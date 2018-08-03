<?php
/**
 * Archive Data Handler for Theme
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

class Archive extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'archive';

        foreach (Resource::get('post:article') as $post) {
            $year = $post['year'];

            if (!isset($this->list[$year])) {
                $this->list[$year] = [];
            }

            $this->list[$year][] = $post;
        }
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($blockName)
    {
        $views = Resource::get('attr:theme')['views'];

        if (!isset($views[$blockName])) {
            return false;
        }

        if (!in_array('archive', $views[$blockName])) {
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
            'blog' => Resource::get('attr:config')['blog'],
            'list' => $this->list
        ], "{$blockName}/archive.php");

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

        $blog = Resource::get('attr:config')['blog'];

        foreach ($this->list as $index => $postList) {
            IO::log("Building archive/{$index}");

            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Archive: {$index}";
            $post['url'] = "archive/{$index}";
            $post['list'] = $this->createDateList($postList);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $archive = $keys[$count - 1];

                $paging['p_title'] = $archive;
                $paging['p_url'] = "{$blog['base']}archive/{$archive}";
            }

            if (isset($keys[$count + 1])) {
                $archive = $keys[$count + 1];

                $paging['n_title'] = $archive;
                $paging['n_url'] = "{$blog['base']}archive/{$archive}";
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
            ], 'container/archive.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("archive/{$first}/index.html", 'archive/index.html');
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
