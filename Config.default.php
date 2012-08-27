<?php
/**
 * Blog Path
 */
define('BLOG', ROOT . 'Blog' . SEPARATOR);

// Require Blog Config
if(file_exists(BLOG . 'Config.php'))
	require_once BLOG . 'Config.php';
else
	require_once ROOT . 'Sample' . SEPARATOR . 'Config.php';

// Markdown
define('BLOG_MARKDOWN', BLOG . 'Markdown' . SEPARATOR);
define('BLOG_MARKDOWN_ARTICLE', BLOG_MARKDOWN . 'Article' . SEPARATOR);
define('BLOG_MARKDOWN_BLOGPAGE', BLOG_MARKDOWN . 'BlogPage' . SEPARATOR);

// Public
define('BLOG_PUBLIC', BLOG . 'Public' . SEPARATOR);
define('BLOG_PUBLIC_ARTICLE', BLOG_PUBLIC . 'article' . SEPARATOR);
define('BLOG_PUBLIC_CATEGORY', BLOG_PUBLIC . 'category' . SEPARATOR);
define('BLOG_PUBLIC_TAG', BLOG_PUBLIC . 'tag' . SEPARATOR);
define('BLOG_PUBLIC_PAGE', BLOG_PUBLIC . 'page' . SEPARATOR);
define('BLOG_PUBLIC_ARCHIVE', BLOG_PUBLIC . 'archive' . SEPARATOR);

// Resource
define('BLOG_RESOURCE', BLOG . 'Resource' . SEPARATOR);

// Theme Path
if(file_exists(BLOG . 'Theme'))
	define('THEME', BLOG . 'Theme' . SEPARATOR);
else
	define('THEME', ROOT . 'Theme' . SEPARATOR);

define('THEME_SCRIPT', THEME . SEPARATOR . BLOG_THEME . SEPARATOR . 'Script' . SEPARATOR);
define('THEME_TEMPLATE', THEME . SEPARATOR . BLOG_THEME . SEPARATOR . 'Template' . SEPARATOR);

define('THEME_RESOURCE', THEME . SEPARATOR . BLOG_THEME . SEPARATOR . 'Resource' . SEPARATOR);
define('THEME_RESOURCE_CSS', THEME_RESOURCE . 'Css' . SEPARATOR);
define('THEME_RESOURCE_JS', THEME_RESOURCE . 'Js' . SEPARATOR);