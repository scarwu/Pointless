<?php
/**
 * Tools for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

trait ThemeTools
{
    /**
     * Render HTML
     *
     * @param string
     * @param string
     * @return string
     */
    final protected function render($_data, $_path)
    {
        foreach ($_data as $_key => $_value) {
            $$_key = $_value;
        }

        ob_start();
        include THEME . "/Template/$_path";
        $_result = ob_get_contents();
        ob_end_clean();

        return $_result;
    }

    /**
     * Save HTML
     *
     * @param string
     * @param string
     */
    final protected function save($path, $data)
    {
        $path = TEMP . "/$path";

        if (!preg_match('/\.(html|xml)$/', $path)) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $path = "$path/index.html";
        } else {
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
        }

        file_put_contents($path, $data);

        Resource::append('sitemap', $path);
    }

    /**
     * Save HTML
     *
     * @param string
     * @param string
     */
    final protected function createIndex($src, $dest)
    {
        if (file_exists(TEMP . "/$src")) {
            copy(TEMP . "/$src", TEMP . "/$dest");
            Resource::append('sitemap', dirname($dest));
        }
    }
}
