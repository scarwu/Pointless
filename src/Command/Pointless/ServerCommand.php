<?php
/**
 * Pointless Server Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class ServerCommand extends Command
{
    public function help()
    {
        IO::log('    server     - Start built-in web server');
        IO::log('    --port=<port number>');
        IO::log('               - Set port number');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $route_script = (defined('BUILD_TIMESTAMP') ? HOME : ROOT) . '/Sample/Route.php';
        $port = $this->hasConfigs() ? $this->getConfigs('port') : 3000;
        $root = HOME;

        system("php -S localhost:$port -t $root $route_script < `tty` > `tty`");
    }
}
