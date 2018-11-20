<?php
/**
 * Theme Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\Misc;
use Oni\CLI\Task;

class ThemeTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        if ($isShowDetail) {
            $this->io->log('    theme           - Show theme status');

            // Sub Help Info
            (new \Pointless\Task\Theme\InstallTask)->helpInfo();
            (new \Pointless\Task\Theme\UninstallTask)->helpInfo();
        } else {
            $this->io->log('    theme           - Themes manage');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        if ($this->io->hasOptions('h')) {
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
        $this->io->info('Used command "theme -h" for more.');
    }

    public function run()
    {
        $this->io->notice('Theme Status:');

        $count = count(Misc::getThemeList());

        $this->io->log("{$count} theme(s).");
    }
}
