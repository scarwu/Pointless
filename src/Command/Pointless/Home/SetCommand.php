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
    private $path;

    public function help()
    {
        IO::log('    home set <path or not>');
        IO::log('               - Set another blog as default');
    }

    public function up()
    {
        $this->path = $this->getPath();

        if (!file_exists($this->path)) {
            IO::error("Path \"$this->path\" is't exists.");

            return false;
        }

        if (!file_exists("$this->path/.pointless") || !is_file("$this->path/.pointless")) {
            IO::error("Path \"$this->path\" is't the Pointless blog folder.");

            return false;
        }
    }

    public function run()
    {
        file_put_contents(HOME . '/Default', $this->path);
        IO::notice("Default blog is setting to path \"$this->path\".");
    }

    private function getPath()
    {
        $path = '';

        if ($this->getArguments(0)) {
            $path = $this->getArguments(0);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/$path" : $path);
        }

        return $path;
    }
}
