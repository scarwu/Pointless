<?php
/**
 * Article Document Format
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

class Article extends Format
{
    /**
     * @var string
     */
    protected $type = 'article';

    /**
     * @var string
     */
    protected $name = 'Article';

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
        ],
        [
            'name' => 'category',
            'statement' => 'Enter Category:'
        ],
        [
            'name' => 'tags',
            'statement' => 'Enter Tags: (tag1|tag2|tag3...)'
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
        $time = time();
        $filename = Utility::pathReplace($input['url']);
        $filename = date('Ymd_', $time) . $filename;

        return $this->saveToFile([
            'type' => $this->type,
            'filename' => $filename,
            'header' => [
                'type' => $this->type,
                'url' => Utility::pathReplace($input['url']),
                'tags' => $input['tags'],
                'category' => $input['category'],
                'date' => date('Y-m-d', $time),
                'time' => date('H:i:s', $time),
                'coverImage' => null,
                'withMessage' => true,
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
        $format = Resource::get('blog:config')['post']['article']['format'];
        $format = trim($format, '/');

        // Time information
        list($year, $month, $day) = explode('-', $post['params']['date']);
        list($hour, $minute, $second) = explode(':', $post['params']['time']);

        $timestamp = strtotime("{$day}-{$month}-{$year} {$post['params']['time']}");

        // Generate custom url
        $url = str_replace([
            ':year', ':month', ':day',
            ':hour', ':minute', ':second', ':timestamp',
            ':title', ':url'
        ], [
            $year, $month, $day,
            $hour, $minute, $second, $timestamp,
            $post['title'], $post['params']['url']
        ], $format);

        if (false === (bool) preg_match('/\.html$/', $url)) {
            $url .= '/';
        }

        // Sort tags
        $post['params']['tags'] = explode('|', $post['params']['tags']);

        sort($post['params']['tags']);

        // Summary and Description
        $summary = preg_replace('/<!--more-->(.|\s)*/', '', $post['content']);

        preg_match('/<p>((:?.|\n)*?)<\/p>/', $summary, $match);

        $description = (true === isset($match[1])) ? strip_tags($match[1]) : '';

        return [
            'type' => $post['params']['type'],
            'title' => $post['title'],
            'url' => $url,
            'content' => $post['content'],
            'summary' => $summary,
            'description' => $description,
            'coverImage' => (true === isset($post['params']['coverImage'])) ? $post['params']['coverImage'] : null,
            'category' => $post['params']['category'],
            'tags' => $post['params']['tags'],
            'date' => $post['params']['date'],
            'time' => $post['params']['time'],
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'timestamp' => $timestamp,
            'accessTime' => $post['accessTime'],
            'createTime' => $post['createTime'],
            'modifyTime' => $post['modifyTime'],
            'withMessage' => $post['params']['withMessage']
        ];
    }
}
