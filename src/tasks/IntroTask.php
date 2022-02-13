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

use Pointless\Task\BlogTask;
use Pointless\Task\PostTask;
use Pointless\Task\ThemeTask;
use Pointless\Task\ServerTask;
use Pointless\Task\UpdateTask;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class IntroTask extends Task
{
    public function run($params = [])
    {
        $this->showBanner();

        if (0 !== count($params)) {
            $this->io->error("Can't find the command \"{$params[0]}\".");

            return false;
        }

        // Sub Help Info
        (new BlogTask)->helpInfo();
        (new PostTask)->helpInfo();
        (new ThemeTask)->helpInfo();
        (new ServerTask)->helpInfo();
        (new UpdateTask)->helpInfo();
        $this->io->writeln();

        // Show Version
        $constant = Resource::get('system:constant');

        $date = date(DATE_RSS, $constant['build']['timestamp']);
        $text = ('development' === APP_ENV)
            ? "{$constant['build']['version']} (Development)"
            : "{$constant['build']['version']} ({$date})";

        $this->io->info($text);
    }
}
