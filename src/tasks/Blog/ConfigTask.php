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

use Pointless\Library\BlogCore;
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
        $this->io->log('blog config             - Modify config');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }

        // Check Editor
        $editor = Resource::get('blog:config')['editor'];

        if (false === Utility::commandExists($editor)) {
            $this->io->error("System command \"{$editor}\" is not found.");

            return false;
        }
    }

    public function run()
    {
        // Call CLI Editor to open file
        $this->editFile(BLOG_ROOT . '/config.php');
    }
}
