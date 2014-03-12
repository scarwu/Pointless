<?php
/**
 * Css and Js Compressor
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class Compress
{
    /**
     * @var array
     */
    private $css;

    /**
     * @var array
     */
    private $js;

    public function __construct()
    {
        $this->css = [];
        $this->js = [];
    }

    /**
     * Css IO Setting
     *
     * @param string
     * @param string
     */
    public function css()
    {
        foreach ((array) Resource::get('theme')['css'] as $filename) {
            $filename = preg_replace('/.css$/', '', $filename);

            if (!file_exists(THEME . "/Css/$filename.css")) {
                IO::writeln("CSS file \"$filename.css\" not found.", 'red');
                continue;
            }

            $this->css[] = THEME . "/Css/$filename.css";
        }

        if (!file_exists(TEMP . '/theme')) {
            mkdir(TEMP . '/theme', 0755, true);
        }

        $handle = fopen(TEMP . '/theme/main.css', 'w+');
        foreach ((array) $this->css as $filepath) {
            $css = file_get_contents($filepath);
            $css = $this->cssCompressor($css);
            fwrite($handle, $css);
        }
        fclose($handle);
    }

    /**
     * Css Compressor
     *
     * @param string
     * @return string
     */
    private function cssCompressor($css)
    {
        $css = preg_replace('/(\f|\n|\r|\t|\v)/', '', $css);
        $css = preg_replace('/\/\*.+?\*\//', '', $css);
        $css = preg_replace('/[ ]+/', ' ', $css);
        $css = str_replace([
            ' ,', ', ', ': ', ' :',
            ' {', '{ ', ' }', '} ',
            ' ;', '; '
        ], [
            ',', ',', ':', ':',
            '{', '{', '}', '}',
            ';', ';'
        ], $css);

        return $css;
    }

    /**
     * Javascript IO Setting
     *
     * @param string
     * @param string
     */
    public function js()
    {
        foreach ((array) Resource::get('theme')['js'] as $filename) {
            $filename = preg_replace('/.js$/', '', $filename);

            if (!file_exists(THEME . "/Js/$filename.js")) {
                IO::writeln("Javascript file \"$filename.js\" not found.", 'red');
                continue;
            }

            $this->js[] = THEME . "/Js/$filename.js";
        }

        if (!file_exists(TEMP . '/theme')) {
            mkdir(TEMP . '/theme', 0755, true);
        }

        $handle = fopen(TEMP . '/theme/main.js', 'w+');
        foreach ((array) $this->js as $filepath) {
            $js = file_get_contents($filepath);
            fwrite($handle, $js);
        }
        fclose($handle);
    }
}
