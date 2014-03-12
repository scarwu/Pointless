<?php
/**
 * HTML Generator
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class HTMLGenerator
{
    /**
     * @var array
     */
    private $script;

    public function __construct()
    {
        $this->script = [];
    }

    /**
     * Run HTML Generator
     */
    public function run()
    {
        // Load Script
        $this->loadScript();

        // Generate Block
        $this->genBlock();

        foreach ((array) $this->script as $class) {
            $class->gen();
        }
    }

    /**
     * Load Theme Script
     */
    private function loadScript()
    {
        // Load Script
        foreach ((array) Resource::get('theme')['script'] as $filename) {
            $filename = preg_replace('/.php$/', '', $filename);

            if (file_exists(THEME . "/Script/$filename.php")) {
                require THEME . "/Script/$filename.php";
                $this->script[$filename] = new $filename;
            } elseif (file_exists(ROOT . "/Sample/Script/$filename.php")) {
                require ROOT . "/Sample/Script/$filename.php";
                $this->script[$filename] = new $filename;
            }
        }
    }

    /**
     * Generate Block
     */
    private function genBlock()
    {
        $block = [];

        foreach ((array) Resource::get('theme')['template'] as $blockname => $files) {

            $result = null;

            foreach ($files as $filename) {
                $filename = preg_replace('/.php$/', '', $filename);

                if (!file_exists(THEME . "/Template/$blockname/$filename.php")) {
                    continue;
                }

                $script = explode('_', $filename);
                foreach ($script as $key => $value) {
                    $script[$key] = ucfirst($value);
                }
                $script = join($script);

                $data = [];
                if (array_key_exists($script, $this->script)) {
                    $method = 'get' . ucfirst($blockname) . 'Data';

                    if (method_exists($this->script[$script], $method)) {
                        $data = $this->script[$script]->$method();
                    }
                }
                
                $result .= bindData($data, THEME . "/Template/$blockname/$filename.php");
            }

            if (null !== $result) {
                $block[$blockname] = $result;
            }
        }

        Resource::set('block', $block);
    }
}
