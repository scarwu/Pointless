<?php
/**
 * Describe Document Format
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

class Describe extends Format
{
    /**
     * @var string
     */
    protected $type = 'describe';

    /**
     * @var string
     */
    protected $name = 'Describe';

    /**
     * @var array
     */
    protected $questionList = [
        [
            'name' => 'title',
            'statement' => 'Enter Title:'
        ],
        [
            'name' => 'url',
            'statement' => 'Enter Url:'
        ]
    ];

    /**
     * Convert Input
     *
     * @param array $input
     *
     * @return array
     */
    public function convertInput($input)
    {
        $filename = Utility::pathReplace($input['url']);
        $filename = strtolower($filename);

        return $this->saveToFile([
            'type' => $this->type,
            'filename' => $filename,
            'header' => [
                'type' => $this->type,
                'url' => Utility::pathReplace($input['url'], true),
                'withMessage' => false,
                'isPublic' => false
            ],
            'title' => $input['title']
        ]);
    }

    /**
     * Convert Post
     *
     * @param array $post
     *
     * @return array
     */
    public function convertPost($post)
    {
        if (false === (bool) preg_match('/\.html$/', $post['params']['url'])) {
            $post['params']['url'] .= '/';
        }

        return [
            'type' => $post['params']['type'],
            'title' => $post['title'],
            'url' => $post['params']['url'],
            'content' => $post['content'],
            'accessTime' => $post['accessTime'],
            'createTime' => $post['createTime'],
            'modifyTime' => $post['modifyTime'],
            'withMessage' => $post['params']['withMessage']
        ];
    }
}
