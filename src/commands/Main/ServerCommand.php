<?php
/**
 * Pointless Server Command
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Oni\CLI\Command;
use Oni\CLI\IO;

class ServerCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    server      - Show server status');
        IO::log('    server start');
        IO::log('                - Start built-in web server');
        IO::log('    --port=<port number>');
        IO::log('                - Set port number');
        IO::log('    server stop - Stop built-in web server');
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (!Misc::initBlog()) {
            return false;
        }

        if (!file_exists(HOME_ROOT . '/pid')) {
            IO::error('Server is not running.');

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $list = json_decode(file_get_contents(HOME_ROOT . '/pid'), true);

        foreach ($list as $pid => $info) {
            exec("ps aux | grep \"{$info['command']}\"", $output);

            if (count($output) > 1) {
                IO::notice('Server Status:');
                IO::log("Doc Root   - {$info['root']}");
                IO::log("Server URL - http://localhost:{$info['port']}");
                IO::log("Server PID - {$pid}");

                break;
            }
        }

        IO::writeln();
        IO::info('Used command "help server" for more.');
    }
}
