<?php
/**
 * Pointless Deploy Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

use Utility;
use Resource;

class DeployCommand extends Command
{
    public function help()
    {
        IO::log('    deploy      - Deploy blog to Github');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        if (!Utility::commandExists('git')) {
            IO::error('System command "git" is not found.');

            return false;
        }
    }

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

        chdir(DEPLOY);

        if (!file_exists(DEPLOY . '/.git')) {
            system('git init');
            system("git remote add origin git@github.com:$account/$repo.git");
        }

        system("git pull origin $branch");

        Utility::remove(DEPLOY, DEPLOY);
        Utility::copy(TEMP, DEPLOY);

        // Create Github CNAME
        if ($github['cname']) {
            file_put_contents(DEPLOY . '/CNAME', $blog['dn']);
        }

        system('git add --all .');
        system(sprintf('git commit -m "%s"', date(DATE_RSS)));
        system("git push origin $branch");

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            Utility::chown(DEPLOY, fileowner(HOME), filegroup(HOME));
        }
    }
}
