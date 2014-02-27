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

class HomeCommand extends Command {
    public function __construct() {
        parent::__construct();
    }
    
    public function help() {
        IO::writeln('    home       - Show default blog path');
        IO::writeln('    home -s <path or not>');
        IO::writeln('               - Set another blog as default');
        IO::writeln('    home -i <path or not>');
        IO::writeln('               - Init a new blog');
    }

    public function run() {

        // Set default blog path
        if($this->hasOptions('s')) {
            $path = getcwd();

            if('' != ($dir = $this->getOptions()['s'])) {
                $path = "$path/$dir";
            }

            if(file_exists("$path/.pointless")) {
                file_put_contents(HOME . '/Default', $path);

                IO::writeln("Default blog is setting to path \"$path\".", 'green');
                return;
            }
            else {
                IO::writeln("Path \"$path\" is't the Pointless blog folder.", 'red');
                return;
            }
        }

        // Initialize blog
        if($this->hasOptions('i')) {
            $path = getcwd();

            if('' != ($dir = $this->getOptions()['i'])) {
                $path = "$path/$dir";

                if(file_exists($path)) {
                    IO::writeln("Path \"$path\" is exists.", 'red');
                    return;
                }

                mkdir($dir);
                chdir($dir);
            }

            define('BLOG', $path);
            file_put_contents(HOME . '/Default', BLOG);

            initBlog();

            IO::writeln("Blog is initialized.", 'green');
            IO::writeln("Default blog is setting to path \"$path\".", 'green');

            return;
        }

        if(checkDefaultBlog()) {
            initBlog();
            IO::writeln('Default blog path: ' . BLOG);
        }
    }
}
