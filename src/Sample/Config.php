<?php

$config => [
	// Blog Name
	'blog_name' => 'Pointless',

	// Blog Slogan
	'blog_slogan' => 'The Static Blog Generator',

	// Blog Description
	'blog_desciption' => '',

	// Blog Key Words
	'blog_keywords' => '',

	// Blog Footer
	'blog_footer' => 'Powerd By Pointless',

	// Blog Language
	// en | zh-tw | zh-cn | other
	'blog_lang' => 'en',

	// Blog Base - If blog is't put on root then modify this
	'blog_base' => '/',

	// Blog Domain Name - For google search
	'blog_dn' => 'localhost',

	// Blog Theme
	'blog_theme' => 'Classic',

	// Author Name
	'author_name' => NULL,

	// Author Email
	'author_name' => NULL,

	// Feed Article Quantity
	'feed_quantity' => 5,

	// Article Quantity
	'article_quantity' => 10,

	// Article URL Format
	// :year, :month, :day
	// :hour, :minute, :second, :timestamp
	// :title, :url
	'article_url' => ':year/:month/:day/:url',

	// Disqus Shortname
	'disqus_shortname' => NULL,

	// Google Analytics - UA-xxxxxxxx-x
	'google_analytics' => NULL,

	// Github Deployment Setting
	'github_account' => NULL,
	'github_repo' => NULL,
	'github_branch' => NULL,
	'github_cname' => FALSE,

	// Local Encoding - For console,
	// If your environment encoding is't utf-8 then modify this
	// Big5 | GBK | other
	'local_encoding' => NULL,

	// Default file editor
	'editor' => 'vi',

	// Time Zone
	'timezone' => 'Asia/Taipei'
];