<?php
$config = [
    'blog' => [
        'name' => 'Pointless',
        'slogan' => 'A Useful Static Blog Generator',
        'footer' => 'Powerd By Pointless',
        'description' => '',
        'keywords' => '',

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
        'Atom',
        'Sitemap'
    ],

    'feed_quantity' => 5,
    'article_quantity' => 5,

    // :year, :month, :day
    // :hour, :minute, :second, :timestamp
    // :title, :url
    'article_url' => ':year/:month/:day/:url',

    // Reference: http://php.net/manual/en/timezones.php
    'timezone' => 'Etc/UTC',

    'github' => [
        'account' => null,
        'repo' => null,
        'branch' => null,
        'cname' => false
    ],

    // Reference: http://php.net/manual/en/function.iconv.php
    // Big5 | GBK | other => UTF-8
    'encoding' => null,

    'editor' => 'vi'
];
