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
        IO::writeln('    home -s    - Set another blog as default');
        IO::writeln('    home -i    - Init a new blog');
    }

    public function run() {

        // Set default blog path
        if($this->hasOptions('s')) {
            $path = $_SERVER['PWD'];

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
            define('BLOG', $_SERVER['PWD']);
            file_put_contents(HOME . '/Default', BLOG);

            initBlog();

            IO::writeln("Blog is initialized.", 'green');

            return;
        }

        if(checkDefaultBlog()) {
            
            initBlog();

            IO::writeln('Default blog path: ' . BLOG);
        }
    }
}
