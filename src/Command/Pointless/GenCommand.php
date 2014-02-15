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
use Resource;
use Compress;
use HTMLGenerator;
use ExtensionLoader;
use Michelf\MarkdownExtra;

class GenCommand extends Command {
    public function __construct() {
        parent::__construct();
    }
    
    public function help() {
        IO::writeln('    gen        - Generate blog');
        IO::writeln('    gen -css   - Compress CSS');
        IO::writeln('    gen -js    - Compress Javascript');
    }

    public function run() {
        require LIBRARY . '/Helper.php';
        require LIBRARY . '/Compress.php';
        require LIBRARY . '/HTMLGenerator.php';
        require LIBRARY . '/ExtensionLoader.php';
        require VENDOR . '/Markdown/Michelf/MarkdownExtra.inc.php';

        $this->config = Resource::get('config');
        $start = microtime(TRUE);

        if($this->hasOptions('css')) {
            if(file_exists(TEMP . '/main.css'))
                unlink(TEMP . '/main.css');

            IO::writeln('Compress CSS ...', 'yellow');
            $Compress = new Compress();
            $Compress->css(THEME . '/Assets/Css', TEMP . '/theme');

            $time = sprintf("%.3f", abs(microtime(TRUE) - $start));
            IO::writeln("Generate finish, $time s.", 'green');

            return;
        }

        if($this->hasOptions('js')) {
            if(file_exists(TEMP . '/main.js'))
                unlink(TEMP . '/main.js');

            IO::writeln('Compress Javascript ...', 'yellow');
            $Compress = new Compress();
            $Compress->js(THEME . '/Assets/Js', TEMP . '/theme');

            $time = sprintf("%.3f", abs(microtime(TRUE) - $start));
            IO::writeln("Generate finish, $time s.", 'green');

            return;
        }

        $start_mem = memory_get_usage();

        // Clear Public Files
        IO::writeln('Clean Public Files ...', 'yellow');
        recursiveRemove(TEMP);
        
        // Create README
        $handle = fopen(TEMP . '/README.md', 'w+');
        fwrite($handle, '[Powered by Pointless](https://github.com/scarwu/Pointless)');
        fclose($handle);

        // Create Github CNAME
        if($this->config['github_cname']) {
            IO::writeln('Create Github CNAME ...', 'yellow');
            $handle = fopen(TEMP . '/CNAME', 'w+');
            fwrite($handle, $this->config['blog_dn']);
            fclose($handle);
        }

        // Copy Resource Files
        IO::writeln('Copy Resource Files ...', 'yellow');
        recursiveCopy(RESOURCE, TEMP);
        
        if(file_exists(THEME . '/Resource'))
            recursiveCopy(THEME . '/Resource', TEMP . '/theme');

        // Compress CSS and JavaScript
        IO::writeln('Compress CSS & Javascript ...', 'yellow');
        $compress = new Compress();
        $compress->js(THEME . '/Assets/Js', TEMP . '/theme');
        $compress->css(THEME . '/Assets/Css', TEMP . '/theme');
        
        // Initialize Resource Pool
        IO::writeln('Initialize Resource Pool ...', 'yellow');
        $this->initResourcePool();

        // Generate HTML Pages
        IO::writeln('Generating HTML ...', 'yellow');
        $html = new HTMLGenerator();
        $html->run();

        // Generate Extension
        IO::writeln('Generating Extensions ...', 'yellow');
        $extension = new ExtensionLoader();
        $extension->run();
        
        $time = sprintf("%.3f", abs(microtime(TRUE) - $start));
        $mem = sprintf("%.3f", abs(memory_get_usage() - $start_mem) / 1024);
        IO::writeln("Generate finish, $time s and memory usage $mem kb.", 'green');
    }

    /**
     * Initialize Resource Pool
     */
    private function initResourcePool() {
        $article = [];

        // Handle Markdown
        IO::writeln('Load and Initialize Markdown');
        $handle = opendir(MARKDOWN);
        while($filename = readdir($handle)) {
            if('.' == $filename || '..' == $filename || !preg_match('/.md$/', $filename))
                continue;

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $temp = json_decode($match[1], TRUE);

            if(NULL == $temp) {
                IO::writeln('Attribute Error: ' . $filename, 'red');
                exit(1);
            }

            if('static' == $temp['type']) {
                Resource::append('static', [
                    'title' => $temp['title'],
                    'url' => $temp['url'],
                    'content' => MarkdownExtra::defaultTransform($match[2]),
                    'message' => isset($temp['message']) ? $temp['message'] : TRUE
                ]);
            }

            if('article' == $temp['type']) {
                if(!(isset($temp['publish']) ? $temp['publish'] : TRUE))
                    continue;

                $date = explode('-', $temp['date']);
                $time = explode(':', $temp['time']);
                $timestamp = strtotime("$date[2]-$date[1]-$date[0] {$temp['time']}");

                // Generate custom url
                $url = trim($this->config['article_url'], '/');
                $url = str_replace([
                    ':year', ':month', ':day',
                    ':hour', ':minute', ':second', ':timestamp',
                    ':title', ':url'
                ], [
                    $date[0], $date[1], $date[2],
                    $time[0], $time[1], $time[2], $timestamp,
                    $temp['title'], $temp['url']
                ], $url);

                $article[$timestamp] = [
                    'title' => $temp['title'],
                    'url' => $url,
                    'content' => MarkdownExtra::defaultTransform($match[2]),
                    'date' => $temp['date'],
                    'time' => $temp['time'],
                    'category' => $temp['category'],
                    'keywords' => $temp['keywords'],
                    'tag' => explode('|', $temp['tag']),
                    'year' => $date[0],
                    'month' => $date[1],
                    'day' => $date[2],
                    'hour' => $time[0],
                    'minute' => $time[1],
                    'second' => $time[2],
                    'timestamp' => $timestamp,
                    'message' => isset($temp['message']) ? $temp['message'] : TRUE
                ];
            }
        }
        closedir($handle);

        krsort($article);
        Resource::set('article', $article);
    }
}