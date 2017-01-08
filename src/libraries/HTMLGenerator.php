<?php
/**
 * HTML Generator
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Pointless\Extend\ThemeTools;

class HTMLGenerator
{
    use ThemeTools;

    /**
     * Run HTML Generator
     */
    public function run()
    {
        // Get Handler List
        foreach (Resource::get('theme')['handlers'] as $name) {
            $class_name = 'Pointless\\Handler\\' . ucfirst($name);
            $handler_list[lcfirst($name)] = new $class_name;
        }

        // Generate Block
        $block = [];

        foreach (Resource::get('theme')['views'] as $block_name => $name_list) {

            $result = null;

            foreach ($name_list as $name) {
                $data = [];

                if (isset($handler_list[lcfirst($name)])) {
                    $method = 'get' . ucfirst($block_name) . 'Data';

                    if (method_exists($handler_list[lcfirst($name)], $method)) {
                        $data = $handler_list[lcfirst($name)]->$method();
                    }
                }

                $result .= $this->render($data, "{$block_name}/{$name}.php");
            }

            if (null !== $result) {
                $block[$block_name] = $result;
            }
        }

        Resource::set('block', $block);

        // Call Handler Generate
        foreach ($handler_list as $handler) {
            $handler->gen();
        }
    }
}
