<?php
/**
 * Blog Config
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

$config = [
    'name' => 'My Blog',
    'slogan' => 'Pointless - Static Blog Generator',
    'footer' => 'My Blog',
    'description' => '',

    // en | zh-tw | zh-cn | other
    'lang' => 'en',

    'withSSL' => false,
    'domainName' => 'localhost',
    'baseUrl' => '/',

    'author' => null,
    'email' => null,

    // Disqus Shortname
    'disqusShortname' => null,

    // Google Analytics - UA-xxxxxxxx-x
    'googleAnalytics' => null,

    'theme' => 'Classic',

    // Reference: http://php.net/manual/en/timezones.php
    'timezone' => 'Etc/UTC',

    // Reference: http://php.net/manual/en/function.iconv.php
    // UTF-8 | Big5 | GBK
    'encoding' => 'UTF-8',

    'editor' => 'vi',

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
        'target' => 'github',
        'setting' => [
            'github' => [
                'account' => null,
                'repo' => null,
                'branch' => null,
                'enableCname' => false
            ]
        ]
    ],
    'backup' => [
        'target' => 'github',
        'setting' => [
            'github' => [
                'account' => null,
                'repo' => null,
                'branch' => null
            ]
        ]
    ],

    // PHP Built-in Server for Blog preview & Markdown Editor
    'server' => [
        'host' => 'localhost',
        'port' => 3000
    ]
];
