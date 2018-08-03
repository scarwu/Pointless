<?php
/**
 * Extension
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
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
