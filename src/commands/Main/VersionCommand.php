<?php
/**
 * Pointless Version Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc,
    NanoCLI\Command,
    NanoCLI\IO;

class VersionCommand extends Command
{
    public function help()
    {
        IO::log('    version     - Show version');
    }

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
