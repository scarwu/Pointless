<?php
/**
 * Template Helper
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

/**
 * Create Link
 *
 * @param string
 * @param string
 * @return string
 */
function linkTo($link, $name) {
	return '<a href="' . $link . '">' . $name . '</a>';
}