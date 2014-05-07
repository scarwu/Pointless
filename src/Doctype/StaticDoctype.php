<?php
/**
 * Static Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class StaticDoctype extends Doctype
{
    public function __construct()
    {
        parent::__construct();

        $this->id = 'static';
        $this->name = 'Static Page';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "]
        ];
    }

    public function headerHandleAndSave($header)
    {
        $filename = Utility::pathReplace($header['url']);
        $filename = strtolower($filename);
        $filename = "static_$filename.md";

        return $this->save($filename, [
            'type' => $this->id,
            'title' => $header['title'],
            'url' => Utility::pathReplace($header['url'], true),
            'message' => false,
            'publish' => false
        ]);
    }

    public function postHandleAndGetResult($post)
    {
        return [
            'type' => $post['type'],
            'title' => $post['title'],
            'url' => $post['url'],
            'content' => $post['content'],
            'message' => $post['message']
        ];
    }
}
