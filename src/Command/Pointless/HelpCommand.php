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
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    home       - Init and switch default blog');
        IO::writeln('    gen        - Generate blog');
        IO::writeln('    add        - Add new article');
        IO::writeln('    edit       - Edit article');
        IO::writeln('    delete     - Delete article');
        IO::writeln('    server     - Start built-in web server');
        IO::writeln('    config     - Modify config');
        IO::writeln('    deploy     - Deploy blog to Github');
        IO::writeln('    update     - Self-update');
        IO::writeln('    version    - Show version');
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

        IO::writeln($pointless, 'green');
        if ($this->hasArguments()) {
            $command = $this->getArguments(0);

            try {
                $class_name = 'Pointless\\' . ucfirst($command) . 'Command';
                $class = new $class_name();
                $class->help();
            } catch (Exception $e) {
                IO::writeln("    No description for $command.", 'red');
            }
        } else {
            $this->help();
        }
    }
}
