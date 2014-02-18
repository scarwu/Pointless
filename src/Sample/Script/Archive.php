<?php
/**
 * Archive Data Generator Script for Theme
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Archive {

    /**
     * @var array
     */
    private $list;

    public function __construct() {
        $this->list = [];

        foreach(Resource::get('article') as $index => $value) {
            if(!isset($this->list[$value['year']]))
                $this->list[$value['year']] = [];

            $this->list[$value['year']][] = $value;
        }
    }

    /**
     * Get List
     *
     * @return array
     */
    public function getList() {
        return $this->list;
    }

    /**
     * Generate Data
     *
     * @param string
     */
    public function gen() {
        $first = NULL;
        $count = 0;
        $total = count($this->list);
        
        $blog = Resource::get('config')['blog'];

        foreach((array)$this->list as $index => $post_list) {
            IO::writeln("Building archive/$index");
            if(NULL == $first) {
                $first = $index;
            }

            $post = [];
            $post['title'] = "Archive: $index";
            $post['url'] = "archive/$index";
            $post['list'] = $this->createDateList($post_list);
            $post['bar']['index'] = $count + 1;
            $post['bar']['total'] = $total;

            if(isset($this->list[$index - 1])) {
                $post['bar']['n_title'] = $index - 1;
                $post['bar']['n_path'] = "{$blog['base']}archive/" . ($index - 1);
            }

            if(isset($this->list[$index + 1])) {
                $post['bar']['p_title'] = $index + 1;
                $post['bar']['p_path'] = "{$blog['base']}archive/" . ($index + 1);
            }
                
            $count++;
            
            $container = bindData($post, THEME . '/Template/Container/Archive.php');

            $block = Resource::get('block');
            $block['container'] = $container;

            $ext = [];
            $ext['name'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $data = [];
            $data['blog'] = $blog;
            $data['block'] = $block;
            $data['ext'] = $ext;
            
            // Write HTML to Disk
            $result = bindData($data, THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$post['url']}");

            // Sitemap
            Resource::append('sitemap', $post['url']);
        }
        
        if(file_exists(TEMP . "/archive/$first/index.html")) {
            copy(TEMP . "/archive/$first/index.html", TEMP . '/archive/index.html');
            Resource::append('sitemap', 'archive');
        }
    }

    private function createDateList($list) {
        $result = [];

        foreach((array)$list as $article) {
            if(!isset($result[$article['year']])) {
                $result[$article['year']] = [];
            }

            if(!isset($result[$article['year']][$article['month']])) {
                $result[$article['year']][$article['month']] = [];
            }

            $result[$article['year']][$article['month']][] = $article;
        }

        return $result;
    }
}