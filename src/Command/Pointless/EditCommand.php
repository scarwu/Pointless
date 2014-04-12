<?php
/**
 * Pointless Edit Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Utility;
use Resource;

class EditCommand extends Command
{
    private $editor;

    public function help()
    {
        IO::log('    edit       - Edit post');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $this->editor = Resource::get('config')['editor'];
        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"$this->editor\" is not found.");

            return false;
        }
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
        system("$this->editor $path < `tty` > `tty`");
    }
}
