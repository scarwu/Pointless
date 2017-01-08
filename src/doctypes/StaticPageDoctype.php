<?php
/**
 * Static Page Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Doctype;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Doctype;

class StaticPageDoctype extends Doctype
{
    public function __construct()
    {
        parent::__construct();

        $this->type = 'staticPage';
        $this->name = 'Static Page';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "]
        ];
    }

    public function inputHandleAndSaveFile($input)
    {
        $filename = Utility::pathReplace($input['url']);
        $filename = strtolower($filename);
        $filename = "static_{$filename}.md";

        return $this->save([
            'filename' => $filename,
            'title' => $input['title'],
            'header' => [
                'type' => $this->type,
                'url' => Utility::pathReplace($input['url'], true),
                'message' => false,
                'publish' => false
            ]
        ]);
    }

    public function postHandleAndGetResult($post)
    {
        if (!preg_match('/\.html$/', $post['url'])) {
            $post['url'] .= '/';
        }

        return [
            'type' => $post['type'],
            'title' => $post['title'],
            'url' => $post['url'],
            'content' => $post['content'],
            'message' => $post['message']
        ];
    }
}
