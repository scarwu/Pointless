<?php
/**
 * HTML Generator
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class HTMLGenerator {

    /**
     * @var array
     */
    private $script;
    
    public function __construct() {
        $this->script = [];
    }
    
    /**
     * Run HTML Generator
     */
    public function run() {

        // Load Script
        $this->loadScript();
        
        // Generate Block
        $this->genBlock();

        foreach((array)$this->script as $class)
            $class->gen();
    }

    /**
     * Load Theme Script
     */
    private function loadScript() {
        $handle = opendir(THEME . '/Script');
        while($filename = readdir($handle)) {
            if('.' == $filename || '..' == $filename)
                continue;

            require THEME . "/Script/$filename";

            $class_name = preg_replace('/.php$/', '', $filename);
            $this->script[$class_name] = new $class_name;
        }
        closedir($handle);
    }

    /**
     * Generate Block
     */
    private function genBlock() {
        $filter = ['.', '..', 'Container', 'index.php'];
        $block = [];

        $block_handle = opendir(THEME . '/Template');
        while($block_name = readdir($block_handle)) {
            if(in_array($block_name, $filter))
                continue;

            $file_list = [];

            $handle = opendir(THEME . "/Template/$block_name");
            while($file = readdir($handle)) {
                if('.' == $file || '..' == $file)
                    continue;

                $file_list[] = $file;
            }
            closedir($handle);

            sort($file_list);

            $result = '';
            foreach((array)$file_list as $file) {
                $script_name = preg_replace(['/^\d+_/', '/.php$/'], '', $file);
                $data['config'] = Resource::get('config');
                $data['list'] = isset($this->script[$script_name])
                    ? $this->script[$script_name]->getList()
                    : NULL;
                $result .= bindData($data, THEME . "/Template/$block_name/$file");
            }

            $block[strtolower($block_name)] = $result;
        }
        closedir($block_handle);

        Resource::set('block', $block);
    }
}