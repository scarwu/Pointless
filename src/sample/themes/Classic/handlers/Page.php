<?php
/**
 * Page Handler Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Library\Resource;
use Pointless\Extend\ThemeHandler;
use NanoCLI\IO;

class Page extends ThemeHandler
{
    public function __construct()
    {
        parent::__construct();

        $this->list = Resource::get('post')['article'];
    }

    /**
     * Generate Data
     *
     * @param string
     */
    public function gen()
    {
        $quantity = Resource::get('config')['post']['article']['quantity'];
        $total = ceil(count($this->list) / $quantity);

        $blog = Resource::get('config')['blog'];

        for ($index = 1;$index <= $total;$index++) {
            IO::log("Building page/$index");

            $post = [];
            $post['url'] = "page/$index";
            $post['list'] = array_slice($this->list, $quantity * ($index - 1), $quantity);

            $paging = [];
            $paging['index'] = $index;
            $paging['total'] = $total;

            if ($index - 1 >= 1) {
                $paging['p_title'] = $index - 1;
                $paging['p_url'] = "{$blog['base']}page/" . ($index - 1);
            }

            if ($index + 1 <= $total) {
                $paging['n_title'] = $index + 1;
                $paging['n_url'] = "{$blog['base']}page/" . ($index + 1);
            }

            $ext = [];
            $ext['title'] = $blog['name'];
            $ext['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'paging' => $paging
            ], 'container/page.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex('page/1/index.html', 'page/index.html');
        $this->createIndex('page/1/index.html', 'index.html');
    }
}
