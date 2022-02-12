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
    public function helpInfo($isShowDetail = false)
    {
        if (true === $isShowDetail) {
            $this->io->log('    blog                    - Show blog status');

            // Sub Help Info
            (new \Pointless\Task\Blog\InitTask)->helpInfo();
            (new \Pointless\Task\Blog\BuildTask)->helpInfo();
            (new \Pointless\Task\Blog\DeployTask)->helpInfo();
            (new \Pointless\Task\Blog\ConfigTask)->helpInfo();
        } else {
            $this->io->log('    blog                    - Blog control');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        if (true === $this->io->hasOptions('h')) {
            Misc::showBanner();

            $this->helpInfo(true);

            return false;
        }

        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    public function down()
    {
        $this->io->writeln();
        $this->io->info('Used command "blog -h" for more.');
    }

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
    }
}
