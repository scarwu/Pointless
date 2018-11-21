<?php
/**
 * Server Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\Misc;
use Oni\CLI\Task;

class ServerTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        if ($isShowDetail) {
            $this->io->log('    server          - Show server status');

            // Sub Help Info
            (new \Pointless\Task\Server\StartTask)->helpInfo();
            (new \Pointless\Task\Server\StopTask)->helpInfo();
        } else {
            $this->io->log('    server          - Built-in web server');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        if ($this->io->hasOptions('h')) {
            Misc::showBanner();

            $this->helpInfo(true);

            return false;
        }

        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    public function down()
    {
        $this->io->writeln();
        $this->io->info('Used command "server -h" for more.');
    }

    public function run()
    {
        if (false === is_file(HOME_ROOT . '/.server')) {
            $this->io->error('Server is not running.');

            return false;
        }

        $server = json_decode(file_get_contents(HOME_ROOT . '/.server'), true);

        if ($this->isCommandRunning($server['command'])) {
            $this->io->notice('Server Status:');
            $this->io->log("PID - {$server['pid']}");
            $this->io->log("URL - {$server['url']}");
        } else {
            $this->io->error('Server is not running.');
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
