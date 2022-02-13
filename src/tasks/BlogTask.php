<?php
/**
 * Blog Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\BlogCore;
use Pointless\Library\Resource;
use Pointless\Task\Blog\InitTask;
use Pointless\Task\Blog\BuildTask;
use Pointless\Task\Blog\DeployTask;
use Pointless\Task\Blog\ConfigTask;
use Pointless\Extend\Task;

class BlogTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        $this->io->log('blog                    - Blog control');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->showBanner();
        (new InitTask)->helpInfo();
        (new BuildTask)->helpInfo();
        (new DeployTask)->helpInfo();
        (new ConfigTask)->helpInfo();
        $this->io->writeln();

        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        $config = Resource::get('blog:config');

        $this->io->notice('Path:');
        $this->io->log(BLOG_ROOT);
        $this->io->writeln();

        $this->io->notice('Information:');
        $this->io->log("Name     - {$config['name']}");
        $this->io->log("Theme    - {$config['theme']}");
        $this->io->log("Timezone - {$config['timezone']}");
        $this->io->log("Editor   - {$config['editor']}");
    }
}
