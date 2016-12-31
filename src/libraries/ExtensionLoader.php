<?php
/**
 * Extensions Loader
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

class ExtensionsLoader
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
        // Load Extensions
        foreach (Resource::get('theme')['extension'] as $filename) {
            $filename = preg_replace('/.php$/', '', $filename);

            if (file_exists(EXTENSION . "/$filename.php")) {
                require EXTENSION . "/$filename.php";
                $this->extension[$filename] = new $filename;
            } elseif (file_exists(ROOT . "/sample/extensions/{$filename}.php")) {
                require ROOT . "/sample/extensions/{$filename}.php";
                $this->extension[$filename] = new $filename;
            }
        }

        foreach ($this->extension as $class) {
            $class->run();
        }
    }
}
