<?php
/**
 * Update Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task;

use Pointless\Library\Utility;
use Pointless\Extend\Task;

class UpdateTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo($isShowDetail = false)
    {
        if (true === $isShowDetail) {
            $this->io->log('    update                  - Update poi command by ota');
        } else {
            $this->io->log('    update                  - System self-update');
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

        if ('development' === APP_ENV) {
            $this->io->error('Development mode not allow update.');

            return false;
        }

        if (false === Utility::commandExists('wget')) {
            $this->io->error('System command "wget" is not found.');

            return false;
        }

        if (false === is_writable(BIN_LOCATE)) {
            $this->io->error('Permission denied: ' . BIN_LOCATE);

            return false;
        }
    }

    public function down()
    {
        $this->io->writeln();
        $this->io->info('Used command "update -h" for more.');
    }

    public function run()
    {
        $anwser = $this->io->ask('Are you sure to update system? [y/N]');
        $anwser = strtolower($anwser);

        if ('y' === $anwser) {
            $remote = "https://raw.github.com/scarwu/Pointless/master/bin/poi";

            system("wget {$remote} -O /tmp/poi");
            chmod('/tmp/poi', 0755);
            rename('/tmp/poi', BIN_LOCATE . '/poi');
            system(BIN_LOCATE . '/poi');

            // Reset Timestamp
            file_put_contents(HOME_ROOT . '/timestamp', 0);

            $this->io->notice('Update system finished.');
        } else {
            $this->io->warning('Update system skipped.');
        }
    }
}
