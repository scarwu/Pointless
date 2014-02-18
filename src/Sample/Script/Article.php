<?php
/**
 * Article Data Generator Script for Theme
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Article {

    /**
     * @var array
     */
    private $list;
    
    public function __construct() {
        $this->list = Resource::get('article');
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
        $count = 0;
        $total = count($this->list);
        $key = array_keys($this->list);

        $blog = Resource::get('config')['blog'];

        foreach((array)$this->list as $data) {
            IO::writeln("Building article/{$data['url']}");
            
            $data['path'] = "article/{$data['url']}";

            // Extend Data
            $data['name'] = "{$data['title']} | {$blog['name']}";
            $data['header'] = $blog['name'];
            $data['keywords'] = $blog['keywords'] . $data['keywords'];
            $data['url'] = $blog['dn'] . $blog['base'];

            // Bar
            $data['bar']['index'] = $count + 1;
            $data['bar']['total'] = $total;

            if(isset($key[$count - 1])) {
                $title = $this->list[$key[$count - 1]]['title'];
                $path = $this->list[$key[$count - 1]]['url'];

                $data['bar']['p_title'] = $title;
                $data['bar']['p_path'] = "{$data['base']}article/$path";
            }

            if(isset($key[$count + 1])) {
                $title = $this->list[$key[$count + 1]]['title'];
                $path = $this->list[$key[$count + 1]]['url'];

                $data['bar']['n_title'] = $title;
                $data['bar']['n_path'] = "{$data['base']}article/$path";
            }

            $count++;

            $container = bindData($data, THEME . '/Template/Container/Article.php');

            $data['block'] = Resource::get('block');
            $data['block']['container'] = $container;
            
            // Write HTML to Disk
            $result = bindData($data, THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$data['path']}");

            // Sitemap
            Resource::append('sitemap', $data['path']);
        }
    }
}
