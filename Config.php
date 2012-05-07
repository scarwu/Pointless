<?php
/**
 * Core
 */
define('CORE', ROOT . 'Core' . SEPARATOR);
define('CORE_LIBRARY', CORE . 'Library' . SEPARATOR);
define('CORE_PLUGIN', CORE . 'Plugin' . SEPARATOR);

/**
 * Blog Path
 */
define('BLOG', ROOT . 'Blog' . SEPARATOR);

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

/**
 * UI Path
 */
if(file_exists(BLOG . 'UI'))
	define('UI', BLOG . 'UI' . SEPARATOR);
else
	define('UI', ROOT . 'UI' . SEPARATOR);

define('UI_SCRIPT', UI . 'Script' . SEPARATOR);
define('UI_TEMPLATE', UI . 'Template' . SEPARATOR);

define('UI_RESOURCE', UI . 'Resource' . SEPARATOR);
define('UI_RESOURCE_CSS', UI_RESOURCE . 'Css' . SEPARATOR);
define('UI_RESOURCE_JS', UI_RESOURCE . 'Js' . SEPARATOR);