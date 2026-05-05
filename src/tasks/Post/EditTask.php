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
    public function helpInfo(): void
    {
        $this->io->log('post edit               - Edit post');
    }

    /**
     * Lifecycle Funtions
     */
    #[\Override]
    public function up(): mixed
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

    #[\Override]
    public function run(array $params = []): void
    {
        // Select Format Item
        $formatItem = $this->selectFormatItem();

        // Select Post Data
        $postData = $this->selectPostData($formatItem->getType());

        if (!is_array($postData)) {
            $this->io->error('No post(s).');

            return;
        }

        // Get Info
        $filepath = $postData['filepath'];

        // Call CLI Editor to open file
        $this->editFile($filepath);
    }
}
