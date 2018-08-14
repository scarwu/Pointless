<?php
/**
 * Tools for Render
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

use Pointless\Library\Resource;

trait RenderTools
{
    /**
     * Render HTML
     *
     * @param string $_data
     * @param string $_path
     *
     * @return string
     */
    final protected function render($_data, $_path)
    {
        foreach ($_data as $_key => $_value) {
            $$_key = $_value;
        }

        ob_start();
        include BLOG_THEME . "/views/{$_path}";
        $_result = ob_get_contents();
        ob_end_clean();

        return $_result;
    }

    /**
     * Save HTML
     *
     * @param string $path
     * @param string $data
     */
    final protected function save($path, $data)
    {
        $realpath = BLOG_BUILD . "/{$path}";

        if (!preg_match('/\.(html|xml)$/', $realpath)) {
            if (false === file_exists($realpath)) {
                mkdir($realpath, 0755, true);
            }

            $realpath = "{$realpath}/index.html";
        } else {
            if (false === file_exists(dirname($realpath))) {
                mkdir(dirname($realpath), 0755, true);
            }
        }

        file_put_contents($realpath, $data);

        Resource::append('sitemap', $path);
    }

    /**
     * Save HTML
     *
     * @param string $src
     * @param string $dest
     */
    final protected function createIndex($src, $dest)
    {
        if (file_exists(BLOG_BUILD . "/{$src}")) {
            copy(BLOG_BUILD . "/{$src}", BLOG_BUILD . "/{$dest}");

            if ('.' !== ($path = dirname($dest))) {
                Resource::append('sitemap', $path);
            }
        }
    }
}
