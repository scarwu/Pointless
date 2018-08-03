<?php
/**
 * Pointless Start Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Server;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class StartCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
		IO::log('    server start');
        IO::log('                - Start built-in web server');
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
    }

    /**
     * Run
     */
    public function run()
    {
        // Startgin Server
        IO::notice('Starting Server');

        // Start Blog Server
        $this->startBlog();

        // Start Editor Server
        $this->startEditor();
    }

    /**
     * Start Blog
     */
    private function startBlog() {

        // Prepare Variables
        $pidList = [];
        $routeScript = ('production' === APP_ENV ? HOME_ROOT : APP_ROOT) . '/sample/route.php';
        $host = Resource::get('attr:config')['server']['blog']['host'];
        $port = Resource::get('attr:config')['server']['blog']['port'];
        $root = HOME_ROOT;
        $command = "php -S localhost:{$port} -t {$root} {$routeScript}";

        // Get PID
        $output = [];

        exec("{$command} > /dev/null 2>&1 & echo $!", $output);

        $pid = $output[0];

        // Wait Process Start
        sleep(2);

        // Dubble Check PID
        $output = [];

        exec("ps {$pid}", $output);

        if (count($output) > 1) {
            $pidList[$pid] = [
                'command' => $command,
                'root' => $root,
                'port' => $port
            ];

            file_put_contents(HOME_ROOT . '/pid', json_encode($pidList));

            IO::info('Server is start.');
            IO::log("Doc Root   - {$root}");
            IO::log("Server URL - http://localhost:{$port}");
            IO::log("Server PID - {$pid}");
        } else {
            IO::error('Server fails to start.');
        }
    }

    /**
     * Start Editor
     */
    private function startEditor() {

        // Prepare Variables
        $pidList = [];
        $routeScript = ('production' === APP_ENV ? HOME_ROOT : APP_ROOT) . '/sample/route.php';
        $host = Resource::get('attr:config')['server']['editor']['host'];
        $port = Resource::get('attr:config')['server']['editor']['port'];
        $root = HOME_ROOT;
        $command = "php -S localhost:{$port} -t {$root} {$routeScript}";

        // Get PID
        $output = [];

        exec("{$command} > /dev/null 2>&1 & echo $!", $output);

        $pid = $output[0];

        // Wait Process Start
        sleep(2);

        // Dubble Check PID
        $output = [];

        exec("ps {$pid}", $output);

        if (count($output) > 1) {
            $pidList[$pid] = [
                'command' => $command,
                'root' => $root,
                'port' => $port
            ];

            file_put_contents(HOME_ROOT . '/pid', json_encode($pidList));

            IO::info('Server is start.');
            IO::log("Doc Root   - {$root}");
            IO::log("Server URL - http://localhost:{$port}");
            IO::log("Server PID - {$pid}");
        } else {
            IO::error('Server fails to start.');
        }
    }
}
