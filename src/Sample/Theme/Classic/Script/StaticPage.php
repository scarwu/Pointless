<?php
/**
 * Static Page Data Generator Script for Theme
 * 
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class StaticPage {

    /**
     * @var array
     */
    private $list;
    
    public function __construct() {
        $this->list = Resource::get('static');
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
        $config = Resource::get('config');

        foreach((array)$this->list as $data) {
            IO::writeln("Building {$data['url']}");
            
            $data['path'] = $data['url'];

            // Extend Data
            $data['name'] = "{$data['title']} | {$config['blog_name']}";
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

            $container = bindData($data, THEME . '/Template/Container/StaticPage.php');

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
