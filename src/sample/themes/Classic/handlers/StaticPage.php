<?php
/**
 * Static Page Data Handler for Theme
 *
 * @package     Pointless Theme - Classic
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Classic
 */

namespace Pointless\Handler;

use Pointless\Library\Resource;
use Pointless\Extend\ThemeHandler;
use NanoCLI\IO;

class StaticPage extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'staticPage';
        $this->list = Resource::get('post:staticPage');
    }

    /**
     * Render Block
     *
     * @param string
     */
    public function renderBlock($blockName)
    {
        return false;
    }

    /**
     * Render Page
     */
    public function renderPage()
    {
        $blog = Resource::get('system:config')['blog'];

        foreach ($this->list as $post) {
            IO::log("Building {$post['url']}");

            $extBlog = [];
            $extBlog['title'] = "{$post['title']} | {$blog['name']}";
            $extBlog['url'] = $blog['dn'] . $blog['base'];

            $block = Resource::get('block');
            $block['container'] = $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post
            ], 'container/staticPage.php');

            // Save HTML
            $this->save($post['url'], $this->render([
                'blog' => array_merge($blog, $extBlog),
                'post' => $post,
                'block' => $block
            ], 'index.php'));
        }
    }
}
