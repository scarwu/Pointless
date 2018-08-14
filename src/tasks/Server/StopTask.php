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
		$this->io->log('    server stop     - Stop server');
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

        if (false === file_exists(HOME_ROOT . '/pid')) {
            $this->io->error('Server is not running.');

            return false;
        }
    }

    public function run()
    {
        $this->io->notice('Stopping Server');

        $list = json_decode(file_get_contents(HOME_ROOT . '/pid'), true);

        foreach ($list as $pid => $info) {
            exec("ps aux | grep \"{$info['command']}\"", $output);

            if (count($output) > 1) {
                system("kill -9 {$pid}");

                $this->io->info('Server is stop.');
            }
        }

        unlink(HOME_ROOT . '/pid');
    }
}
