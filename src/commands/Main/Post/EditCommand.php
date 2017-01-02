<?php
/**
 * Pointless Post Edit Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class EditCommand extends Command
{
    /**
     * @var string
     */
    private $editor;

    /**
     * Help
     */
    public function help()
    {
        IO::log('    post edit   - Edit post');
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

        // Check Editor
        $this->editor = Resource::get('config')['editor'];

        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"{$this->editor}\" is not found.");

            return false;
        }
    }

    /**
     * Run
     */
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
            if ($post['publish']) {
                IO::log(sprintf("[ %3d] ", $count) . $post['title']);
            } else {
                IO::log(sprintf("[*%3d] ", $count) . $post['title']);
            }

            $count++;
        }

        $number = IO::ask("\nEnter Number:\n-> ", function ($answer) use ($list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($list);
        });

        // Get Info
        $path = $list[array_keys($list)[$number]]['path'];

        system("{$this->editor} {$path} < `tty` > `tty`");
    }
}
