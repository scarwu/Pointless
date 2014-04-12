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
        IO::log('    home       - Initialize and set default blog');
        IO::log('    gen        - Generate blog');
        IO::log('    add        - Add new post');
        IO::log('    edit       - Edit post');
        IO::log('    delete     - Delete post');
        IO::log('    server     - Start built-in web server');
        IO::log('    config     - Modify config');
        IO::log('    deploy     - Deploy blog');
        IO::log('    update     - Self-update');
        IO::log('    version    - Show version');
    }

    public function run()
    {
        $pointless = <<<EOF
                                           __
      ______  ______  __  ______  ______  / /\______  _____  _____
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/
  \_\/

EOF;

        IO::notice($pointless);

        $prefix = 'Pointless';

        if ($this->hasArguments()) {
            $arguments = $this->getArguments();
            $command = [];

            while ($arguments) {
                if (!preg_match('/^[a-zA-Z]+/', $arguments[0])) {
                    break;
                }

                $command[] = $arguments[0];
                $class_name = ucfirst($arguments[0]);
                $class_name = "$prefix\\$class_name";

                try {
                    if (class_exists("{$class_name}Command")) {
                        $prefix = $class_name;
                        array_shift($arguments);
                    }
                } catch (Exception $e) {
                    $command = implode($command, ' ');
                    IO::error("    No description for \"$command\".");

                    return false;
                }
            }
        }

        if (count(explode("\\", $prefix)) > 1) {
            $class_name = $prefix . 'Command';
            $class = new $class_name;
            $class->help();
        } else {
            $this->help();
        }
    }
}
