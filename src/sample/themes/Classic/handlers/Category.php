<?php
/**
 * Category Data Handler for Theme
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

class Category extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'category';

        foreach (Resource::get('post:article') as $post) {
            $category = $post['category'];

            if (!isset($this->list[$category])) {
                $this->list[$category] = [];
            }

            $this->list[$category][] = $post;
        }

        uasort($this->list, function ($a, $b) {
            if (count($a) === count($b)) {
                return 0;
            }

            return count($a) > count($b) ? -1 : 1;
        });
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($blockName)
    {
        $views = Resource::get('theme:config')['views'];

        if (!isset($views[$blockName])) {
            return false;
        }

        if (!in_array('category', $views[$blockName])) {
            return false;
        }

        $block = Resource::get('block');

        if (null === $block) {
            $block = [];
        }

        if (!isset($block[$blockName])) {
            $block[$blockName] = '';
        }

        $block[$blockName] .= $this->render([
            'blog' => Resource::get('system:config')['blog'],
            'list' => $this->list
        ], "{$blockName}/category.php");

        Resource::set('block', $block);
    }

    /**
     * Render Page
     */
    public function renderPage()
    {
        $first = null;
        $count = 0;
        $total = count($this->list);
        $keys = array_keys($this->list);

        $blog = Resource::get('system:config')['blog'];

        foreach ($this->list as $index => $postList) {
            IO::log("Building category/{$index}");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] ="Category: {$index}";
            $post['url'] = "category/{$index}";
            $post['list'] = $this->createDateList($postList);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $category = $keys[$count - 1];

                $paging['p_title'] = $category;
                $paging['p_url'] = "{$blog['base']}category/{$category}";
            }

            if (isset($keys[$count + 1])) {
                $category = $keys[$count + 1];

                $paging['n_title'] = $category;
                $paging['n_url'] = "{$blog['base']}category/{$category}";
            }

            $count++;

            $extBlog = [];
            $extBlog['title'] = "{$post['title']} | {$blog['name']}";
            $extBlog['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'paging' => $paging
            ], 'container/category.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("category/{$first}/index.html", 'category/index.html');
    }

    private function createDateList($list)
    {
        $result = [];

        foreach ($list as $article) {
            $year = $article['year'];
            $month = $article['month'];

            if (!isset($result[$year])) {
                $result[$year] = [];
            }

            if (!isset($result[$year][$month])) {
                $result[$year][$month] = [];
            }

            $result[$year][$month][] = $article;
        }

        return $result;
    }
}
