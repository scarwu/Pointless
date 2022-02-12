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

use Pointless\Library\Core;
use Pointless\Task\BlogTask;
use Pointless\Task\PostTask;
use Pointless\Task\ThemeTask;
use Pointless\Task\ServerTask;
use Pointless\Task\UpdateTask;
use Pointless\Extend\Task;

class IntroTask extends Task
{
    public function run($params = [])
    {
        $this->showBanner();

        if (0 === count($params)) {

            // Sub Help Info
            (new BlogTask)->helpInfo();
            (new PostTask)->helpInfo();
            (new ThemeTask)->helpInfo();
            (new ServerTask)->helpInfo();
            (new UpdateTask)->helpInfo();

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
