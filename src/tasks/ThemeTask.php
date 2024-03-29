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
    public function helpInfo()
    {
        $this->io->log('theme                   - Themes manage');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->showBanner();
        (new InstallTask)->helpInfo();
        (new UninstallTask)->helpInfo();
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

        $count = count(BlogCore::getThemeList());

        $this->io->log("{$count} theme(s).");
    }
}
