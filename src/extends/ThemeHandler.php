<?php
/**
 * Data Handler for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class ThemeHandler
{
    use ThemeTools;

    /**
     * @var array
     */
    protected $list;

    public function __construct()
    {
        $this->list = [];
    }

    /**
     * Generate Data
     */
    abstract public function gen();
}
