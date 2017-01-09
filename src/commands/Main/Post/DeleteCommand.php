<?php
/**
 * Pointless Post Delete Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class DeleteCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    post delete - Delete post');
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
    }

    public function run()
    {
        $doctype_list = [];

        foreach (Resource::get('constant')['doctypes'] as $index => $name) {
            $class_name = 'Pointless\\Doctype\\' . ucfirst($name) . 'Doctype';
            $doctype_list[$index] = new $class_name;

            IO::log(sprintf('[ %3d] ', $index) . $doctype_list[$index]->getName());
        }

        $index = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($doctype_list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($doctype_list);
        });

        IO::writeln();

        // Load Markdown
        $doctype = $doctype_list[$index]->getType();
        $markdown_list = Misc::getMarkdownList($doctype, true);

        if (0 === count($markdown_list)) {
            IO::error('No post(s).');

            return false;
        }

        // Get Post Number
        foreach ($markdown_list as $index => $post) {
            $text = $post['publish']
                ? sprintf("[ %3d] ", $index) . $post['title']
                : sprintf("[*%3d] ", $index) . $post['title'];

            IO::log($text);
        }

        $index = IO::ask("\nEnter Number:\n-> ", function ($answer) use ($markdown_list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($markdown_list);
        });

        // Get Info
        $path = $markdown_list[array_keys($markdown_list)[$index]]['path'];
        $title = $markdown_list[array_keys($markdown_list)[$index]]['title'];

        if ('yes' === IO::ask("\nAre you sure delete post \"{$title}\"? (yes)\n-> ", null, 'red')) {
            unlink($path);

            IO::writeln();
            IO::notice("Successfully removed post \"{$title}\".");
        }
    }
}
