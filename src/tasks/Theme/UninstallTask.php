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

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class UninstallTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    theme uninstall     - Uninstall theme');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    public function run()
    {
        // Load Markdown
        $themeList = Misc::getThemeList();

        if (0 === count($themeList)) {
            $this->io->error('No theme(s).');

            return false;
        }

        // Get Theme Number
        foreach ($themeList as $index => $theme) {
            $this->io->log(sprintf("[ %3d] ", $index) . $theme['title']);
        }

        $index = $this->io->ask("\nEnter Number:\n-> ", function ($answer) use ($themeList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($themeList);
        });

        // Get Info
        $path = $themeList[array_keys($themeList)[$index]]['path'];
        $title = $themeList[array_keys($themeList)[$index]]['title'];

        if ('yes' === $this->io->ask("\nAre you sure uninstall theme \"{$title}\"? (yes)\n-> ", null, 'red')) {
            Utility::remove($path);

            $this->io->writeln();
            $this->io->notice("Successfully uninstalled theme \"{$title}\".");
        }
    }
}
