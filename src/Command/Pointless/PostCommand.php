<?php
/**
 * Pointless Post Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class PostCommand extends Command
{
    public function help()
    {
        IO::log('    post add    - Add new post');
        IO::log('    post edit   - Edit post');
        IO::log('    post delete - Delete post');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();
    }

    public function run()
    {
        $this->help();
    }
}
