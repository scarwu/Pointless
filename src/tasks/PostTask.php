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

use Pointless\Library\Core;
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
    public function helpInfo($isShowDetail = false)
    {
        if (true === $isShowDetail) {
            $this->io->log('    post                    - Show posts status');

            // Sub Help Info
            (new AddTask)->helpInfo();
            (new EditTask)->helpInfo();
            (new DeleteTask)->helpInfo();
        } else {
            $this->io->log('    post                    - Posts manage');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        if (true === $this->io->hasOptions('h')) {
            $this->showBanner();
            $this->helpInfo(true);

            return false;
        }

        // Init Blog
        if (false === Core::initBlog()) {
            return false;
        }
    }

    public function down()
    {
        $this->io->writeln();
        $this->io->info('Used command "post -h" for more.');
    }

    public function run()
    {
        $this->io->notice('Post Status:');

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();
            $count = count(Core::getPostList($type));

            $this->io->log("{$count} {$name} post(s).");
        }
    }
}
