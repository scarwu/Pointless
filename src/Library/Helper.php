<?php
/**
 * Template Helper
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

/**
 * Create Link To
 *
 * @param string
 * @param string
 * @return string
 */
function linkTo($link, $name)
{
    return '<a href="' . linkEncode($link) . '">' . $name . '</a>';
}

/**
 * Link Encode
 *
 * @param string
 * @return string
 */
function linkEncode($link)
{
    $segments = explode('/', $link);
    $segments = array_map('rawurlencode', $segments);
    $link = implode('/', $segments);

    return $link;
}
