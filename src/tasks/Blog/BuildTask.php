<?php
/**
 * Blog Build Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Blog;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Loader;
use Oni\CLI\Task;
use Oni\Web\View;

class BuildTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog build      - Generate blog');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }

        // Require Theme Attr
        require BLOG_THEME . '/config.php';

        Resource::set('theme:config', $config);

        // Loader Append
        Loader::append('Pointless\Handler', BLOG_THEME . '/handlers');
        Loader::append('Pointless\Extension', BLOG_EXTENSION);
        Loader::append('Pointless\Extension', APP_ROOT . '/sample/extensions');
    }

    public function run()
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Get Resources
        $systemConfig = Resource::get('system:config');
        $systemConstant = Resource::get('system:constant');
        $themeConfig = Resource::get('theme:config');

        // Clear Files
        $this->io->notice('Clean Files ...');

        Utility::remove(BLOG_BUILD, BLOG_BUILD);

        // Create README
        $readme = '[Powered by Pointless](https://github.com/scarwu/Pointless)';

        file_put_contents(BLOG_BUILD . '/README.md', $readme);

        // Copy Assets Files
        $this->io->notice('Copy Assets Files ...');

        Utility::copy(BLOG_STATIC, BLOG_BUILD);

        if (file_exists(BLOG_THEME . '/assets')) {
            Utility::copy(BLOG_THEME . '/assets', BLOG_BUILD . '/assets');
        }

        // Load Posts
        $this->io->notice('Load Posts ...');

        $postBundle = [];

        foreach ($systemConstant['formats'] as $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);

            $instance = new $namespace();
            $type = $instance->getType();

            $postBundle[$type] = [];

            foreach (Misc::getPostList($type) as $post) {
                if (false === $post['isPublic']) {
                    continue;
                }

                $postBundle[$type][] = $instance->convertPost($post);
            }
        }

        foreach ($postBundle as $type => $post) {
            $postBundle[$type] = array_reverse($post);
        }

        // Rendering HTML Pages
        $this->io->notice('Rendering HTML ...');

        $handlerList = [];

        foreach ($themeConfig['handlers'] as $name) {
            if (!isset($handlerList[$name])) {
                $namespace = 'Pointless\\Handler\\' . ucfirst($name);

                $instance = new $namespace();
                $type = $instance->getType();

                $handlerList[$type] = $instance;
                $handlerList[$type]->initData([
                    'systemConfig' => $systemConfig,
                    'systemConstant' => $systemConstant,
                    'themeConfig' => $themeConfig,
                    'postBundle' => $postBundle
                ]);
            }
        }

        // Init View
        $view = View::init();
        $view->setAttr('path', BLOG_THEME . '/views');
        $view->setLayoutPath('index');

        // Get Side Data
        $sideList = [];

        foreach ($themeConfig['views']['side'] as $name) {
            if (!isset($handlerList[$name])) {
                continue;
            }

            $sideList[$name] = $handlerList[$name]->getSideData();
        }

        // Get Container Data List & Render
        $postPathList = [];

        foreach ($themeConfig['views']['container'] as $name) {
            $view->setContentPath("container/{$name}");

            foreach ($handlerList[$name]->getContainerDataList() as $path => $container) {
                $this->io->log("Render: {$path}");

                $postPathList[] = $path;

                $view->setData([
                    'systemConfig' => $systemConfig,
                    'systemConstant' => $systemConstant,
                    'themeConfig' => $themeConfig,
                    'sideList' => $sideList,
                    'container' => $container
                ]);

                $this->saveToDisk($path, $view->render());
            }
        }

        // Generate Extension
        $this->io->notice('Generating Extensions ...');

        foreach ($themeConfig['extensions'] as $name) {
            $namespace = 'Pointless\\Extension\\' . ucfirst($name);

            $instance = new $namespace();
            $path = $instance->getPath();

            $this->io->log("Render: {$path}");

            $this->saveToDisk($path, $instance->render([
                'systemConfig' => $systemConfig,
                'systemConstant' => $systemConstant,
                'themeConfig' => $themeConfig,
                'postBundle' => $postBundle,
                'postPathList' => $postPathList
            ]));
        }

        // Fix Permission
        Misc::fixPermission(BLOG_BUILD);

        // Print Execution Time
        $time = sprintf('%.3f', abs(microtime(true) - $startTime));
        $memory = sprintf('%.3f', abs(memory_get_usage() - $startMemory) / 1024);

        $this->io->info("Generate finish, {$time}s and memory usage {$memory}KB.");
    }

    /**
     * Save To Disk
     *
     * @param string $path
     * @param string $data
     */
    private function saveToDisk($path, $data)
    {
        $realpath = BLOG_BUILD . "/{$path}";

        if (!preg_match('/\.(html|xml)$/', $realpath)) {
            if (false === file_exists($realpath)) {
                mkdir($realpath, 0755, true);
            }

            $realpath = "{$realpath}/index.html";
        } else {
            if (false === file_exists(dirname($realpath))) {
                mkdir(dirname($realpath), 0755, true);
            }
        }

        file_put_contents($realpath, $data);
    }
}
