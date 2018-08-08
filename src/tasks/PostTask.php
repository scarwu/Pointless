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

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class PostTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($is_show_detail = false)
    {
        if ($is_show_detail) {
            $this->io->log('    post        - Show post status');

            // Sub Help Info
            (new \Pointless\Task\Post\AddTask)->helpInfo();
            (new \Pointless\Task\Post\EditTask)->helpInfo();
            (new \Pointless\Task\Post\DeleteTask)->helpInfo();
        } else {
            $this->io->log('    post        - Posts manage');
        }
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

    /**
     * Run
     */
    public function run()
    {
        $this->io->notice('Post Status:');

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();
            $count = count(Misc::getPostList($type));

            $this->io->log("{$count} {$name} post(s).");
        }

        $this->io->writeln();
        $this->io->info('Used command "post -h" for more.');
    }
}
