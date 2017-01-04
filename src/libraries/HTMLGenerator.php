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
        $handler_list = Misc::getHandlerList();

        foreach ($handler_list as $index => $handler) {
            $class_name = 'Pointless\\Handler\\' . ucfirst($handler);
            $handler_list[$index] = new $class_name;
        }

        // Generate Block
        $block = [];

        foreach (Resource::get('theme')['views'] as $blockname => $files) {

            $result = null;

            foreach ($files as $filename) {
                $filename = preg_replace('/.php$/', '', $filename);

                if (!file_exists(BLOG_THEME . "/views/{$blockname}/{$filename}.php")) {
                    continue;
                }

                $script = explode('_', $filename);
                foreach ($script as $key => $value) {
                    $script[$key] = ucfirst($value);
                }
                $script = join($script);

                $data = [];
                if (array_key_exists($script, $handler_list)) {
                    $method = 'get' . ucfirst($blockname) . 'Data';

                    if (method_exists($handler_list[$script], $method)) {
                        $data = $handler_list[$script]->$method();
                    }
                }

                $result .= $this->render($data, "{$blockname}/{$filename}.php");
            }

            if (null !== $result) {
                $block[$blockname] = $result;
            }
        }

        Resource::set('block', $block);

        // Call Handler Generate
        foreach ($handler_list as $handler) {
            $handler->gen();
        }
    }
}
