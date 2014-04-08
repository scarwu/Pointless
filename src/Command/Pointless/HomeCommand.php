<?php
/**
 * Pointless Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class HomeCommand extends Command
{
    public function help()
    {
        IO::log('    home       - Show default blog path');
        IO::log('    home set <path or not>');
        IO::log('               - Set another blog as default');
        IO::log('    home init <path or not>');
        IO::log('               - Init a new blog');
    }

    public function run()
    {
        if (checkDefaultBlog()) {
            initBlog();
            IO::info('Default blog path: ' . BLOG);
        }
    }
}
