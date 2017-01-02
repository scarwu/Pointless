<?php
/**
 * Pointless Version Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

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
            if (checkDefaultBlog()) {
                initBlog();
            }

            $date = date(DATE_RSS, BUILD_TIMESTAMP);
            $version = BUILD_VERSION . " ($date)";
        }

        IO::info($version);
    }
}
