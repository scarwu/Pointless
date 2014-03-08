<?php
/**
 * Extension Loader
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class ExtensionLoader
{
    /**
     * @var array
     */
    private $extension;

    public function __construct()
    {
        $this->extension = [];
    }

    /**
     * Run Loader
     */
    public function run()
    {
        // Load Extension
        foreach ((array) Resource::get('config')['extension'] as $filename) {
            $filename = preg_replace('/.php$/', '', $filename);

            if (file_exists(EXTENSION . "/$filename.php")) {
                require EXTENSION . "/$filename.php";
                $this->extension[$filename] = new $filename;
            } elseif (file_exists(ROOT . "/Sample/Extension/$filename.php")) {
                require ROOT . "/Sample/Extension/$filename.php";
                $this->extension[$filename] = new $filename;
            }
        }

        foreach ((array) $this->extension as $class) {
            $class->run();
        }
    }
}
