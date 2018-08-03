<?php
/**
 * Pointless Version Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Oni\CLI\Command;
use Oni\CLI\IO;

class VersionCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    version     - Show version');
    }

    /**
     * Run
     */
    public function run()
    {
        $date = date(DATE_RSS, BUILD_TIMESTAMP);
        $version = BUILD_VERSION . " ($date)";

        if ('development' === APP_ENV) {
            $version = "(Development) {$version}";
        }

        IO::info($version);
    }
}
