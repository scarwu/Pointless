<?php
/**
 * Static Page Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class StaticPage extends ThemeScript
{
    public function __construct()
    {
        parent::__construct();

        $this->list = Resource::get('static');
    }

    /**
     * Generate Data
     *
     * @param string
     */
    public function gen()
    {
        $blog = Resource::get('config')['blog'];

        foreach ((array) $this->list as $post) {
            IO::log("Building {$post['url']}");

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], 'container/static_page.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'block' => $block
            ], 'index.php'));
        }
    }
}
