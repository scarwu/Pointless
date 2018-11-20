<?php
/**
 * Intro Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\Misc;
use Oni\CLI\Task;

class IntroTask extends Task
{
    public function run($params = [])
    {
        Misc::showBanner();

        if (0 === count($params)) {

            // Sub Help Info
            (new \Pointless\Task\BlogTask)->helpInfo();
            (new \Pointless\Task\PostTask)->helpInfo();
            (new \Pointless\Task\ThemeTask)->helpInfo();
            (new \Pointless\Task\ServerTask)->helpInfo();
            (new \Pointless\Task\UpdateTask)->helpInfo();

            // Show Version
            $date = date(DATE_RSS, BUILD_TIMESTAMP);
            $version = ('development' === APP_ENV)
                ? BUILD_VERSION . " (Development)"
                : BUILD_VERSION . " ({$date})";

            $this->io->writeln();
            $this->io->info("    {$version}");
        } else {
            $this->io->error("    Can't find the command \"{$params[0]}\".");
        }
    }
}
