<?php
/**
 * Static Page Handler Script for Theme
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Library\Resource;
use Pointless\Extend\ThemeHandler;
use NanoCLI\IO;

class StaticPage extends ThemeHandler
{
    public function __construct()
    {
        parent::__construct();

        $this->list = Resource::get('post')['static'];
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($block_name)
    {
        return false;
    }

    /**
     * Render Page
     */
    public function renderPage()
    {
        $blog = Resource::get('config')['blog'];

        foreach ($this->list as $post) {
            IO::log("Building {$post['url']}");

            $ext = [];
            $ext['title'] = "{$post['title']} | {$blog['name']}";
            $ext['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post
            ], 'container/staticPage.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $ext),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }
    }
}
