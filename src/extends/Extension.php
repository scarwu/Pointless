<?php
/**
 * Extension
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

abstract class Extension
{
    use ThemeTools;

    /**
     * Run Extension
     */
    abstract public function run();
}
