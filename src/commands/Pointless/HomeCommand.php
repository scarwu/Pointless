<?php
/**
 * Pointless Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Resource;

class HomeCommand extends Command
{
    public function help()
    {
        IO::log('    home        - Show default blog path');
        IO::log('    home set <path or not>');
        IO::log('                - Set another blog as default');
        IO::log('    home init <path or not>');
        IO::log('                - Init a new blog');
    }

    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();
    }

    public function run()
    {
        $config = Resource::get('config');

        IO::notice('Home Path:');
        IO::log(BLOG . "\n");

        IO::notice('Blog Information:');
        IO::log("Name     - {$config['blog']['name']}");
        IO::log("Theme    - {$config['theme']}");
        IO::log("Timezone - {$config['timezone']}");
        IO::log("Editor   - {$config['editor']}");

        IO::info("\nUsed command \"help home\" for more.");
    }
}
