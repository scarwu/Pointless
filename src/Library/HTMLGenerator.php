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

        foreach((array)$this->script as $class) {
            $class->gen();
        }
    }

    /**
     * Load Theme Script
     */
    private function loadScript() {

        // Load Script
        foreach(Resource::get('theme')['script'] as $filename) {
            if(file_exists(THEME . "/Script/$filename")) {
                require THEME . "/Script/$filename";

                $class_name = preg_replace('/.php$/', '', $filename);
                $this->script[$class_name] = new $class_name;
            }
            else if(ROOT . "/Sample/Script/$filename") {
                require ROOT . "/Sample/Script/$filename";

                $class_name = preg_replace('/.php$/', '', $filename);
                $this->script[$class_name] = new $class_name;
            }
        }

    }

    /**
     * Generate Block
     */
    private function genBlock() {
        $block = [];

        foreach(Resource::get('theme')['template'] as $blockname => $files) {

            $result = NULL;

            foreach ($files as $filename) {
                if(!file_exists(THEME . "/Template/$blockname/$filename"))
                    continue;

                $script = preg_replace('/.php$/', '', $filename);
                $script = explode('_', $script);
                foreach($script as $key => $value) {
                    $script[$key] = ucfirst($value);
                }
                $script = join($script);

                $data['blog'] = Resource::get('config')['blog'];
                $data['list'] = isset($this->script[$script])
                    ? $this->script[$script]->getList()
                    : NULL;
                
                $result .= bindData($data, THEME . "/Template/$blockname/$filename");
            }

            if(NULL != $result) {
                $block[$blockname] = $result;
            }
        }

        Resource::set('block', $block);
    }
}