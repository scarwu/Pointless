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

class Page extends ThemeScript
{
    public function __construct()
    {
        parent::__construct();

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
            IO::log("Building page/$index");

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

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], 'container/page.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex('page/1/index.html', 'page/index.html');
        $this->createIndex('page/1/index.html', 'index.html');
    }
}
