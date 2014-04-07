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
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    delete <number or not>');
        IO::writeln('               - Delete post');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

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
            IO::writeln(sprintf("[ %3d] ", $index) . $class->getName());
        }
        $select = IO::question("\nSelect Document Type:\n-> ", null, function ($answer) use ($type) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($type);
        });

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
            IO::writeln('No post(s).', 'red');

            return false;
        }

        // Get Post Number
        $count = 0;
        foreach ($list as $post) {
            if ($post['publish']) {
                IO::writeln(sprintf("[ %3d] ", $count) . $post['title']);
            } else {
                IO::writeln(sprintf("[*%3d] ", $count) . $post['title']);
            }

            $count++;
        }

        $number = IO::question("\nEnter Number:\n-> ", null, function ($answer) use ($list) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($list);
        });

        $path = $list[array_keys($list)[$number]]['path'];
        $title = $list[array_keys($list)[$number]]['title'];
        IO::write("Are you sure delete post \"$title\"? (yes)\n-> ", 'red');
        if ('yes' === IO::read()) {
            unlink($path);
            IO::writeln("Successfully removed post \"$title\".");
        }
    }
}
