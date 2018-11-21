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
        $this->io->notice('Starting Server');

        // Prepare Variables
        $routeScript = ('production' === APP_ENV ? HOME_ROOT : APP_ROOT) . '/sample/route.php';
        $host = 'localhost';
        $port = 3000;
        $root = HOME_ROOT;
        $command = "php -S {$host}:{$port} -t {$root} {$routeScript}";

        // Check Command
        if ($this->isCommandRunning($command)) {
            $this->io->info('Server is running.');

            return false;
        }

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
            file_put_contents(HOME_ROOT . '/.server', json_encode([
                'pid' => $pid,
                'command' => $command,
                'host' => $host,
                'port' => $port,
                'root' => $root
            ]));

            $this->io->info('Server is start.');
            $this->io->log("Server PID - {$pid}");
            $this->io->log("Server URL - http://{$host}:{$port}");
            $this->io->log("Doc Root   - {$root}");
        } else {
            $this->io->error('Server fails to start.');
        }
    }

    private function isCommandRunning($command) {
        exec('ps aux', $output);

        $output = array_filter($output, function ($text) use ($command) {
            return strpos($text, $command);
        });

        return 0 < count($output);
    }
}
