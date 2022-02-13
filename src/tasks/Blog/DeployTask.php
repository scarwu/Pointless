<?php
/**
 * Blog Deploy Task
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

class DeployTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog deploy             - Deploy blog to Github Pages');
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
        $config = Resource::get('config:blog');
        $target = $config['deploy']['target'];

        switch ($target) {
        case 'github':
            $setting = $config['deploy']['setting']['github'];

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

            // Create Deploy Folder & Fix Permission
            Utility::mkdir(BLOG_DEPLOY);
            Utility::fixPermission(BLOG_DEPLOY);

            chdir(BLOG_DEPLOY);

            if (false === file_exists(BLOG_DEPLOY . '/.git')) {
                system('git init');
                system("git remote add origin git@github.com:{$account}/{$repo}.git");
            }

            system("git pull origin {$branch}");

            Utility::remove(BLOG_DEPLOY, true, ['.git']);
            Utility::copy(BLOG_BUILD, BLOG_DEPLOY);

            // Create Github CNAME
            if (true === $github['enableCname']) {
                file_put_contents(BLOG_DEPLOY . '/CNAME', $config['domainName']);
            }

            system('git add --all .');
            system(sprintf('git commit -m "%s"', date(DATE_RSS)));
            system("git push origin {$branch}");

            break;
        default:
            $this->io->error('Deploy target not found.');

            return false;
        }
    }
}
