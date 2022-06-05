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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class EditTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('post edit               - Edit post');
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

        // Check Editor
        $editor = Resource::get('blog:config')['editor'];

        if (false === Utility::commandExists($editor)) {
            $this->io->error("System command \"{$editor}\" is not found.");

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

        // Call CLI Editor to open file
        $this->editFile($filepath);
    }
}
