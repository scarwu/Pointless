<?php
/**
 * Static Page Document Format
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Format;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Format;

class StaticPage extends Format
{
    public function __construct()
    {
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
