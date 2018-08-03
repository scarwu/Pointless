<?php
/**
 * Pointless Command
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Command;

use Pointless\Library\Misc;
use Oni\CLI\Command;
use Oni\CLI\IO;

class MainCommand extends Command
{
    public function __construct()
    {
        self::$_namespace = __NAMESPACE__;
    }

    /**
     * Help
     */
    public function help()
    {
        IO::log('    home        - Initialize and set default blog');
        IO::log('    build       - Build blog');
        IO::log('    post        - Add / Edit / Delete post');
        IO::log('    server      - Start built-in web server');
        IO::log('    config      - Modify config');
        IO::log('    deploy      - Deploy blog');
        IO::log('    update      - Self-update');
        IO::log('    version     - Show version');
        IO::log('    help        - Help');
    }

    /**
     * Run
     */
    public function run()
    {
        Misc::showBanner();

        if ($this->hasArguments()) {
            $command = $this->getArguments()[0];

            IO::error("    Can't find the command \"{$command}\".");
        } else {
            $this->help();
        }
    }
}
