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

        $config = Resource::get('config');

        foreach((array)$this->list as $data) {
            IO::writeln("Building article/{$data['url']}");
            
            $data['path'] = "article/{$data['url']}";

            // Extend Data
            $data['name'] = "{$data['title']} | {$config['blog_name']}";
            $data['header'] = $config['blog_name'];
            $data['slogan'] = $config['blog_slogan'];
            $data['description'] = $config['blog_description'];
            $data['keywords'] = $config['blog_keywords'] . $data['keywords'];
            $data['footer'] = $config['blog_footer'];
            $data['dn'] = $config['blog_dn'];
            $data['base'] = $config['blog_base'];
            $data['url'] = $config['blog_dn'] . $config['blog_base'];
            $data['lang'] = $config['blog_lang'];
            $data['author'] = $config['author_name'];
            $data['email'] = $config['author_email'];
            $data['google_analytics'] = $config['google_analytics'];
            $data['disqus_shortname'] = $config['disqus_shortname'];

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
