<?php
/**
 * Extension Loader
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class ExtensionLoader {

    /**
     * @var array
     */
    private $extension;

    public function __construct() {
        $this->extension = [];
    }

    /**
     * Run Loader
     */
    public function run() {

        // Load Extension
        foreach(Resource::get('theme')['extension'] as $filename) {
            if(file_exists(EXTENSION . "/$filename")) {
                require EXTENSION . "/$filename";

                $class_name = preg_replace('/.php$/', '', $filename);
                $this->extension[$class_name] = new $class_name;
            }
            else if(file_exists(ROOT . "/Sample/Extension/$filename")) {
                require ROOT . "/Sample/Extension/$filename";

                $class_name = preg_replace('/.php$/', '', $filename);
                $this->extension[$class_name] = new $class_name;
            }
        }

        foreach((array)$this->extension as $class) {
            $class->run();
        }
    }
}