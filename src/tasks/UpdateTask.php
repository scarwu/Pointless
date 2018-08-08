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

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Oni\CLI\Task;

class UpdateTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    update      - Self-update');
    }

    /**
     * Up
     */
    public function up()
    {
        if ('development' === APP_ENV) {
            $this->io->error('Development version can not be update.');

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

    /**
     * Help
     */
    public function run()
    {
        $anwser = $this->io->ask('Are you sure to update system? [y/N]');
        $anwser = strtolower($anwser);

        if ('y' === $anwser) {
            $remote = "https://raw.github.com/scarwu/Pointless/master/bin/poi";

            system("wget {$remote} -O /tmp/poi");

            chmod('/tmp/poi', 0755);

            // Reset Timestamp
            file_put_contents(HOME_ROOT . '/timestamp', 0);

            $this->io->notice('Update system finished.');

            system('/tmp/poi version');
            rename('/tmp/poi', BIN_LOCATE . '/poi');
        } else {
            $this->io->warning('Update system skipped.');
        }
    }
}
