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

use Pointless\Library\BlogCore;
use Pointless\Task\Theme\InstallTask;
use Pointless\Task\Theme\UninstallTask;
use Pointless\Extend\Task;

class ThemeTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        if (true === $isShowDetail) {
            $this->io->log('    theme                   - Show theme status');

            // Sub Help Info
            (new InstallTask)->helpInfo();
            (new UninstallTask)->helpInfo();
        } else {
            $this->io->log('    theme                   - Themes manage');
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
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

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

        $count = count(BlogCore::getThemeList());

        $this->io->log("{$count} theme(s).");
    }
}
