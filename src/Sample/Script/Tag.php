<?php
/**
 * Tag Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Tag
{
    /**
     * @var array
     */
    private $list;

    public function __construct()
    {
        $this->list = [];

        foreach (Resource::get('article') as $value) {
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
     * Get Side Data
     *
     * @return array
     */
    public function getSideData()
    {
        $data['blog'] = Resource::get('config')['blog'];
        $data['list'] = $this->list;

        return $data;
    }

    /**
     * Generate Data
     *
     * @param string
     */
    public function gen()
    {
        $first = null;
        $count = 0;
        $total = count($this->list);
        $keys = array_keys($this->list);

        $blog = Resource::get('config')['blog'];

        foreach ((array) $this->list as $index => $post_list) {
            IO::writeln("Building tag/$index");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Tag: $index";
            $post['url'] = "tag/$index";
            $post['list'] = $this->createDateList($post_list);
            $post['bar']['index'] = $count + 1;
            $post['bar']['total'] = $total;

            if (isset($keys[$count - 1])) {
                $tag = $keys[$count - 1];

                $post['bar']['p_title'] = $tag;
                $post['bar']['p_url'] = "{$blog['base']}tag/$tag";
            }

            if (isset($keys[$count + 1])) {
                $tag = $keys[$count + 1];

                $post['bar']['n_title'] = $tag;
                $post['bar']['n_url'] = "{$blog['base']}tag/$tag";
            }

            $count++;

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $container = bindData([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], THEME . '/Template/container/tag.php');

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

        if (file_exists(TEMP . "/tag/$first/index.html")) {
            copy(TEMP . "/tag/$first/index.html", TEMP . '/tag/index.html');
            Resource::append('sitemap', 'tag');
        }
    }

    private function createDateList($list)
    {
        $result = [];

        foreach ((array) $list as $article) {
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
