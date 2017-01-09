<?php
/**
 * Pointless Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class HomeCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    home        - Show default blog path');
        IO::log('    home set <path or not>');
        IO::log('                - Set another blog as default');
        IO::log('    home init <path or not>');
        IO::log('                - Init a new blog');
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (!Misc::initBlog()) {
            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $config = Resource::get('attr:config');

        IO::notice('Home Path:');
        IO::log(BLOG_ROOT);

        IO::writeln();
        IO::notice('Blog Information:');
        IO::log("Name     - {$config['blog']['name']}");
        IO::log("Theme    - {$config['theme']}");
        IO::log("Timezone - {$config['timezone']}");
        IO::log("Editor   - {$config['editor']}");

        IO::writeln();
        IO::info('Used command "help home" for more.');
    }
}
