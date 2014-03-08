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

class UpdateCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    update     - Self-update');
        IO::writeln('    update -d  - Use development version');
        IO::writeln('    update -e  - Use experipment version');
    }

    public function run()
    {
        if (!defined('BUILD_TIMESTAMP')) {
            IO::writeln('Development version can not be updated.', 'red');

            return false;
        }

        $branch = 'master';

        if ($this->hasOptions('d')) {
            $branch = 'develop';
        }

        if ($this->hasOptions('e')) {
            $branch = 'experipment';
        }

        $remote = "https://raw.github.com/scarwu/Pointless/$branch/bin/poi";

        if (!is_writable(BIN_LOCATE)) {
            IO::writeln('Permission denied: ' . BIN_LOCATE, 'red');

            return false;
        }

        system("wget $remote -O /tmp/poi");
        system('chmod +x /tmp/poi');

        // Reset Timestamp
        file_put_contents(HOME . '/Timestamp', 0);

        IO::writeln('Update finish.', 'green');

        system('/tmp/poi version');
        system('mv /tmp/poi ' . BIN_LOCATE);
    }
}
