<?php
/**
 * Pointless Help Command
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Exception;
use Pointless\Library\Misc;
use Oni\CLI\Command;
use Oni\CLI\IO;

class HelpCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    help        - Help');
        IO::log('    help <command>');
        IO::log('                - Show command help');
    }

    /**
     * Run
     */
    public function run()
    {
        Misc::showBanner();

        if ($this->hasArguments()) {
            list($className) = $this->findCommand('Pointless\Command\MainCommand', $this->getArguments());

            if ($className) {
                $class = new $className;
                $class->help();
            } else {
                $command = $this->getArguments()[0];

                IO::error("    No description for command \"{$command}\".");
            }
        } else {
            $this->help();
        }
    }
}
