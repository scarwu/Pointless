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

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class BlogTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($is_show_detail = false)
    {
        if ($is_show_detail) {
            $this->io->log('    blog        - Swho blog status');

            // Sub Help Info
            (new \Pointless\Task\Blog\InitTask)->helpInfo();
            (new \Pointless\Task\Blog\SetTask)->helpInfo();
            (new \Pointless\Task\Blog\ConfigTask)->helpInfo();
            (new \Pointless\Task\Blog\BuildTask)->helpInfo();
            (new \Pointless\Task\Blog\DeployTask)->helpInfo();
        } else {
            $this->io->log('    blog        - Blog control');
        }
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $config = Resource::get('system:config');

        $this->io->notice('Blog Path:');
        $this->io->log(BLOG_ROOT);

        $this->io->writeln();
        $this->io->notice('Blog Information:');
        $this->io->log("Name     - {$config['blog']['name']}");
        $this->io->log("Theme    - {$config['theme']}");
        $this->io->log("Timezone - {$config['timezone']}");
        $this->io->log("Editor   - {$config['editor']}");

        $this->io->writeln();
        $this->io->info('Used command "blog -h" for more.');
    }
}
