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
    public function helpInfo($is_show_detail = false)
    {
        if ($is_show_detail) {
            $this->io->log('    server          - Show server status');

            // Sub Help Info
            (new \Pointless\Task\Server\StartTask)->helpInfo();
            (new \Pointless\Task\Server\StopTask)->helpInfo();
        } else {
            $this->io->log('    server          - Built-in web server');
        }
    }

    /**
     * Up
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

        if (false === file_exists(HOME_ROOT . '/pid')) {
            $this->io->error('Server is not running.');

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $list = json_decode(file_get_contents(HOME_ROOT . '/pid'), true);

        foreach ($list as $pid => $info) {
            exec("ps aux | grep \"{$info['command']}\"", $output);

            if (count($output) > 1) {
                $this->io->notice('Server Status:');
                $this->io->log("Doc Root   - {$info['root']}");
                $this->io->log("Server URL - http://localhost:{$info['port']}");
                $this->io->log("Server PID - {$pid}");

                break;
            }
        }

        $this->io->writeln();
        $this->io->info('Used command "server -h" for more.');
    }
}
