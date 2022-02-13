<?php
/**
 * Post Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\BlogCore;
use Pointless\Library\Resource;
use Pointless\Task\Post\AddTask;
use Pointless\Task\Post\EditTask;
use Pointless\Task\Post\DeleteTask;
use Pointless\Extend\Task;

class PostTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('post                    - Posts manage');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->showBanner();
        (new AddTask)->helpInfo();
        (new EditTask)->helpInfo();
        (new DeleteTask)->helpInfo();
        $this->io->writeln();

        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        $this->io->notice('Status:');

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();
            $count = count(BlogCore::getPostList($type));

            $this->io->log("{$count} {$name} post(s).");
        }
    }
}
