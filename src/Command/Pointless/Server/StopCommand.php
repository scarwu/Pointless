<?php
/**
 * Pointless Stop Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Server;

use NanoCLI\Command;
use NanoCLI\IO;

class StopCommand extends Command
{
    public function help()
    {
		IO::log('    server stop');
        IO::log('               - Stop built-in web server');
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
        IO::notice('Stoping Server');

        $list = json_decode(file_get_contents(HOME . '/PID'), true);

        foreach ($list as $pid => $command) {
            exec("ps aux | grep \"$command\"", $output);

            if (count($output) > 1) {
                system("kill -9 $pid");

                IO::info('Server is stop.');
            }
        }

        unlink(HOME . '/PID');
    }
}
