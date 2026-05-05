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
    public function helpInfo(): void
    {
        $this->io->log('post delete             - Delete post');
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
        $title = $postData['title'];
        $filepath = $postData['filepath'];

        $anwser = $this->io->ask("Are you sure delete post \"{$title}\"? [y/N]", null, 'red');
        $anwser = strtolower($anwser);

        $this->io->writeln();

        if ('y' === $anwser) {
            Utility::remove($filepath);

            $this->io->notice("Successfully removed post \"{$title}\".");
        }
    }
}
