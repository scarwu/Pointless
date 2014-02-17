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

    // Article URL Format
	// :year, :month, :day
	// :hour, :minute, :second, :timestamp
	// :title, :url
    'article_url' => ':year/:month/:day/:url',

    'timezone' => 'Asia/Taipei',

    'github' => [
        'account' => NULL,
        'repo' => NULL,
        'branch' => NULL,
        'cname' => FALSE
    ],

    // Local Encoding - For console,
    // If your environment encoding is't utf-8 then modify this
    // Big5 | GBK | other
    'encoding' => NULL,

    'editor' => 'vi'
];