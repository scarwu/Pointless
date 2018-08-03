<?php
/**
 * Data Handler for Theme
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
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
