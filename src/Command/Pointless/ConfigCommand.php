<?php
/**
 * Pointless Config Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Utility;
use Resource;

class ConfigCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    config     - Modify config');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        // Check System Command
        $editor = Resource::get('config')['editor'];
        if (!Utility::commandExists($editor)) {
            IO::writeln("System command \"$editor\" is not found.", 'red');
            return false;
        }

        $filepath = BLOG . '/Config.php';

        system("$editor $filepath < `tty` > `tty`");
    }
}
