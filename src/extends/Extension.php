<?php
/**
 * Extension
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class Extension
{
    use ThemeTools;

    /**
     * Run Extension
     */
    abstract public function run();
}
