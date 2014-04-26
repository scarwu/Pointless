<?php
/**
 * Pointless Start Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Server;

use NanoCLI\Command;
use NanoCLI\IO;

class StartCommand extends Command
{
    public function help()
    {
		IO::log('    server start');
        IO::log('               - Start built-in web server');
        IO::log('    --port=<port number>');
        IO::log('               - Set port number');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();
    }

    public function run()
    {
        $pid_list = [];
        $route_script = (defined('BUILD_TIMESTAMP') ? HOME : ROOT) . '/Sample/Route.php';
        $port = $this->hasConfigs() ? $this->getConfigs('port') : 3000;
        $root = HOME;
        $command = "php -S localhost:$port -t $root $route_script";

        IO::info('Starting Server');
        exec("$command > /dev/null 2>&1 & echo $!", $output);
        sleep(3);

        $pid = $output[0];

        exec("ps $pid", $output);

        if (count($output) >= 0) {
            $pid_list[$pid] = $command;
        }

        file_put_contents(HOME . '/PID', json_encode($pid_list));
    }
}
