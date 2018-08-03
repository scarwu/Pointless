<?php
/**
 * Home Config
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

$config = [
    'blog' => [
        'name' => 'My Blog',
        'slogan' => 'Pointless - Static Blog Generator',
        'footer' => 'My Blog',
        'description' => '',

        'lang' => 'en', // en | zh-tw | zh-cn | other

        'dn' => 'localhost',
        'base' => '/',

        'author' => null,
        'email' => null,

        'disqus_shortname' => null, // Disqus Shortname
        'google_analytics' => null, // Google Analytics - UA-xxxxxxxx-x
    ],

    'theme' => 'Classic',

    'extension' => [
        'atom' => [
            'quantity' => 5
        ]
    ],

    'post' => [
        'article' => [
            // :year, :month, :day
            // :hour, :minute, :second, :timestamp
            // :title, :url
            'format' => ':year/:month/:day/:url',
            'quantity' => 5,
        ]
    ],

    'deploy' => [
        'github' => [
            'account' => null,
            'repo' => null,
            'branch' => null,
            'cname' => false
        ]
    ],

    // Reference: http://php.net/manual/en/timezones.php
    'timezone' => 'Etc/UTC',

    // Reference: http://php.net/manual/en/function.iconv.php
    // Big5 | GBK | other => UTF-8
    'encoding' => null,

    'editor' => 'vi',

    // PHP Built-in Server for Blog preview & Markdown Editor
    'server' => [
        'blog' => [
            'host' => 'localhost',
            'port' => 3000
        ],
        'editor' => [
            'host' => 'localhost',
            'port' => 3001
        ]
    ]
];
