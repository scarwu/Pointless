<?php
/**
 * Pointless Gen Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;
use Pack\CSS;
use Pack\JS;
use Parsedown;

use Utility;
use Resource;
use HTMLGenerator;
use ExtensionLoader;

class GenCommand extends Command
{
    public function help()
    {
        IO::log('    gen        - Generate blog');
        IO::log('    gen -css   - Compress CSS');
        IO::log('    gen -js    - Compress JavaScript');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        require LIBRARY . '/Helper.php';
        require LIBRARY . '/HTMLGenerator.php';
        require LIBRARY . '/ExtensionLoader.php';

        // Load Theme Config
        require THEME . '/Theme.php';
        Resource::set('theme', $theme);
    }

    public function run()
    {
        $blog = Resource::get('config')['blog'];
        $start = microtime(true);
        $start_mem = memory_get_usage();

        if ($this->hasOptions('css')) {
            IO::notice('Clean Files ...');
            if (file_exists(TEMP . '/theme/main.css')) {
                unlink(TEMP . '/theme/main.css');
            }

            IO::notice('Compress Assets ...');
            $this->CSSCompress();
        }

        if ($this->hasOptions('js')) {
            IO::notice('Clean Files ...');
            if (file_exists(TEMP . '/theme/main.js')) {
                unlink(TEMP . '/theme/main.js');
            }

            IO::notice('Compress Assets ...');
            $this->JSCompress();
        }

        if (!$this->hasOptions('css') && !$this->hasOptions('js')) {
            // Clear Files
            IO::notice('Clean Files ...');
            Utility::remove(TEMP, TEMP);

            // Create README
            $readme = '[Powered by Pointless](https://github.com/scarwu/Pointless)';
            file_put_contents(TEMP . '/README.md', $readme);

            // Copy Resource Files
            IO::notice('Copy Resource Files ...');
            Utility::copy(RESOURCE, TEMP);

            if (file_exists(THEME . '/Resource')) {
                Utility::copy(THEME . '/Resource', TEMP . '/theme');
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

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            Utility::chown(TEMP, fileowner(HOME), filegroup(HOME));
        }
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

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $post = json_decode($match[1], true);

            if (null === $post) {
                IO::error("Post header error: $filename");
                exit(1);
            }

            if (!$post['publish']) {
                continue;
            }

            if (!isset($type[$post['type']])) {
                continue;
            }

            // Transfer Markdown to HTML
            $post['content'] = $parsedown->text($match[2]);

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

        foreach ((array) Resource::get('theme')['css'] as $filename) {
            $filename = preg_replace('/.css$/', '', $filename);

            if (!file_exists(THEME . "/Css/$filename.css")) {
                IO::warning("CSS file \"$filename.css\" not found.");
                continue;
            }

            $css_pack->append(THEME . "/Css/$filename.css");
        }

        $css_pack->save(TEMP . '/theme/main.css', true);
    }

    /**
     * Javascript Compress
     */
    private function JSCompress()
    {
        IO::log('Compressing Javascript');

        $js_pack = new CSS();

        foreach ((array) Resource::get('theme')['js'] as $filename) {
            $filename = preg_replace('/.js$/', '', $filename);

            if (!file_exists(THEME . "/Js/$filename.js")) {
                IO::warning("Javascript file \"$filename.js\" not found.");
                continue;
            }

            $js_pack->append(THEME . "/Js/$filename.js");
        }

        $js_pack->save(TEMP . '/theme/main.js', false);
    }
}
