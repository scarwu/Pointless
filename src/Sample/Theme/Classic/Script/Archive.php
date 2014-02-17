<?php
/**
 * Archive Data Generator Script for Theme
 * 
 * @package        Pointless
 * @author        ScarWu
 * @copyright    Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Archive {

    /**
     * @var array
     */
    private $list;

    public function __construct() {
        $this->list = array();

        foreach(Resource::get('article') as $index => $value) {
            if(!isset($this->list[$value['year']]))
                $this->list[$value['year']] = array();

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

        foreach((array)$this->list as $index => $article_list) {
            IO::writeln("Building archive/$index");
            if(NULL == $first) {
                $first = $index;
            }

            $data = [];
            $data['title'] = "Archive: $index";
            $data['path'] = "archive/$index";
            $data['list'] = $this->createDateList($article_list);

            // Extend Data
            $data['name'] = "{$data['title']} | {$blog['name']}";
            $data['header'] = $blog['name'];
            $data['url'] = $blog['dn'] . $blog['base'];

            // Bar
            $data['bar']['index'] = $count + 1;
            $data['bar']['total'] = $total;

            if(isset($this->list[$index - 1])) {
                $data['bar']['n_title'] = $index - 1;
                $data['bar']['n_path'] = "{$data['base']}archive/" . ($index - 1);
            }

            if(isset($this->list[$index + 1])) {
                $data['bar']['p_title'] = $index + 1;
                $data['bar']['p_path'] = "{$data['base']}archive/" . ($index + 1);
            }
                
            $count++;
            
            $container = bindData($data, THEME . '/Template/Container/Archive.php');

            $data['block'] = Resource::get('block');
            $data['block']['container'] = $container;
            
            // Write HTML to Disk
            $result = bindData($data, THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$data['path']}");

            // Sitemap
            Resource::append('sitemap', $data['path']);
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