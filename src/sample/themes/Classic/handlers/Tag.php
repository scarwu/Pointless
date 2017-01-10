<?php
/**
 * Tag Data Handler for Theme
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

class Tag extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'tag';

        foreach (Resource::get('post:article') as $post) {
            foreach ($post['tag'] as $tag) {
                if (!isset($this->list[$tag])) {
                    $this->list[$tag] = [];
                }

                $this->list[$tag][] = $post;
            }
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
        $views = Resource::get('attr:theme')['views'];

        if (!isset($views[$blockName])) {
            return false;
        }

        if (!in_array('tag', $views[$blockName])) {
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
        ], "{$blockName}/tag.php");

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
            IO::log("Building tag/{$index}");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Tag: {$index}";
            $post['url'] = "tag/{$index}";
            $post['list'] = $this->createDateList($postList);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $tag = $keys[$count - 1];

                $paging['p_title'] = $tag;
                $paging['p_url'] = "{$blog['base']}tag/{$tag}";
            }

            if (isset($keys[$count + 1])) {
                $tag = $keys[$count + 1];

                $paging['n_title'] = $tag;
                $paging['n_url'] = "{$blog['base']}tag/{$tag}";
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
            ], 'container/tag.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("/tag/{$first}/index.html", 'tag/index.html');
    }

    private function createDateList($postList)
    {
        $result = [];

        foreach ($postList as $post) {
            $year = $post['year'];
            $month = $post['month'];

            if (!isset($result[$year])) {
                $result[$year] = [];
            }

            if (!isset($result[$year][$month])) {
                $result[$year][$month] = [];
            }

            $result[$year][$month][] = $post;
        }

        return $result;
    }
}
