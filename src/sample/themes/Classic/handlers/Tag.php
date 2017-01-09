<?php
/**
 * Tag Handler for Theme
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
        parent::__construct();

        foreach (Resource::get('post')['article'] as $value) {
            foreach ($value['tag'] as $tag) {
                if (!isset($this->list[$tag])) {
                    $this->list[$tag] = [];
                }

                $this->list[$tag][] = $value;
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
    public function renderBlock($block_name)
    {
        $views = Resource::get('theme')['views'];

        if (!isset($views[$block_name])) {
            return false;
        }

        if (!in_array('tag', $views[$block_name])) {
            return false;
        }

        $block = Resource::get('block');

        if (null === $block) {
            $block = [];
        }

        if (!isset($block[$block_name])) {
            $block[$block_name] = '';
        }

        $block[$block_name] .= $this->render([
            'blog' => Resource::get('config')['blog'],
            'list' => $this->list
        ], "{$block_name}/tag.php");

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

        $blog = Resource::get('config')['blog'];

        foreach ($this->list as $index => $post_list) {
            IO::log("Building tag/$index");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Tag: $index";
            $post['url'] = "tag/$index";
            $post['list'] = $this->createDateList($post_list);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $tag = $keys[$count - 1];

                $paging['p_title'] = $tag;
                $paging['p_url'] = "{$blog['base']}tag/$tag";
            }

            if (isset($keys[$count + 1])) {
                $tag = $keys[$count + 1];

                $paging['n_title'] = $tag;
                $paging['n_url'] = "{$blog['base']}tag/$tag";
            }

            $count++;

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'paging' => $paging
            ], 'container/tag.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("/tag/$first/index.html", 'tag/index.html');
    }

    private function createDateList($list)
    {
        $result = [];

        foreach ($list as $article) {
            if (!isset($result[$article['year']])) {
                $result[$article['year']] = [];
            }

            if (!isset($result[$article['year']][$article['month']])) {
                $result[$article['year']][$article['month']] = [];
            }

            $result[$article['year']][$article['month']][] = $article;
        }

        return $result;
    }
}
