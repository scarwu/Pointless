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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Task\Server\StartTask;
use Pointless\Task\Server\StopTask;
use Pointless\Extend\Task;

class ServerTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        $this->io->log('server                  - Built-in web server');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->showBanner();
        (new StartTask)->helpInfo();
        (new StopTask)->helpInfo();
        $this->io->writeln();

        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        // Load & Save Blog Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (false === is_array($blog)
            || false === isset($blog['server'])
            || false === is_array($blog['server'])
        ) {
            $this->io->error('Server is not running.');

            return false;
        }

        if (false === Utility::isCommandRunning($blog['server']['command'])) {
            $this->io->error('Server is not running.');

            $blog['server'] = null;

            Utility::saveJsonFile(HOME_ROOT . '/blog.json', $blog);

            return false;
        }

        $this->io->notice('Status:');
        $this->io->log("PID - {$blog['server']['pid']}");
        $this->io->log("URL - {$blog['server']['url']}");
    }
}
