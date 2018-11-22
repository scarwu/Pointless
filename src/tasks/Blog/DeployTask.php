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

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class DeployTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog deploy         - Deploy blog to Github Pages');
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

        // Check Git
        if (false === Utility::commandExists('git')) {
            $this->io->error('System command "git" is not found.');

            return false;
        }
    }

    public function run()
    {
        $blog = Resource::get('system:config')['blog'];
        $github = Resource::get('system:config')['deploy']['github'];

        $account = $github['account'];
        $repo = $github['repo'];
        $branch = $github['branch'];

        if (null === $account || null === $repo || null === $branch) {
            $this->io->error('Please add Github setting in Pointless config.');

            return false;
        }

        chdir(BLOG_DEPLOY);

        if (false === file_exists(BLOG_DEPLOY . '/.git')) {
            system('git init');
            system("git remote add origin git@github.com:{$account}/{$repo}.git");
        }

        system("git pull origin {$branch}");

        Utility::remove(BLOG_DEPLOY, true, ['.git']);
        Utility::copy(BLOG_BUILD, BLOG_DEPLOY);

        // Create Github CNAME
        if ($github['cname']) {
            file_put_contents(BLOG_DEPLOY . '/CNAME', $blog['domainName']);
        }

        system('git add --all .');
        system(sprintf('git commit -m "%s"', date(DATE_RSS)));
        system("git push origin {$branch}");

        // Fix Permission
        Misc::fixPermission(BLOG_DEPLOY);
    }
}
