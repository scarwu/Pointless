<?php
/**
 * Pointless Help Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Exception,
    Pointless\Library\Misc,
    NanoCLI\Command,
    NanoCLI\IO;

class HelpCommand extends Command
{
    public function help()
    {
        IO::log('    help        - Help');
        IO::log('    help <command>');
        IO::log('                - Show command help');
    }

    public function run()
    {
        Misc::showBanner();

        if ($this->hasArguments()) {
            list($class_name) = $this->findCommand('Pointless', $this->getArguments());

            if ($class_name) {
                $class = new $class_name;
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
