<?php
/**
 * Pointless Config Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class ConfigCommand extends Command
{

    /**
     * @var string
     */
    private $editor;

    /**
     * Help
     */
    public function help()
    {
        IO::log('    config      - Modify config');
    }

    /**
     * Up
     */
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

    /**
     * Run
     */
    public function run()
    {
        $filepath = BLOG . '/config.php';

        system("{$this->editor} $filepath < `tty` > `tty`");
    }
}
