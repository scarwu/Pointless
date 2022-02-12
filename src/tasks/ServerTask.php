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

use Pointless\Library\Core;
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
        if (true === $isShowDetail) {
            $this->io->log('    server                  - Show server status');

            // Sub Help Info
            (new StartTask)->helpInfo();
            (new StopTask)->helpInfo();
        } else {
            $this->io->log('    server                  - Built-in web server');
        }
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        if (true === $this->io->hasOptions('h')) {
            $this->showBanner();
            $this->helpInfo(true);

            return false;
        }

        // Init Blog
        if (false === Core::initBlog()) {
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
        // Load & Save Config
        $config = Utility::loadJsonFile(HOME_ROOT . '/config.json');

        if (false === is_array($config)
            || false === is_array($config['server'])
        ) {
            $this->io->error('Server is not running.');

            return false;
        }

        if (true === Utility::isCommandRunning($config['server']['command'])) {
            $this->io->notice('Server Status:');
            $this->io->log("PID - {$config['server']['pid']}");
            $this->io->log("URL - {$config['server']['url']}");
        } else {
            $this->io->error('Server is not running.');
        }
    }
}
