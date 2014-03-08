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
use Resource;

class DeployCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    deploy     - Deploy blog to Github');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $github = Resource::get('config')['github'];

        $account = $github['account'];
        $repo = $github['repo'];
        $branch = $github['branch'];

        if (null === $account || null === $repo || null === $branch) {
            IO::writeln('Please add Github setting in Pointless config.', 'red');

            return false;
        }

        chdir(DEPLOY);

        if (!file_exists(DEPLOY . '/.git')) {
            system('git init');
            system("git remote add origin git@github.com:$account/$repo.git");
        }

        system("git pull origin $branch");

        recursiveRemove(DEPLOY, DEPLOY);
        recursiveCopy(TEMP, DEPLOY);

        system('git add --all .');
        system(sprintf('git commit -m "%s"', date(DATE_RSS)));
        system("git push origin $branch");

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            $user = fileowner(HOME);
            $group = filegroup(HOME);
            system("chown $user.$group -R " . DEPLOY);
        }
    }
}
