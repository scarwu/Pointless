<?php
/**
 * Template Helper
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

class Helper {

    /**
     * Create Link To
     *
     * @param string
     * @param string
     *
     * @return string
     */
    public static function linkTo($link, $name)
    {
        $link = self::linkEncode($link);

        return "<a href=\"{$link}\">{$name}</a>";
    }

    /**
     * Link Encode
     *
     * @param string
     *
     * @return string
     */
    public static function linkEncode($link)
    {
        $segments = explode('/', $link);
        $segments = array_map('rawurlencode', $segments);
        $link = implode('/', $segments);

        return $link;
    }
}
