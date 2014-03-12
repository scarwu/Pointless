<?php
/**
 * Pointless Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class HomeCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    home       - Show default blog path');
        IO::writeln('    home -s <path or not>');
        IO::writeln('               - Set another blog as default');
        IO::writeln('    home -i <path or not>');
        IO::writeln('               - Init a new blog');
    }

    public function run()
    {
        // Set default blog path
        if ($this->hasOptions('s')) {
            $path = $this->getPath();

            if (!file_exists($path)) {
                IO::writeln("Path \"$path\" is't exists.", 'red');

                return false;
            }

            if (file_exists("$path/.pointless") && is_file("$path/.pointless")) {
                file_put_contents(HOME . '/Default', $path);

                IO::writeln("Default blog is setting to path \"$path\".", 'green');

                return true;
            } else {
                IO::writeln("Path \"$path\" is't the Pointless blog folder.", 'red');

                return false;
            }
        }

        // Initialize blog
        if ($this->hasOptions('i')) {
            $path = $this->getPath();

            if (file_exists($path)) {
                IO::writeln("Path \"$path\" is exists.", 'red');

                return false;
            } else {
                if (!mkdir($path, 0755, true)) {
                    IO::writeln("Permission denied: $path", 'red');

                    return false;
                }

                chdir($path);
            }

            define('BLOG', $path);
            file_put_contents(HOME . '/Default', BLOG);

            initBlog();

            IO::writeln("Blog is initialized.", 'green');
            IO::writeln("Default blog is setting to path \"$path\".", 'green');

            return true;
        }

        if (checkDefaultBlog()) {
            initBlog();
            IO::writeln('Default blog path: ' . BLOG);
        }
    }

    private function getPath()
    {
        if ($this->hasOptions('s')) {
            $path = $this->getOptions('s');
        }

        if ($this->hasOptions('i')) {
            $path = $this->getOptions('i');
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . "/$path";
        }

        return $path;
    }
}
