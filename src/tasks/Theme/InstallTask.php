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
        $this->io->log('    theme install <git repo url>');
		$this->io->log('                        - Install theme');
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
        // [ 'theme', 'install', '<gitRepo>' ]
        $gitRepo = $this->io->getArguments(2);

        if (false === $gitRepo) {
            $this->io->error("Git repo url is not found.");

            return false;
        }

        $this->io->notice('Installing Theme');

        $tmpFolder = '/tmp/pointless-theme-' . hash('md5', $gitRepo . time());

        if (file_exists($tmpFolder)) {
            Utility::remove($tmpFolder);
        }

        // Clone Git Repo to Temp Folder
        system("git clone {$gitRepo} {$tmpFolder}");

        // Check Theme Information
        if (is_dir("{$tmpFolder}/dist")
            && is_file("{$tmpFolder}/dist/constant.php")) {

            // Include Theme Constant
            include "{$tmpFolder}/dist/constant.php";

            // Copy Theme to Current Folder
            Utility::copy("{$tmpFolder}/dist", BLOG_ROOT . "/themes/{$constant['name']}");
        }

        // Remove Temp Folder
        Utility::remove($tmpFolder);
    }
}
