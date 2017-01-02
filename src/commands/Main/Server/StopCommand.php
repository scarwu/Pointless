<?php
/**
 * Pointless Stop Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Server;

use Pointless\Library\Misc;
use NanoCLI\Command;
use NanoCLI\IO;

class StopCommand extends Command
{

    /**
     * Help
     */
    public function help()
    {
		IO::log('    server stop - Stop built-in web server');
    }

    /**
     * Up
     */
    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();

        if (!file_exists(HOME_ROOT . '/pid')) {
            IO::error('Server is not running.');

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        IO::notice('Stopping Server');

        $list = json_decode(file_get_contents(HOME_ROOT . '/pid'), true);

        foreach ($list as $pid => $info) {
            exec("ps aux | grep \"{$info['command']}\"", $output);

            if (count($output) > 1) {
                system("kill -9 {$pid}");

                IO::info('Server is stop.');
            }
        }

        unlink(HOME_ROOT . '/pid');
    }
}
