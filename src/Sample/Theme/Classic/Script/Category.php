<?php
/**
 * Category Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Category extends ThemeScript
{
    public function __construct()
    {
        parent::__construct();

        foreach (Resource::get('post')['article'] as $value) {
            if (!isset($this->list[$value['category']]))
                $this->list[$value['category']] = [];

            $this->list[$value['category']][] = $value;
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
            IO::log("Building category/$index");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] ="Category: $index";
            $post['url'] = "category/$index";
            $post['list'] = $this->createDateList($post_list);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $category = $keys[$count - 1];

                $paging['p_title'] = $category;
                $paging['p_url'] = "{$blog['base']}category/$category";
            }

            if (isset($keys[$count + 1])) {
                $category = $keys[$count + 1];

                $paging['n_title'] = $category;
                $paging['n_url'] = "{$blog['base']}category/$category";
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
            ], 'container/category.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("category/$first/index.html", 'category/index.html');
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
