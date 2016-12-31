<?php
/**
 * Archive Data Generator Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;
use NanoCLI\IO;

class Archive extends ThemeHandler
{
    public function __construct()
    {
        parent::__construct();

        foreach (Resource::get('post')['article'] as $index => $value) {
            if (!isset($this->list[$value['year']])) {
                $this->list[$value['year']] = [];
            }

            $this->list[$value['year']][] = $value;
        }
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

        foreach ($this->list as $index => $post_list) {
            IO::log("Building archive/$index");
            if (null === $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Archive: $index";
            $post['url'] = "archive/$index";
            $post['list'] = $this->createDateList($post_list);

            $paging = [];
            $paging['index'] = $count + 1;
            $paging['total'] = $total;

            if (isset($keys[$count - 1])) {
                $archive = $keys[$count - 1];

                $paging['p_title'] = $archive;
                $paging['p_url'] = "{$blog['base']}archive/$archive";
            }

            if (isset($keys[$count + 1])) {
                $archive = $keys[$count + 1];

                $paging['n_title'] = $archive;
                $paging['n_url'] = "{$blog['base']}archive/$archive";
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
            ], 'container/archive.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }

        $this->createIndex("archive/$first/index.html", 'archive/index.html');
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
