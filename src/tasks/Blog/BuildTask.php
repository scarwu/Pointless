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
use Pointless\Library\HTMLGenerator;
use Pointless\Library\ExtensionLoader;
use Pack\CSS;
use Pack\JS;
use Oni\Loader;
use Oni\CLI\Task;

class BuildTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog build  - Generate blog');
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (!Misc::initBlog()) {
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

    /**
     * Run
     */
    public function run()
    {
        $blog = Resource::get('system:config')['blog'];
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Clear Files
        $this->io->notice('Clean Files ...');

        Utility::remove(BLOG_BUILD, BLOG_BUILD);

        // Create README
        $readme = '[Powered by Pointless](https://github.com/scarwu/Pointless)';

        file_put_contents(BLOG_BUILD . '/README.md', $readme);

        // Copy Resource Files
        $this->io->notice('Copy Resource Files ...');

        Utility::copy(BLOG_STATIC, BLOG_BUILD);

        if (file_exists(BLOG_THEME . '/assets/fonts')) {
            Utility::copy(BLOG_THEME . '/assets/fonts', BLOG_BUILD . '/assets/fonts');
        }

        if (file_exists(BLOG_THEME . '/assets/images')) {
            Utility::copy(BLOG_THEME . '/assets/images', BLOG_BUILD . '/assets/images');
        }

        // Compress Assets
        $this->io->notice('Compress Assets ...');

        $this->CSSCompress();
        $this->JSCompress();

        // Initialize Resource Pool
        $this->io->notice('Load Post Files ...');

        $formatList = [];
        $result = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);

            $format = new $className;
            $type = $format->getType();

            $formatList[$type] = $format;
            $result[$type] = [];
        }

        // Load Post
        $postList = Misc::getPostList();

        foreach ($postList as $post) {
            if (!$post['publish']) {
                continue;
            }
            if (!isset($formatList[$post['type']])) {
                continue;
            }

            // Append Post to Result
            $result[$post['type']][] = $formatList[$post['type']]->postHandleAndGetResult($post);
        }

        foreach ($result as $type => $post) {
            Resource::set("post:{$type}", array_reverse($post));
        }

        // Rendering HTML Pages
        $this->io->notice('Rendering HTML ...');

        foreach (Resource::get('theme:config')['handlers'] as $subClassName) {
            $className = 'Pointless\\Handler\\' . ucfirst($subClassName);

            $handler = new $className;
            $type = $handler->getType();

            $handlerList[$type] = new $handler;
        }

        // Render Block
        foreach (Resource::get('theme:config')['views'] as $blockName => $typeList) {
            foreach ($typeList as $type) {
                $handlerList[$type]->renderBlock($blockName);
            }
        }

        // Render Page
        foreach ($handlerList as $handler) {
            $handler->renderPage();
        }

        // Generate Extension
        $this->io->notice('Generating Extensions ...');

        foreach (Resource::get('theme:config')['extensions'] as $subClassName) {
            $className = 'Pointless\\Extension\\' . ucfirst($subClassName);
            (new $className)->run();
        }

        $time = sprintf('%.3f', abs(microtime(true) - $startTime));
        $memory = sprintf('%.3f', abs(memory_get_usage() - $startMemory) / 1024);

        $this->io->info("Generate finish, {$time}s and memory usage {$memory}KB.");

        // Fix Permission
        Misc::fixPermission(BLOG_BUILD);
    }

    /**
     * CSS Compress
     */
    private function CSSCompress()
    {
        $this->io->log('Compressing CSS');

        $cssPack = new CSS();

        foreach (Resource::get('theme:config')['assets']['styles'] as $filename) {
            $filename = preg_replace('/.css$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/assets/styles/{$filename}.css")) {
                $this->io->warning("CSS file \"{$filename}.css\" not found.");

                continue;
            }

            $cssPack->append(BLOG_THEME . "/assets/styles/{$filename}.css");
        }

        $cssPack->save(BLOG_BUILD . '/assets/styles.css', true);
    }

    /**
     * Javascript Compress
     */
    private function JSCompress()
    {
        $this->io->log('Compressing Javascript');

        $jsPack = new JS();

        foreach (Resource::get('theme:config')['assets']['scripts'] as $filename) {
            $filename = preg_replace('/.js$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/assets/scripts/{$filename}.js")) {
                $this->io->warning("Javascript file \"{$filename}.js\" not found.");

                continue;
            }

            $jsPack->append(BLOG_THEME . "/assets/scripts/{$filename}.js");
        }

        $jsPack->save(BLOG_BUILD . '/assets/scripts.js', false);
    }
}
