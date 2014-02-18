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
        $blog = Resource::get('config')['blog'];

        foreach((array)$this->list as $post) {
            IO::writeln("Building {$post['url']}");

            $data = [];
            $data['blog'] = $blog;
            $data['post'] = $post;

            $container = bindData($data, THEME . '/Template/Container/StaticPage.php');

            $block = Resource::get('block');
            $block['container'] = $container;

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $data = [];
            $data['blog'] = array_merge($blog, $ext);
            $data['block'] = $block;

            // Write HTML to Disk
            $result = bindData($data, THEME . '/Template/index.php');
            writeTo($result, TEMP . "/{$post['url']}");

            // Sitemap
            Resource::append('sitemap', $post['url']);
        }
    }
}
