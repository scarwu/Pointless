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

use Pointless\Library\Core;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class ConfigTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog config             - Modify config');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Core::initBlog()) {
            return false;
        }
    }

    public function run()
    {
        $configPath = BLOG_ROOT . '/config.php';

        // Call CLI Editor to open file
        Core::editFile($configPath);
    }
}
