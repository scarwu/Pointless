<?php
/**
 * Blog Backup Task
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

class BackupTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('blog backup             - Backup blog to Git');
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

        // Check Git
        if (false === Utility::commandExists('git')) {
            $this->io->error('System command "git" is not found.');

            return false;
        }
    }

    public function run()
    {
        $config = Resource::get('blog:config');
        $target = $config['backup']['target'];

        switch ($target) {
        case 'github':
            $setting = $config['backup']['setting']['github'];

            $account = $setting['account'];
            $repo = $setting['repo'];
            $branch = $setting['branch'];

            if (false === is_string($account)
                || false === is_string($repo)
                || false === is_string($branch)
            ) {
                $this->io->error('Please add Github setting in blog config.');

                return false;
            }

            // Create Backup Folder & Fix Permission
            Utility::mkdir(BLOG_BACKUP);
            Utility::fixPermission(BLOG_BACKUP);

            chdir(BLOG_BACKUP);

            if (false === file_exists(BLOG_BACKUP . '/.git')) {
                system('git init');
                system("git remote add origin git@github.com:{$account}/{$repo}.git");
            }

            system("git pull origin {$branch}");

            Utility::remove(BLOG_BACKUP, true, ['.git']);

            foreach ([
                'posts',
                'assets',
                'themes',
                'handlers',
                'extensions',
                'config.php'
            ] as $filepath) {
                Utility::copy(BLOG_ROOT . "/{$filepath}", BLOG_BACKUP . "/{$filepath}");
            }

            system('git add --all .');
            system(sprintf('git commit -m "%s"', date(DATE_RSS)));
            system("git push origin {$branch}");

            break;
        default:
            $this->io->error('Backup target not found.');

            return false;
        }
    }
}
