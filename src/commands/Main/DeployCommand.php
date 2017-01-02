<?php
/**
 * Pointless Deploy Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class DeployCommand extends Command
{

    /**
     * Help
     */
    public function help()
    {
        IO::log('    deploy      - Deploy blog to Github');
    }

    /**
     * Up
     */
    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();

        // Check Git
        if (!Utility::commandExists('git')) {
            IO::error('System command "git" is not found.');

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $blog = Resource::get('config')['blog'];
        $github = Resource::get('config')['deploy']['github'];

        $account = $github['account'];
        $repo = $github['repo'];
        $branch = $github['branch'];

        if (null === $account || null === $repo || null === $branch) {
            IO::error('Please add Github setting in Pointless config.');

            return false;
        }

        chdir(BLOG_DEPLOY);

        if (!file_exists(BLOG_DEPLOY . '/.git')) {
            system('git init');
            system("git remote add origin git@github.com:$account/$repo.git");
        }

        system("git pull origin $branch");

        Utility::remove(BLOG_DEPLOY, BLOG_DEPLOY);
        Utility::copy(BLOG_TEMP, BLOG_DEPLOY);

        // Create Github CNAME
        if ($github['cname']) {
            file_put_contents(BLOG_DEPLOY . '/CNAME', $blog['dn']);
        }

        system('git add --all .');
        system(sprintf('git commit -m "%s"', date(DATE_RSS)));
        system("git push origin $branch");

        // Change Owner
        if (IS_SUPER_USER) {
            Utility::chown(BLOG_DEPLOY, fileowner(HOME_ROOT), filegroup(HOME_ROOT));
        }
    }
}
