<?php
/**
 * Page Data Generator Script for Theme
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Page {

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
        $quantity = Resource::get('config')['article_quantity'];
        $total = ceil(count($this->list) / $quantity);

        $config = Resource::get('config');
                
        for($index = 1;$index <= $total;$index++) {
            IO::writeln("Building page/$index");
            
            $data = [];
            $data['path'] = "page/$index";
            $data['list'] = array_slice($this->list, $quantity * ($index - 1), $quantity);

            // Extend Data
            $data['name'] = $config['blog_name'];
            $data['header'] = $config['blog_name'];
            $data['slogan'] = $config['blog_slogan'];
            $data['description'] = $config['blog_description'];
            $data['keywords'] = $config['blog_keywords'];
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
            $data['bar']['index'] = $index;
            $data['bar']['total'] = $total;

            if($index - 1 > 1) {
                $data['bar']['p_title'] = $index - 1;
                $data['bar']['p_path'] = "{$data['base']}page/" . ($index - 1);
            }
            
            if($index + 1 < $total) {
                $data['bar']['n_title'] = $index + 1;
                $data['bar']['n_path'] = "{$data['base']}page/" . ($index + 1);
            }

            $container = bindData($data, THEME . '/Template/Container/Page.php');

            $data['block'] = Resource::get('block');
            $data['block']['container'] = $container;
            
            // Write HTML to Disk
            $result = bindData($data, THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$data['path']}");

            // Sitemap
            Resource::append('sitemap', $data['path']);
        }
        
        if(file_exists(TEMP . '/page/1/index.html')) {
            copy(TEMP . '/page/1/index.html', TEMP . '/page/index.html');
            copy(TEMP . '/page/1/index.html', TEMP . '/index.html');
            Resource::append('sitemap', 'page');
        }
    }
}
