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
    private $editor;

    public function help()
    {
        IO::log('    config      - Modify config');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $this->editor = Resource::get('config')['editor'];
        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"$this->editor\" is not found.");

            return false;
        }
    }

    public function run()
    {
        $filepath = BLOG . '/Config.php';
        system("$this->editor $filepath < `tty` > `tty`");
    }
}
