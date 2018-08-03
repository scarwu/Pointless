<?php
/**
 * Archive Data Generator Script for Theme
 *
 * @package     Pointless Theme - Unique
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Unique
 */

namespace Pointless\Handler;

use Pointless\Library\Resource;
use Pointless\Extend\ThemeHandler;

class About extends ThemeHandler
{
    public function __construct() {
        $this->type = 'about';
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($blockName)
    {
        $views = Resource::get('theme:config')['views'];

        if (!isset($views[$blockName])) {
            return false;
        }

        if (!in_array('about', $views[$blockName])) {
            return false;
        }

        $block = Resource::get('block');

        if (null === $block) {
            $block = [];
        }

        if (!isset($block[$blockName])) {
            $block[$blockName] = '';
        }

        $block[$blockName] .= $this->render([
            'blog' => Resource::get('system:config')['blog']
        ], "{$blockName}/about.php");

        Resource::set('block', $block);
    }

    /**
     * Render Page
     */
    public function renderPage() {}
}
