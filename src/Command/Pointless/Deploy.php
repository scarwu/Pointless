<?php
/**
 * Pointless Deploy Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Deploy extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		if(NULL == GITHUB_ACCOUNT || NULL == GITHUB_REPO || NULL == GITHUB_BRANCH) {
			IO::writeln('Please add Github setting in Pointless config.', 'red');
			return;
		}

		chdir(DEPLOY_FOLDER);

		if(!file_exists(DEPLOY_FOLDER . '.git')) {
			system('git init');
			system('git remote add origin git@github.com:' . GITHUB_ACCOUNT . '/' . GITHUB_REPO. '.git');
		}

		system('git pull origin ' . GITHUB_BRANCH);

		recursiveRemove(DEPLOY_FOLDER);
		recursiveCopy(PUBLIC_FOLDER, DEPLOY_FOLDER);

		system('git add . && git add -u');
		system(sprintf('git commit -m "%s"', date(DATE_RSS)));
		system('git push origin '. GITHUB_BRANCH);
	}
}
