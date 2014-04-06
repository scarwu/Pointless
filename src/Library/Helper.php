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
 * Bind PHP Data to HTML Template
 *
 * @param string
 * @param string
 * @return string
 */
function bindData($_data, $_path)
{
    foreach ($_data as $_key => $_value) {
        $$_key = $_value;
    }

    ob_start();
    include $_path;
    $_result = ob_get_contents();
    ob_end_clean();

    return $_result;
}

/**
 * Write Data to File
 *
 * @param string
 * @param string
 */
function writeTo($data, $path)
{
    if (!preg_match('/\.(html|xml)$/', $path)) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = "$path/index.html";
    } else {
        $segments = explode('/', $path);
        array_pop($segments);

        $dirpath = implode($segments, '/');
        if (!file_exists($dirpath)) {
            mkdir($dirpath, 0755, true);
        }
    }

    $handle = fopen($path, 'w+');
    fwrite($handle, $data);
    fclose($handle);
}

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
