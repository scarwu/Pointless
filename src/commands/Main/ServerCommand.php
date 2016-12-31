<?php
/**
 * Pointless Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
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
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();

        if (!file_exists(APP_HOME . '/pid')) {
            IO::error('Server is not running.');

            return false;
        }
    }

    public function run()
    {
        $list = json_decode(file_get_contents(APP_HOME . '/pid'), true);

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

        IO::info("\nUsed command \"help server\" for more.");
    }
}
