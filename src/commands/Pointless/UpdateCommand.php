<?php
/**
 * Pointless Update Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Utility;

class UpdateCommand extends Command
{
    public function help()
    {
        IO::log('    update      - Self-update');
        IO::log('    update -d   - Use development version');
        IO::log('    update -e   - Use experipment version');
    }

    public function up()
    {
        if (!defined('BUILD_TIMESTAMP')) {
            IO::error('Development version can not be updated.');

            return false;
        }

        if (!Utility::commandExists('wget')) {
            IO::error('System command "wget" is not found.');

            return false;
        }

        if (!is_writable(BIN_LOCATE)) {
            IO::error('Permission denied: ' . BIN_LOCATE);

            return false;
        }
    }

    public function run()
    {
        $branch = 'master';

        if ($this->hasOptions('d')) {
            $branch = 'develop';
        }

        if ($this->hasOptions('e')) {
            $branch = 'experipment';
        }

        $remote = "https://raw.github.com/scarwu/Pointless/$branch/bin/poi";

        system("wget $remote -O /tmp/poi");
        chmod('/tmp/poi', 0755);

        // Reset Timestamp
        file_put_contents(HOME . '/Timestamp', 0);

        IO::notice('Update finish.');

        system('/tmp/poi version');
        rename('/tmp/poi', BIN_LOCATE . '/poi');
    }
}
