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

        'author' => NULL,
        'email' => NULL,
        
        'disqus_shortname' => NULL, // Disqus Shortname
        'google_analytics' => NULL, // Google Analytics - UA-xxxxxxxx-x
    ],

    'theme' => 'Classis',

    'feed_quantity' => 5,
    'article_quantity' => 5,

	// :year, :month, :day
	// :hour, :minute, :second, :timestamp
	// :title, :url
    'article_url' => ':year/:month/:day/:url',

    // Reference: http://php.net/manual/en/timezones.php
    'timezone' => 'Etc/UTC',

    'github' => [
        'account' => NULL,
        'repo' => NULL,
        'branch' => NULL,
        'cname' => FALSE
    ],

    // Reference: http://php.net/manual/en/function.iconv.php
    // Big5 | GBK | other => UTF-8
    'encoding' => NULL,

    'editor' => 'vi'
];