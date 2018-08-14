<?php
/**
 * Start Server Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Server;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class StartTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
		$this->io->log('    server start    - Start server');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    public function run()
    {
        // Startgin Server
        $this->io->notice('Starting Server');

        // Start Blog Server
        $this->startBlog();

        // Start Editor Server
        // $this->startEditor();
    }

    /**
     * Start Blog
     */
    private function startBlog()
    {
        // Prepare Variables
        $pid_list = [];
        $route_script = ('production' === APP_ENV ? HOME_ROOT : APP_ROOT) . '/sample/route.php';
        $host = Resource::get('system:config')['server']['blog']['host'];
        $port = Resource::get('system:config')['server']['blog']['port'];
        $root = HOME_ROOT;
        $command = "php -S localhost:{$port} -t {$root} {$route_script}";

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
            $pid_list[$pid] = [
                'command' => $command,
                'root' => $root,
                'port' => $port
            ];

            file_put_contents(HOME_ROOT . '/pid', json_encode($pid_list));

            $this->io->info('Server is start.');
            $this->io->log("Doc Root   - {$root}");
            $this->io->log("Server URL - http://localhost:{$port}");
            $this->io->log("Server PID - {$pid}");
        } else {
            $this->io->error('Server fails to start.');
        }
    }

    /**
     * Start Editor
     */
    // private function startEditor()
    // {
    //     // Prepare Variables
    //     $pid_list = [];
    //     $route_script = ('production' === APP_ENV ? HOME_ROOT : APP_ROOT) . '/sample/route.php';
    //     $host = Resource::get('system:config')['server']['editor']['host'];
    //     $port = Resource::get('system:config')['server']['editor']['port'];
    //     $root = HOME_ROOT;
    //     $command = "php -S localhost:{$port} -t {$root} {$route_script}";

    //     // Get PID
    //     $output = [];

    //     exec("{$command} > /dev/null 2>&1 & echo $!", $output);

    //     $pid = $output[0];

    //     // Wait Process Start
    //     sleep(2);

    //     // Dubble Check PID
    //     $output = [];

    //     exec("ps {$pid}", $output);

    //     if (count($output) > 1) {
    //         $pid_list[$pid] = [
    //             'command' => $command,
    //             'root' => $root,
    //             'port' => $port
    //         ];

    //         file_put_contents(HOME_ROOT . '/pid', json_encode($pid_list));

    //         $this->io->info('Server is start.');
    //         $this->io->log("Doc Root   - {$root}");
    //         $this->io->log("Server URL - http://localhost:{$port}");
    //         $this->io->log("Server PID - {$pid}");
    //     } else {
    //         $this->io->error('Server fails to start.');
    //     }
    // }
}
