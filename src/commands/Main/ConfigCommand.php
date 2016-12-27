<?php
/**
 * Pointless Config Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Misc,
    Utility,
    Resource,
    NanoCLI\Command,
    NanoCLI\IO;

class ConfigCommand extends Command
{
    private $editor;

    public function help()
    {
        IO::log('    config      - Modify config');
    }

    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();

        // Check Editor
        $this->editor = Resource::get('config')['editor'];

        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"{$this->editor}\" is not found.");

            return false;
        }
    }

    public function run()
    {
        $filepath = BLOG . '/config.php';

        system("{$this->editor} $filepath < `tty` > `tty`");
    }
}
