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
use Michelf\MarkdownExtra;
use Resource;
use Compress;
use HTMLGenerator;
use ExtensionLoader;

class GenCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    gen        - Generate blog');
        IO::writeln('    gen -css   - Compress CSS');
        IO::writeln('    gen -js    - Compress JavaScript');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        require LIBRARY . '/Helper.php';
        require LIBRARY . '/Compress.php';
        require LIBRARY . '/HTMLGenerator.php';
        require LIBRARY . '/ExtensionLoader.php';

        // Load Theme Config
        require THEME . '/Theme.php';
        Resource::set('theme', $theme);

        $blog = Resource::get('config')['blog'];
        $github = Resource::get('config')['github'];

        $start = microtime(true);

        if ($this->hasOptions('css')) {
            if (file_exists(TEMP . '/theme/main.css')) {
                unlink(TEMP . '/theme/main.css');
            }

            IO::writeln('Compress CSS ...', 'yellow');
            $Compress = new Compress();
            $Compress->css();

            $time = sprintf("%.3f", abs(microtime(true) - $start));
            IO::writeln("Generate finish, $time s.", 'green');

            return true;
        }

        if ($this->hasOptions('js')) {
            if (file_exists(TEMP . '/main.js')) {
                unlink(TEMP . '/main.js');
            }

            IO::writeln('Compress Javascript ...', 'yellow');
            $Compress = new Compress();
            $Compress->js();

            $time = sprintf("%.3f", abs(microtime(true) - $start));
            IO::writeln("Generate finish, $time s.", 'green');

            return true;
        }

        $start_mem = memory_get_usage();

        // Clear Public Files
        IO::writeln('Clean Public Files ...', 'yellow');
        recursiveRemove(TEMP, TEMP);

        // Create README
        $readme = '[Powered by Pointless](https://github.com/scarwu/Pointless)';
        file_put_contents(TEMP . '/README.md', $readme);

        // Create Github CNAME
        if ($github['cname']) {
            IO::writeln('Create Github CNAME ...', 'yellow');
            file_put_contents(TEMP . '/CNAME', $blog['dn']);
        }

        // Copy Resource Files
        IO::writeln('Copy Resource Files ...', 'yellow');
        recursiveCopy(RESOURCE, TEMP);

        if (file_exists(THEME . '/Resource')) {
            recursiveCopy(THEME . '/Resource', TEMP . '/theme');
        }

        // Compress CSS and JavaScript
        IO::writeln('Compress CSS & Javascript ...', 'yellow');
        $compress = new Compress();
        $compress->js();
        $compress->css();

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

        $time = sprintf("%.3f", abs(microtime(true) - $start));
        $mem = sprintf("%.3f", abs(memory_get_usage() - $start_mem) / 1024);
        IO::writeln("Generate finish, $time s and memory usage $mem kb.", 'green');

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            $user = fileowner(HOME);
            $group = filegroup(HOME);
            system("chown $user.$group -R " . TEMP);
        }
    }

    /**
     * Initialize Resource Pool
     */
    private function initResourcePool()
    {
        $article = [];
        $article_url = Resource::get('config')['article_url'];
        $article_url = trim($article_url, '/');

        // Handle Markdown
        IO::writeln('Load and Initialize Markdown');
        $handle = opendir(MARKDOWN);
        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $post = json_decode($match[1], true);

            if (null === $post) {
                IO::writeln("Attribute Error: $filename", 'red');
                exit(1);
            }

            if (!$post['publish']) {
                continue;
            }

            // Append Static Page
            if ('static' === $post['type']) {
                Resource::append('static', [
                    'title' => $post['title'],
                    'url' => $post['url'],
                    'content' => MarkdownExtra::defaultTransform($match[2]),
                    'message' => $post['message']
                ]);
            }

            // Create Article
            if ('article' === $post['type']) {
                list($year, $month, $day) = explode('-', $post['date']);
                list($hour, $minute, $second) = explode(':', $post['time']);
                $timestamp = strtotime("$day-$month-$year {$post['time']}");

                // Generate custom url
                $url = str_replace([
                    ':year', ':month', ':day',
                    ':hour', ':minute', ':second', ':timestamp',
                    ':title', ':url'
                ], [
                    $year, $month, $day,
                    $hour, $minute, $second, $timestamp,
                    $post['title'], $post['url']
                ], $article_url);

                $post['tag'] = explode('|', $post['tag']);
                sort($post['tag']);

                $article[$timestamp] = [
                    'title' => $post['title'],
                    'url' => $url,
                    'content' => MarkdownExtra::defaultTransform($match[2]),
                    'date' => $post['date'],
                    'time' => $post['time'],
                    'category' => $post['category'],
                    'keywords' => $post['keywords'],
                    'tag' => $post['tag'],
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'hour' => $hour,
                    'minute' => $minute,
                    'second' => $second,
                    'timestamp' => $timestamp,
                    'message' => $post['message']
                ];
            }
        }
        closedir($handle);

        krsort($article);
        Resource::set('article', $article);
    }
}
