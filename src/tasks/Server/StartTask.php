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
use Pointless\Library\Utility;
use Oni\CLI\Task;

class StartTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    server start            - Start server');
        $this->io->log('            --host=<?>      - Set host (default: localhost)');
        $this->io->log('            --port=<?>      - Set port (default: 3000)');
        $this->io->log('            --theme=<?>     - Set specify theme');
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

        // Set Route Script Path
        if ('production' === APP_ENV) {
            Utility::remove(HOME_ROOT . '/route.php');
            Utility::copy(APP_ROOT . '/route.php', HOME_ROOT . '/route.php');

            $routeScript = HOME_ROOT . '/route.php';
        } else {
            $routeScript = APP_ROOT . '/route.php';
        }

        // Prepare Variables
        $host = 'localhost';
        $port = 3000;
        $root = HOME_ROOT;

        $envs = [
            [ 'key' => 'APP_ENV',   'value' => APP_ENV ],
            [ 'key' => 'APP_ROOT',  'value' => APP_ROOT ],
            [ 'key' => 'BLOG_ROOT', 'value' => BLOG_ROOT ],
            [ 'key' => 'PHAR_FILE', 'value' => isset($_SERVER['_']) ? $_SERVER['_'] : null ]
        ];

        if ($this->io->hasConfigs('host')
            && preg_match('/^\w+(?:(?:\.\w+)+)?$/', $this->io->getConfigs('host'))
        ) {
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

        $command = "php -S {$host}:{$port} {$routeScript}";

        // Check Command
        if (true === Utility::isCommandRunning($command)) {
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

        if (1 < count($output)) {
            $url = "http://{$host}:{$port}";

            $this->io->info('Server is start.');
            $this->io->writeln();
            $this->io->notice('Server Status:');
            $this->io->log("PID - {$pid}");
            $this->io->log("URL - {$url}");

            // Load & Save Config
            $config = Utility::loadJsonFile(HOME_ROOT . '/config.json');

            if (false === is_array($config)) {
                $config = [];
            }

            $config['server'] = [
                'command' => $command,
                'pid' => $pid,
                'url' => $url
            ];

            Utility::saveJsonFile(HOME_ROOT . '/config.json', $config);
        } else {
            $this->io->error('Server fails to start.');
        }
    }
}
