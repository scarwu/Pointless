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
    public function helpInfo(bool $isShowDetail = false): void
    {
        $this->io->log('server                  - Built-in web server');
    }

    /**
     * Lifecycle Funtions
     */
    #[\Override]
    public function up(): mixed
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

    #[\Override]
    public function run(array $params = []): void
    {
        // Load & Save Blog Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (!is_array($blog)
            || !isset($blog['server'])
            || !is_array($blog['server'])
        ) {
            $this->io->error('Server is not running.');

            return;
        }

        if (false === Utility::isCommandRunning($blog['server']['command'])) {
            $this->io->error('Server is not running.');

            $blog['server'] = null;

            Utility::saveJsonFile(HOME_ROOT . '/blog.json', $blog);

            return;
        }

        $this->io->notice('Status:');
        $this->io->log("PID - {$blog['server']['pid']}");
        $this->io->log("URL - {$blog['server']['url']}");
    }
}
