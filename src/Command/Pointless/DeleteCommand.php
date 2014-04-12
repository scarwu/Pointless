<?php
/**
 * Pointless Delete Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class DeleteCommand extends Command
{
    public function help()
    {
        IO::log('    delete <number or not>');
        IO::log('               - Delete post');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();
    }

    public function run()
    {
        // Load Doctype
        $type = [];
        $handle = opendir(ROOT . '/Doctype');
        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require ROOT . "/Doctype/$filename.php";
            $type[] = new $filename;
        }
        closedir($handle);

        // Select Doctype
        foreach ($type as $index => $class) {
            IO::log(sprintf("[ %3d] ", $index) . $class->getName());
        }
        $select = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($type) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($type);
        });

        IO::writeln();

        // Load Markdown
        $list = [];
        $handle = opendir(MARKDOWN);
        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $post = json_decode($match[1], true);

            if ($type[$select]->getID() !== $post['type']) {
                continue;
            }

            $index = $post['title'];

            $list[$index]['publish'] = $post['publish'];
            $list[$index]['title'] = $post['title'];
            $list[$index]['path'] = MARKDOWN . "/$filename";
        }
        closedir($handle);
        uksort($list, 'strnatcasecmp');

        if (0 === count($list)) {
            IO::error('No post(s).');

            return false;
        }

        // Get Post Number
        $count = 0;
        foreach ($list as $post) {
            if ($post['publish']) {
                IO::log(sprintf("[ %3d] ", $count) . $post['title']);
            } else {
                IO::log(sprintf("[*%3d] ", $count) . $post['title']);
            }

            $count++;
        }

        $number = IO::ask("\nEnter Number:\n-> ", function ($answer) use ($list) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($list);
        });

        $path = $list[array_keys($list)[$number]]['path'];
        $title = $list[array_keys($list)[$number]]['title'];

        if ('yes' === IO::ask("\nAre you sure delete post \"$title\"? (yes)\n-> ", null, 'red')) {
            unlink($path);
            IO::notice("Successfully removed post \"$title\".");
        }
    }
}
