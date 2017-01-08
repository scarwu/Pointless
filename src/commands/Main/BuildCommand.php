<?php
/**
 * Pointless Build Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Library\HTMLGenerator;
use Pointless\Library\ExtensionLoader;
use Pack\CSS;
use Pack\JS;
use NanoCLI\IO;
use NanoCLI\Loader;
use NanoCLI\Command;

class BuildCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    build       - Generate blog');
        IO::log('    build -css  - Compress CSS');
        IO::log('    build -js   - Compress JavaScript');
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

        // Load Theme Config
        require BLOG_THEME . '/theme.php';

        Resource::set('theme', $theme);

        // Set Loader
        Loader::set('Pointless\Handler', BLOG_THEME . '/handlers');
        Loader::set('Pointless\Extension', BLOG_EXTENSION);
        Loader::set('Pointless\Extension', APP_ROOT . '/sample/extensions');
    }

    /**
     * Run
     */
    public function run()
    {
        $blog = Resource::get('config')['blog'];
        $start = microtime(true);
        $start_mem = memory_get_usage();

        if ($this->hasOptions('css')) {
            IO::notice('Clean Files ...');

            if (file_exists(BLOG_BUILD . '/theme/main.css')) {
                unlink(BLOG_BUILD . '/theme/main.css');
            }

            IO::notice('Compress Assets ...');

            $this->CSSCompress();
        }

        if ($this->hasOptions('js')) {
            IO::notice('Clean Files ...');

            if (file_exists(BLOG_BUILD . '/theme/main.js')) {
                unlink(BLOG_BUILD . '/theme/main.js');
            }

            IO::notice('Compress Assets ...');

            $this->JSCompress();
        }

        if (!$this->hasOptions('css') && !$this->hasOptions('js')) {
            // Clear Files
            IO::notice('Clean Files ...');

            Utility::remove(BLOG_BUILD, BLOG_BUILD);

            // Create README
            $readme = '[Powered by Pointless](https://github.com/scarwu/Pointless)';

            file_put_contents(BLOG_BUILD . '/README.md', $readme);

            // Copy Resource Files
            IO::notice('Copy Resource Files ...');
            Utility::copy(BLOG_STATIC, BLOG_BUILD);

            if (file_exists(BLOG_THEME . '/assets/fonts')) {
                Utility::copy(BLOG_THEME . '/assets/fonts', BLOG_BUILD . '/assets/fonts');
            }

            if (file_exists(BLOG_THEME . '/assets/images')) {
                Utility::copy(BLOG_THEME . '/assets/images', BLOG_BUILD . '/assets/images');
            }

            // Compress Assets
            IO::notice('Compress Assets ...');

            $this->CSSCompress();
            $this->JSCompress();

            // Initialize Resource Pool
            IO::notice('Load Markdown Files ...');

            $this->loadMarkdown();

            // Generate HTML Pages
            IO::notice('Generating HTML ...');

            $html = new HTMLGenerator();
            $html->run();

            // Generate Extension
            IO::notice('Generating Extensions ...');

            foreach (Resource::get('theme')['extensions'] as $filename) {
                $class_name = 'Pointless\\Extension\\' . ucfirst($filename);
                (new $class_name)->run();
            }
        }

        $time = sprintf('%.3f', abs(microtime(true) - $start));
        $mem = sprintf('%.3f', abs(memory_get_usage() - $start_mem) / 1024);

        IO::info("Generate finish, $time s and memory usage $mem KB.");

        // Fix Permission
        Misc::fixPermission(BLOG_BUILD);
    }

    /**
     * Load Markdown
     */
    private function loadMarkdown()
    {
        $doctype_list = [];
        $result = [];

        foreach (Resource::get('constant')['doctypes'] as $doctype) {
            $class_name = 'Pointless\\Doctype\\' . ucfirst($doctype) . 'Doctype';
            $doctype_list[lcfirst($doctype)] = new $class_name;
            $result[lcfirst($doctype)] = [];
        }

        // Load Markdown
        $markdown_list = Misc::getMarkdownList();

        foreach ($markdown_list as $post) {
            if (!$post['publish']) {
                continue;
            }

            $type = $post['type'];

            if (!isset($doctype_list[$type])) {
                continue;
            }

            // Append Post to Result
            $result[$type][] = $doctype_list[$type]->postHandleAndGetResult($post);
        }

        Resource::set('post', $result);
    }

    /**
     * CSS Compress
     */
    private function CSSCompress()
    {
        IO::log('Compressing CSS');

        $css_pack = new CSS();

        foreach (Resource::get('theme')['assets']['styles'] as $filename) {
            $filename = preg_replace('/.css$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/assets/styles/{$filename}.css")) {
                IO::warning("CSS file \"{$filename}.css\" not found.");
                continue;
            }

            $css_pack->append(BLOG_THEME . "/assets/styles/{$filename}.css");
        }

        $css_pack->save(BLOG_BUILD . '/assets/styles.css', true);
    }

    /**
     * Javascript Compress
     */
    private function JSCompress()
    {
        IO::log('Compressing Javascript');

        $js_pack = new JS();

        foreach (Resource::get('theme')['assets']['scripts'] as $filename) {
            $filename = preg_replace('/.js$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/assets/scripts/{$filename}.js")) {
                IO::warning("Javascript file \"{$filename}.js\" not found.");
                continue;
            }

            $js_pack->append(BLOG_THEME . "/assets/scripts/{$filename}.js");
        }

        $js_pack->save(BLOG_BUILD . '/assets/scripts.js', false);
    }
}
