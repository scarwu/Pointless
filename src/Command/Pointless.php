<?php
/**
 * Pointless Command
 *
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\Command;

class Pointless extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $help = new Pointless\HelpCommand();
        $help->run();
    }
}
