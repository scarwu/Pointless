<?php
/**
 * Post Edit Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Post;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class EditTask extends Task
{
    /**
     * @var string
     */
    private $editor;

    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    post edit       - Edit post');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }

        // Check Editor
        $this->editor = Resource::get('system:config')['editor'];

        if (false === Utility::commandExists($this->editor)) {
            $this->io->error("System command \"{$this->editor}\" is not found.");

            return false;
        }
    }

    public function run()
    {
        $formatList = [];

        foreach (Resource::get('system:constant')['formats'] as $index => $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);

            $formatList[$index] = new $namespace();

            $this->io->log(sprintf('[ %3d] ', $index) . $formatList[$index]->getName());
        }

        $index = $this->io->ask("\nSelect Document Format:\n-> ", function ($answer) use ($formatList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($formatList);
        });

        $this->io->writeln();

        // Load Post
        $type = $formatList[$index]->getType();
        $postList = Misc::getPostList($type, true);

        if (0 === count($postList)) {
            $this->io->error('No post(s).');

            return false;
        }

        // Get Post Number
        foreach ($postList as $index => $post) {
            $text = $post['isPublic']
                ? sprintf("[ %3d] ", $index) . $post['title']
                : sprintf("[*%3d] ", $index) . $post['title'];

            $this->io->log($text);
        }

        $index = $this->io->ask("\nEnter Number:\n-> ", function ($answer) use ($postList) {
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
