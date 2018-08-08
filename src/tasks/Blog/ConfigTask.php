<?php
/**
 * Blog Config Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Blog;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class ConfigTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog config - Modify config');
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
        $configPath = BLOG_ROOT . '/config.php';

        // Call CLI Editor to open file
        Misc::editFile($configPath);
    }
}
