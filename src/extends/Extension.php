<?php
/**
 * Extension
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

use Pointless\Extend\RenderTools;

abstract class Extension
{
    use RenderTools;

    /**
     * Run Extension
     */
    abstract public function run();
}
