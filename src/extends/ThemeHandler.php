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

use Pointless\Extend\RenderTools;

abstract class ThemeHandler
{
    use RenderTools;

    /**
     * @var array
     */
    protected $list;

    public function __construct()
    {
        $this->list = [];
    }

    /**
     * Render Block
     */
    abstract public function renderBlock($block_name);

    /**
     * Render Page
     */
    abstract public function renderPage();
}
