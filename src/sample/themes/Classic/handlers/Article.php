<?php
/**
 * Article Handler Script for Theme
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

class Article extends ThemeHandler
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
        $count = 0;
        $total = count($this->list);
        $keys = array_keys($this->list);

        $blog = Resource::get('config')['blog'];

        foreach ($this->list as $post) {
            IO::log("Building article/{$post['url']}");

            $post['url'] = "article/{$post['url']}";

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $key = $keys[$count - 1];
                $title = $this->list[$key]['title'];
                $url = $this->list[$key]['url'];

                $paging['p_title'] = $title;
                $paging['p_url'] = "{$blog['base']}article/$url";
            }

            if (isset($keys[$count + 1])) {
                $key = $keys[$count + 1];
                $title = $this->list[$key]['title'];
                $url = $this->list[$key]['url'];

                $paging['n_title'] = $title;
                $paging['n_url'] = "{$blog['base']}article/$url";
            }

            $count++;

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];
            $ext['description'] = '' !== $post['description']
                ? $post['description']
                : $blog['description'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'paging' => $paging
            ], 'container/article.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }
    }
}
