<?php
/**
 * Pointless Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class ServerCommand extends Command
{
    public function help()
    {
        IO::log('    server      - Show server status');
        IO::log('    server start');
        IO::log('                - Start built-in web server');
        IO::log('    --port=<port number>');
        IO::log('                - Set port number');
        IO::log('    server stop - Stop built-in web server');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        if (!file_exists(HOME . '/PID')) {
            IO::error('Server is not running.');

            return false;
        }
    }

    public function run()
    {
        $list = json_decode(file_get_contents(HOME . '/PID'), true);

        foreach ($list as $pid => $info) {
            exec("ps aux | grep \"{$info['command']}\"", $output);

            if (count($output) > 1) {
                IO::notice('Server Status:');
                IO::log("Doc Root   - {$info['root']}");
                IO::log("Server URL - http://localhost:{$info['port']}");
                IO::log("Server PID - $pid");

                break;
            }
        }
    }
}
