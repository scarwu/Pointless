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

        $envs = [
            [ 'key' => 'APP_ROOT',  'value' => APP_ROOT ],
            [ 'key' => 'BLOG_ROOT', 'value' => BLOG_ROOT ],
            [ 'key' => 'VIEWER_ROOT', 'value' => APP_ROOT . '/viewer' ]
        ];

        if ($this->io->hasConfigs('host')
            && preg_match('/^\w+(?:(?:\.\w+)+)?$/', $this->io->getConfigs('host'))) {

            $host = $this->io->getConfigs('host');
        }

        if ($this->io->hasConfigs('port')
            && preg_match('/^\d+$/', $this->io->getConfigs('port'))) {

            $port = (int) $this->io->getConfigs('port');
        }

        if ($this->io->hasConfigs('theme')
            && is_dir($this->io->getConfigs('theme'))) {

            $envs[] = [
                'key' => 'BLOG_THEME',
                'value' => $this->io->getConfigs('theme')
            ];
        }

        $command = "php -d variables_order=EGPCS -S {$host}:{$port} {$routeScript}";

        // Check Command
        if ($this->isCommandRunning($command)) {
            $this->io->info('Server is running.');

            return false;
        }

        // Get PID
        $output = [];

        exec(implode(array_map(function ($env) {
            return "{$env['key']}={$env['value']}";
        }, $envs), ' ') . " {$command} > /dev/null 2>&1 & echo $!", $output);

        $pid = $output[0];

        // Wait Process Start
        sleep(2);

        // Dubble Check PID
        $output = [];

        exec("ps {$pid}", $output);

        if (count($output) > 1) {
            $server = [
                'command' => $command,
                'pid' => $pid,
                'url' => "http://{$host}:{$port}"
            ];

            $this->io->info('Server is start.');
            $this->io->writeln();
            $this->io->notice('Server Status:');
            $this->io->log("PID - {$server['pid']}");
            $this->io->log("URL - {$server['url']}");

            file_put_contents(HOME_ROOT . '/.server', json_encode($server));
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
