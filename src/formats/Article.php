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
    public function __construct()
    {
        $this->type = 'article';
        $this->name = 'Article';
        $this->questionList = [
            [
                'name' => 'title',
                'statement' => "Enter Title:\n-> "
            ],
            [
                'name' => 'url',
                'statement' => "Enter Url:\n-> "
            ],
            [
                'name' => 'category',
                'statement' => "Enter Category:\n-> "
            ],
            [
                'name' => 'tags',
                'statement' => "Enter Tags: (tag1|tag2|tag3...)\n-> "
            ]
        ];
    }

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
            'filename' => $filename,
            'header' => [
                'type' => $this->type,
                'url' => Utility::pathReplace($input['url']),
                'tags' => $input['tags'],
                'category' => $input['category'],
                'date' => date('Y-m-d', $time),
                'time' => date('H:i:s', $time),
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
        $format = Resource::get('system:config')['post']['article']['format'];
        $format = trim($format, '/');

        // Time information
        list($year, $month, $day) = explode('-', $post['date']);
        list($hour, $minute, $second) = explode(':', $post['time']);

        $timestamp = strtotime("{$day}-{$month}-{$year} {$post['time']}");

        // Generate custom url
        $url = str_replace([
            ':year', ':month', ':day',
            ':hour', ':minute', ':second', ':timestamp',
            ':title', ':url'
        ], [
            $year, $month, $day,
            $hour, $minute, $second, $timestamp,
            $post['title'], $post['url']
        ], $format);

        if (!preg_match('/\.html$/', $url)) {
            $url .= '/';
        }

        // Sort tags
        $post['tags'] = explode('|', $post['tags']);
        sort($post['tags']);

        // Summary and Description
        $summary = preg_replace('/<!--more-->(.|\s)*/', '', $post['content']);

        preg_match('/<p>((:?.|\n)*?)<\/p>/', $summary, $match);

        $description = isset($match[1]) ? strip_tags($match[1]) : '';

        return [
            'type' => $post['type'],
            'title' => $post['title'],
            'url' => $url,
            'content' => $post['content'],
            'summary' => $summary,
            'description' => $description,
            'category' => $post['category'],
            'tags' => $post['tags'],
            'date' => $post['date'],
            'time' => $post['time'],
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'timestamp' => $timestamp,
            'withMessage' => $post['withMessage']
        ];
    }
}
