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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class InstallTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo(): void
    {
        $this->io->log('theme install <gitUrl>  - Install theme');
        $this->io->log('        --branch=<?>    - Set branch (default: master)');
    }

    /**
     * Lifecycle Funtions
     */
    #[\Override]
    public function up(): mixed
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    #[\Override]
    public function run(array $params = []): void
    {
        // [ 'theme', 'install', '<gitRepo>' ]
        $gitRepo = $this->io->getArguments(2);

        if (!isset($gitRepo)) {
            $this->io->error("Git repo url is not found.");

            return;
        }

        if (true === $this->io->hasConfigs('branch')) {
            $branch = $this->io->getConfigs('branch');
        } else {
            $branch = 'master';
        }

        $this->io->notice('Installing Theme');

        $tmpFolder = '/tmp/pointless-theme-' . hash('md5', $gitRepo . time());

        if (file_exists($tmpFolder)) {
            Utility::remove($tmpFolder);
        }

        // Clone Git Repo to Temp Folder
        system("git clone --branch {$branch} {$gitRepo} {$tmpFolder}");

        // Check Theme Information
        if (is_dir("{$tmpFolder}/dist")
            && is_file("{$tmpFolder}/dist/constant.php")
        ) {

            // Include Theme Constant
            include "{$tmpFolder}/dist/constant.php";

            // Copy Theme to Current Folder
            Utility::copy("{$tmpFolder}/dist", BLOG_ROOT . "/themes/{$constant['name']}");
        }

        // Remove Temp Folder
        Utility::remove($tmpFolder);
    }
}
