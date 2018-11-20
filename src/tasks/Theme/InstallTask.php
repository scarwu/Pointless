<?php
/**
 * Theme Install Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Theme;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class InstallTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    theme install <git repo>');
		$this->io->log('                    - Install theme');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    public function run()
    {
        $gitRepo = $this->io->getArguments(2);

        if (false === $gitRepo) {
            $this->io->error("Git repo url is not found.");

            return false;
        }

        $this->io->notice('Installing Theme');

        chdir('/tmp');

        $tmpFolder = 'pointless-theme-' . hash('md5', time());

        if (file_exists($tmpFolder)) {
            Utility::remove($tmpFolder);
        }

        system("git clone {$gitRepo} {$tmpFolder}");

        if (is_dir("/tmp/{$tmpFolder}/theme")) {
            // Utility::mkdir(BLOG_ROOT . '/themes/' . hash('md5', time()));
            Utility::copy("/tmp/{$tmpFolder}/theme", BLOG_ROOT . '/themes/' . hash('md5', time()));
        }

        Utility::remove($tmpFolder);
    }
}
