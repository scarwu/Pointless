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

class StaticPage
{
    /**
     * @var array
     */
    private $list;

    public function __construct()
    {
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
            IO::writeln("Building {$post['url']}");

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $container = bindData([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], THEME . '/Template/container/static_page.php');

            $block = Resource::get('block');
            $block['container'] = $container;

            // Write HTML to Disk
            $result = bindData([
                'blog' => array_merge($blog, $ext),
                'block' => $block
            ], THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$post['url']}");

            // Sitemap
            Resource::append('sitemap', $post['url']);
        }
    }
}
