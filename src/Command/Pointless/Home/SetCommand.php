<?php
/**
 * Pointless Set Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Home;

use NanoCLI\Command;
use NanoCLI\IO;

class SetCommand extends Command
{
    public function help()
    {
        IO::log('    home set <path or not>');
        IO::log('               - Set another blog as default');
    }

    public function run()
    {
        $path = $this->getPath();

        if (!file_exists($path)) {
            IO::error("Path \"$path\" is't exists.");
            return false;
        }

        if (file_exists("$path/.pointless") && is_file("$path/.pointless")) {
            file_put_contents(HOME . '/Default', $path);

            IO::notice("Default blog is setting to path \"$path\".");
            return true;
        } else {
            IO::error("Path \"$path\" is't the Pointless blog folder.");
            return false;
        }
    }

    private function getPath()
    {
        if ($this->getArguments(0)) {
            $path = $this->getArguments(0);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . "/$path";
        }

        return $path;
    }
}
