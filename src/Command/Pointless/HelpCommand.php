<?php
/**
 * Pointless Help Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Exception;

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
        showBanner();
        list($class_name) = $this->findCommand('Pointless', $this->getArguments());

        if ($class_name) {
            $class = new $class_name;
            $class->help();
        } else {
            $this->help();
        }
    }
}
