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
use Oni\CLI\Command;
use Oni\CLI\IO;

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
        $this->editor = Resource::get('attr:config')['editor'];

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
        $formatList = [];

        foreach (Resource::get('attr:constant')['formats'] as $index => $sublClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($sublClassName);
            $formatList[$index] = new $className;

            IO::log(sprintf('[ %3d] ', $index) . $formatList[$index]->getName());
        }

        $index = IO::ask("\nSelect Document Format:\n-> ", function ($answer) use ($formatList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($formatList);
        });

        IO::writeln();

        // Load Post
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
        $postPath = $postList[array_keys($postList)[$index]]['path'];

        // Call CLI Editor to open file
        Misc::editFile($postPath);
    }
}
