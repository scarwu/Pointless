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
use NanoCLI\Command;
use NanoCLI\IO;

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
        $version = 'v0.0.0-dev';

        if (defined('BUILD_VERSION')) {
            if (Misc::checkDefaultBlog()) {
                MIsc::initBlog();
            }

            $date = date(DATE_RSS, BUILD_TIMESTAMP);
            $version = BUILD_VERSION . " ($date)";
        }

        IO::info($version);
    }
}
