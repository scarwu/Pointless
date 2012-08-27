<?php
/**
 * Blog Setting
 */

// Blog Name
define('BLOG_NAME', 'Pointless');

// Blog Slogan
define('BLOG_SLOGAN', 'The Static Blog Generator');

// Blog Footer
define('BLOG_FOOTER', 'Powerd By Pointless');

// Blog Language
// en | zh-tw | zh-cn | other
define('BLOG_LANG', 'en');

// Blog Path - If blog is't put on root then modify it
define('BLOG_PATH', '/');

// Blog Domain Name - For google search
define('BLOG_DNS', 'localhost');

// Blog Theme
define('BLOG_THEME', 'Classic');

/**
 * Article Setting
 */

// Article Quantity
define('ARTICLE_QUANTITY', 10);

// Article URL Format
// 0: date         - 1970/01/01
// 1: title        - welcome
// 2: date + title - 1970/01/01/welcome
define('ARTICLE_URL', 0);

/**
 * Other Setting
 */

// Disqus Shrotname
define('DISQUS_SHORTNAME', NULL);

// Google Analystic
define('GOOGLE_ANALYSTIC', NULL);

// Github CName
define('GITHUB_CNAME', NULL);

// Local Encoding - For console, If your environment encoding is't utf-8, and then modify this
// Big5 | GBK | other
define('LOCAL_ENCODING', NULL);
