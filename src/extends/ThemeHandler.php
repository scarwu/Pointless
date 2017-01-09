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
     * @var string $type
     */
    protected $type = null;

    /**
     * @var mixed $list
     */
    protected $list = [];

    final public function getType()
    {
        return $this->type;
    }

    /**
     * Render Block
     */
    abstract public function renderBlock($blockName);

    /**
     * Render Page
     */
    abstract public function renderPage();
}
