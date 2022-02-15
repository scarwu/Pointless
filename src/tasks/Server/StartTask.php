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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class StartTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('server start            - Start server');
        $this->io->log('        --host=<?>      - Set host (default: localhost)');
        $this->io->log('        --port=<?>      - Set port (default: 3000)');

        if ('development' === APP_ENV) {
            $this->io->log('        --theme=<?>     - Set specify theme');
            $this->io->log('        --editor=<?>    - Set specify editor');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

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

        if (true === $this->io->hasConfigs('host')
            && true === (bool) preg_match('/^\w+(?:(?:\.\w+)+)?$/', $this->io->getConfigs('host'))
        ) {
            $host = $this->io->getConfigs('host');
        }

        if (true === $this->io->hasConfigs('port')
            && true === (bool) preg_match('/^\d+$/', $this->io->getConfigs('port'))
        ) {
            $port = (int) $this->io->getConfigs('port');
        }

        if ('development' === APP_ENV) {
            if (true === $this->io->hasConfigs('theme')
                && true === is_dir($this->io->getConfigs('theme'))
            ) {
                $envs[] = [
                    'key' => 'BLOG_THEME',
                    'value' => $this->io->getConfigs('theme')
                ];
            }

            if (true === $this->io->hasConfigs('editor')
                && true === is_dir($this->io->getConfigs('editor'))
            ) {
                $envs[] = [
                    'key' => 'BLOG_EDITOR',
                    'value' => $this->io->getConfigs('editor')
                ];
            }
        }

        $command = "php -S {$host}:{$port} {$routeScript}";

        // Check Command
        if (true === Utility::isCommandRunning($command)) {
            $this->io->info('Server is running.');

            return true;
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

        if (1 >= count($output)) {
            $this->io->error('Server fails to start.');

            return false;
        }

        $url = "http://{$host}:{$port}";

        $this->io->info('Server is start.');
        $this->io->writeln();
        $this->io->notice('Server Status:');
        $this->io->log("PID - {$pid}");
        $this->io->log("URL - {$url}");

        // Load & Save Blog Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (false === is_array($blog)) {
            $blog = [];
        }

        $blog['server'] = [
            'command' => $command,
            'pid' => $pid,
            'url' => $url
        ];

        Utility::saveJsonFile(HOME_ROOT . '/blog.json', $blog);
    }
}
