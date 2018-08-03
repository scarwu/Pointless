<?php
/**
 * Pointless Post Delete Command
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Oni\CLI\Command;
use Oni\CLI\IO;

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
        $formatList = [];

        foreach (Resource::get('attr:constant')['formats'] as $index => $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $formatList[$index] = new $className;

            IO::log(sprintf('[ %3d] ', $index) . $formatList[$index]->getName());
        }

        $index = IO::ask("\nSelect Document Format:\n-> ", function ($answer) use ($formatList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($formatList);
        });

        IO::writeln();

        // Load Markdown
        $type = $formatList[$index]->getType();
        $postList = Misc::getPostList($type, true);

        if (0 === count($postList)) {
            IO::error('No post(s).');

            return false;
        }

        // Get Post Number
        foreach ($postList as $index => $post) {
            $text = $post['publish']
                ? sprintf("[ %3d] ", $index) . $post['title']
                : sprintf("[*%3d] ", $index) . $post['title'];

            IO::log($text);
        }

        $index = IO::ask("\nEnter Number:\n-> ", function ($answer) use ($postList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($postList);
        });

        // Get Info
        $path = $postList[array_keys($postList)[$index]]['path'];
        $title = $postList[array_keys($postList)[$index]]['title'];

        if ('yes' === IO::ask("\nAre you sure delete post \"{$title}\"? (yes)\n-> ", null, 'red')) {
            unlink($path);

            IO::writeln();
            IO::notice("Successfully removed post \"{$title}\".");
        }
    }
}
