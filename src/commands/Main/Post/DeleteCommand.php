<?php
/**
 * Pointless Post Delete Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc,
    NanoCLI\Command,
    NanoCLI\IO;

class DeleteCommand extends Command
{
    public function help()
    {
        IO::log('    post delete - Delete post');
    }

    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();
    }

    public function run()
    {
        // Load Doctype
        $type = [];
        $handle = opendir(APP_ROOT . '/doctype');

        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require APP_ROOT . "/doctype/{$filename}.php";

            $type[] = new $filename;
        }

        closedir($handle);

        // Select Doctype
        foreach ($type as $index => $class) {
            IO::log(sprintf("[ %3d] ", $index) . $class->getName());
        }

        $select = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($type) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($type);
        });

        IO::writeln();

        // Load Markdown
        $list = [];
        $handle = opendir(BLOG_MARKDOWN);

        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            if (!($post = parseMarkdownFile($filename, true))) {
                IO::error("Markdown parse error: {$filename}");
                exit(1);
            }

            if ($type[$select]->getID() !== $post['type']) {
                continue;
            }

            if (isset($post['date']) && isset($post['time'])) {
                $index = "{$post['date']}{$post['time']}";
            } else {
                $index = $post['title'];
            }

            $list[$index]['publish'] = $post['publish'];
            $list[$index]['path'] = BLOG_MARKDOWN . "/{$filename}";
            $list[$index]['title'] = ('' !== $post['title'])
                ? $post['title'] : $filename;
        }

        closedir($handle);

        // Sort List
        uksort($list, 'strnatcasecmp');

        if (0 === count($list)) {
            IO::error('No post(s).');

            return false;
        }

        // Get Post Number
        $count = 0;

        foreach ($list as $post) {
            $text = $post['publish']
                ? sprintf("[ %3d] ", $count) . $post['title']
                : sprintf("[*%3d] ", $count) . $post['title'];

            IO::log($text);

            $count++;
        }

        $number = IO::ask("\nEnter Number:\n-> ", function ($answer) use ($list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($list);
        });

        // Get Info
        $path = $list[array_keys($list)[$number]]['path'];
        $title = $list[array_keys($list)[$number]]['title'];

        if ('yes' === IO::ask("\nAre you sure delete post \"{$title}\"? (yes)\n-> ", null, 'red')) {
            unlink($path);

            IO::notice("Successfully removed post \"{$title}\".");
        }
    }
}
