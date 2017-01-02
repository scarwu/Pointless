<?php
/**
 * Pointless Gen Command
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
use Parsedown;
use Pack\CSS;
use Pack\JS;
use NanoCLI\Command;
use NanoCLI\IO;

class GenCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    gen         - Generate blog');
        IO::log('    gen -css    - Compress CSS');
        IO::log('    gen -js     - Compress JavaScript');
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

        require LIBRARY . '/Helper.php';
        require LIBRARY . '/HTMLGenerator.php';
        require LIBRARY . '/ExtensionLoader.php';

        // Load Theme Config
        require BLOG_THEME . '/Theme.php';
        Resource::set('theme', $theme);
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
            Utility::copy(RESOURCE, BLOG_BUILD);

            if (file_exists(BLOG_THEME . '/Resource')) {
                Utility::copy(BLOG_THEME . '/Resource', BLOG_BUILD . '/theme');
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
            $extension = new ExtensionLoader();
            $extension->run();
        }

        $time = sprintf("%.3f", abs(microtime(true) - $start));
        $mem = sprintf("%.3f", abs(memory_get_usage() - $start_mem) / 1024);
        IO::info("Generate finish, $time s and memory usage $mem KB.");

        // Fix Permission
        Misc::fixPermission(BLOG_BUILD);
    }

    /**
     * Load Markdown
     */
    private function loadMarkdown()
    {
        $parsedown = new Parsedown();

        // Load Doctype
        $type = [];
        $handle = opendir(ROOT . '/Doctype');
        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require ROOT . "/Doctype/$filename.php";
            $class = new $filename;
            $type[$class->getID()] = $class;
        }
        closedir($handle);

        // Read Directory
        $handle = opendir(MARKDOWN);
        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            if (!($post = parseMarkdownFile($filename))) {
                IO::error("Markdown parse error: $filename");
                exit(1);
            }

            if (!$post['publish']) {
                continue;
            }

            if (!isset($type[$post['type']])) {
                continue;
            }

            // Transfer Markdown to HTML
            $post['content'] = $parsedown->text($post['content']);

            // Append Post to Result
            if (!isset($result[$post['type']])) {
                $result[$post['type']] = [];
            }

            $post = $type[$post['type']]->postHandleAndGetResult($post);

            if (isset($post['date']) && isset($post['time'])) {
                $index = $post['date'] . $post['time'];
            } else {
                $index = $post['title'];
            }

            $result[$post['type']][$index] = $post;
        }

        foreach (array_keys($result) as $key) {
            krsort($result[$key]);
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

        foreach (Resource::get('theme')['css'] as $filename) {
            $filename = preg_replace('/.css$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/Css/$filename.css")) {
                IO::warning("CSS file \"$filename.css\" not found.");
                continue;
            }

            $css_pack->append(BLOG_THEME . "/Css/$filename.css");
        }

        $css_pack->save(BLOG_BUILD . '/theme/main.css', true);
    }

    /**
     * Javascript Compress
     */
    private function JSCompress()
    {
        IO::log('Compressing Javascript');

        $js_pack = new CSS();

        foreach (Resource::get('theme')['js'] as $filename) {
            $filename = preg_replace('/.js$/', '', $filename);

            if (!file_exists(BLOG_THEME . "/Js/$filename.js")) {
                IO::warning("Javascript file \"$filename.js\" not found.");
                continue;
            }

            $js_pack->append(BLOG_THEME . "/Js/$filename.js");
        }

        $js_pack->save(BLOG_BUILD . '/theme/main.js', false);
    }
}
