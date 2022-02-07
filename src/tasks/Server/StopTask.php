<?php
/**
 * Stop Server Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Server;

use Pointless\Library\Misc;
use Oni\CLI\Task;

class StopTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
		$this->io->log('    server stop             - Stop server');
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
        $this->io->notice('Stopping Server');

        if (false === is_file(HOME_ROOT . '/.server')) {
            $this->io->error('Server is not running.');

            return false;
        }

        $server = json_decode(file_get_contents(HOME_ROOT . '/.server'), true);

        if ($this->isCommandRunning($server['command'])) {
            system("kill -9 {$server['pid']}");

            $this->io->info('Server is stop.');
        } else {
            $this->io->error('Server is not running.');
        }

        unlink(HOME_ROOT . '/.server');
    }

    private function isCommandRunning($command) {
        exec('ps aux', $output);

        $output = array_filter($output, function ($text) use ($command) {
            return strpos($text, $command);
        });

        return 0 < count($output);
    }
}
