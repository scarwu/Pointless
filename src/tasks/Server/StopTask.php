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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Extend\Task;

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
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        $this->io->notice('Stopping Server');

        // Load & Save Blog Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (false === is_array($blog)
            || false === isset($blog['server'])
            || false === is_array($blog['server'])
        ) {
            $this->io->error('Server is not running.');

            return false;
        }

        if (true === Utility::isCommandRunning($blog['server']['command'])) {
            system("kill -9 {$blog['server']['pid']}");

            $this->io->info('Server is stop.');
        } else {
            $this->io->error('Server is not running.');
        }

        $blog['server'] = null;

        Utility::saveJsonFile(HOME_ROOT . '/blog.json', $blog);
    }
}
