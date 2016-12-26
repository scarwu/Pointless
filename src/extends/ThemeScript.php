<?php
/**
 * Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

abstract class ThemeScript
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
