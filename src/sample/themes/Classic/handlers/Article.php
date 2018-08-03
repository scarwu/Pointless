<?php
/**
 * Article Data Handler for Theme
 *
 * @package     Pointless Theme - Classic
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Classic
 */

namespace Pointless\Handler;

use Pointless\Library\Resource;
use Pointless\Extend\ThemeHandler;
use NanoCLI\IO;

class Article extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'article';
        $this->list = Resource::get('post:article');
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($blockName)
    {
        return false;
    }

    /**
     * Render Page
     */
    public function renderPage()
    {
        $count = 0;
        $total = count($this->list);
        $keys = array_keys($this->list);

        $blog = Resource::get('system:config')['blog'];

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
                $paging['p_url'] = "{$blog['base']}article/{$url}";
            }

            if (isset($keys[$count + 1])) {
                $key = $keys[$count + 1];
                $title = $this->list[$key]['title'];
                $url = $this->list[$key]['url'];

                $paging['n_title'] = $title;
                $paging['n_url'] = "{$blog['base']}article/{$url}";
            }

            $count++;

            $extBlog = [];
            $extBlog['title'] = "{$post['title']} | {$blog['name']}";
            $extBlog['url'] = $blog['dn'] . $blog['base'];
            $extBlog['description'] = ('' !== $post['description'])
                ? $post['description']
                : $blog['description'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'paging' => $paging
            ], 'container/article.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }
    }
}
