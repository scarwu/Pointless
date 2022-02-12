<?php
/**
 * Post Delete Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Post;

use Pointless\Library\Core;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class DeleteTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    post delete             - Delete post');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Core::initBlog()) {
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

        // Load Markdown
        $type = $formatList[$index]->getType();
        $postList = Core::getPostList($type, true);

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
        $path = $postList[array_keys($postList)[$index]]['path'];
        $title = $postList[array_keys($postList)[$index]]['title'];

        if ('yes' === $this->io->ask("\nAre you sure delete post \"{$title}\"? (yes)\n-> ", null, 'red')) {
            unlink($path);

            $this->io->writeln();
            $this->io->notice("Successfully removed post \"{$title}\".");
        }
    }
}
