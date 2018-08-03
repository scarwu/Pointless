<?php
/**
 * Pointless Config Command
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Command;
use Oni\CLI\IO;

class ConfigCommand extends Command
{
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
        // Init Blog
        if (!Misc::initBlog()) {
            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $configPath = BLOG_ROOT . '/config.php';

        // Call CLI Editor to open file
        Misc::editFile($configPath);
    }
}
