<?php
/**
 * Css and Js Compressor
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class Compress {

    /**
     * @var array
     */
    private $css_list;

    /**
     * @var array
     */
    private $js_list;

    public function __construct() {
        $this->css_list = [];
        $this->js_list = [];
    }

    /**
     * Css IO Setting
     *
     * @param string
     * @param string
     */
    public function css() {
        foreach(Resource::get('theme')['css'] as $filename) {
            if(!file_exists(THEME . "/Css/$filename"))
                continue;
                
            $this->css_list[] = THEME . "/Css/$filename";
        }
        
        if(!file_exists(TEMP . '/theme')) {
            mkdir(TEMP . '/theme', 0755, TRUE);
        }
        
        $handle = fopen(TEMP . '/theme/main.css', 'w+');
        foreach((array)$this->css_list as $filepath) {
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
    private function cssCompressor($css) {
        $css = preg_replace('/(\f|\n|\r|\t|\v)/', '', $css);
        $css = preg_replace('/\/\*.+?\*\//', '', $css);
        $css = preg_replace('/[ ]+/', ' ', $css);
        $css = str_replace(
            [' ,', ', ', ': ', ' :', ' {', '{ ', ' }', '} ', ' ;', '; '],
            [',', ',', ':', ':', '{', '{', '}', '}', ';', ';'],
            $css
        );
        return $css;
    }
    
    /**
     * Javascript IO Setting
     *
     * @param string
     * @param string
     */
    public function js() {
        foreach(Resource::get('theme')['js'] as $filename) {
            if(!file_exists(THEME . "/Js/$filename"))
                continue;

            $this->js_list[] = THEME . "/Js/$filename";
        }
        
        if(!file_exists(TEMP . '/theme')) {
            mkdir(TEMP . '/theme', 0755, TRUE);
        }
        
        $handle = fopen(TEMP . '/theme/main.js', 'w+');
        foreach((array)$this->js_list as $filepath) {
            $js = file_get_contents($filepath);
            fwrite($handle, $js);
        }
        fclose($handle);
    }
}
