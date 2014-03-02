<?php
/**
 * Page Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Page
{
    /**
     * @var array
     */
    private $list;

    public function __construct()
    {
        $this->list = Resource::get('article');
    }

    /**
     * Generate Data
     *
     * @param string
     */
    public function gen()
    {
        $quantity = Resource::get('config')['article_quantity'];
        $total = ceil(count($this->list) / $quantity);

        $blog = Resource::get('config')['blog'];

        for ($index = 1;$index <= $total;$index++) {
            IO::writeln("Building page/$index");

            $post = [];
            $post['url'] = "page/$index";
            $post['list'] = array_slice($this->list, $quantity * ($index - 1), $quantity);
            $post['bar']['index'] = $index;
            $post['bar']['total'] = $total;

            if ($index - 1 >= 1) {
                $post['bar']['p_title'] = $index - 1;
                $post['bar']['p_url'] = "{$blog['base']}page/" . ($index - 1);
            }

            if ($index + 1 <= $total) {
                $post['bar']['n_title'] = $index + 1;
                $post['bar']['n_url'] = "{$blog['base']}page/" . ($index + 1);
            }

            $ext = [];
            $ext['title'] = $blog['name'];
            $ext['url'] = $blog['dn'] . $blog['base'];

            $container = bindData([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], THEME . '/Template/container/page.php');

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

        if (file_exists(TEMP . '/page/1/index.html')) {
            copy(TEMP . '/page/1/index.html', TEMP . '/page/index.html');
            copy(TEMP . '/page/1/index.html', TEMP . '/index.html');
            Resource::append('sitemap', 'page');
        }
    }
}
