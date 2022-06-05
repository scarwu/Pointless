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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class DeleteTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('post delete             - Delete post');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        $formatList = [];
        $options = [];

        foreach (Resource::get('system:constant')['formats'] as $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);
            $formatItem = new $namespace();

            $formatList[] = $formatItem;
            $options[] = $formatItem->getName();
        }

        $index = $this->io->menuSelector('Select Document Format:', $options);

        $this->io->writeln();

        // Load Post
        $type = $formatList[$index]->getType();
        $postList = BlogCore::getPostList($type, true);
        $postList = array_reverse($postList);

        if (0 === count($postList)) {
            $this->io->error('No post(s).');

            return false;
        }

        // Get Post Number
        $options = [];

        foreach ($postList as $index => $post) {
            $text = (false === $post['params']['isPublic'])
                ? "🔒{$post['title']}" : $post['title'];

            $options[] = "[{$post['params']['date']}] {$text}";
        }

        $index = $this->io->menuSelector('Select Post:', $options, 12);

        $this->io->writeln();

        // Get Info
        $filepath = $postList[array_keys($postList)[$index]]['filepath'];
        $title = $postList[array_keys($postList)[$index]]['title'];

        if ('yes' === $this->io->ask("Are you sure delete post \"{$title}\"? (yes)", null, 'red')) {
            Utility::remove($filepath);

            $this->io->writeln();
            $this->io->notice("Successfully removed post \"{$title}\".");
        }
    }
}
