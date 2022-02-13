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
        $this->io->log('update                  - System self-update');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->showBanner();
        $this->io->writeln();

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

    public function run()
    {
        $anwser = $this->io->ask('Are you sure to update system? [y/N]');
        $anwser = strtolower($anwser);

        if ('y' === $anwser) {
            $remote = "https://raw.github.com/scarwu/Pointless/master/bin/poi";
            $tempFile = '/tmp/poi-' . time();

            system("wget {$remote} -O {$tempFile}");
            chmod($tempFile, 0755);
            rename($tempFile, BIN_LOCATE);
            system(BIN_LOCATE);

            $this->io->notice('Update system finished.');
        } else {
            $this->io->warning('Update system skipped.');
        }
    }
}
