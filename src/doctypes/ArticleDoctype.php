<?php
/**
 * Article Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://gTypeithub.com/scarwu/Pointless
 */

namespace Pointless\Doctype;

use Pointless\Library\Utility;
use Pointless\Extend\Doctype;

class ArticleDoctype extends Doctype
{
    public function __construct()
    {
        parent::__construct();

        $this->id = 'article';
        $this->name = 'Blog Article';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "],
            ['tag', "Enter Tag: (tag1|tag2|tag3...)\n-> "],
            ['category', "Enter Category:\n-> "]
        ];
    }

    public function inputHandleAndSaveFile($input)
    {
        $time = time();
        $filename = Utility::pathReplace($input['url']);
        $filename = date('Ymd_', $time) . "{$filename}.md";

        return $this->save([
            'filename' => $filename,
            'title' => $input['title'],
            'header' => [
                'type' => $this->id,
                'url' => Utility::pathReplace($input['url']),
                'tag' => $input['tag'],
                'category' => $input['category'],
                'date' => date('Y-m-d', $time),
                'time' => date('H:i:s', $time),
                'message' => true,
                'publish' => false
            ]
        ]);
    }

    public function postHandleAndGetResult($post)
    {
        $format = Resource::get('config')['post']['article']['format'];
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
        $post['tag'] = explode('|', $post['tag']);
        sort($post['tag']);

        // Summary and Description
        $summary = preg_replace('/<!--more-->(.|\n)*/', '', $post['content']);

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
            'tag' => $post['tag'],
            'date' => $post['date'],
            'time' => $post['time'],
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'timestamp' => $timestamp,
            'message' => $post['message']
        ];
    }
}
