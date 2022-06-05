<?php
/**
 * Theme Uninstall Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Theme;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class UninstallTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('theme uninstall         - Uninstall theme');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        // Select Theme Data
        $themeData = $this->selectThemeData();

        if (false === is_array($themeData)) {
            $this->io->error('No theme(s).');

            return false;
        }

        // Get Info
        $title = $themeData['title'];
        $path = $themeData['path'];

        $anwser = $this->io->ask("Are you sure uninstall theme \"{$title}\"? [y/N]", null, 'red');
        $anwser = strtolower($anwser);

        $this->io->writeln();

        if ('y' === $anwser) {
            Utility::remove($path);

            $this->io->notice("Successfully uninstalled theme \"{$title}\".");
        }
    }
}
