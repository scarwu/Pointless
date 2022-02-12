<?php
/**
 * Built-in Web Server Route
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

define('APP_ENV', getenv('APP_ENV'));
define('APP_ROOT', getenv('APP_ROOT'));

if (getenv('PHAR_FILE')) {
    Phar::loadPhar(getenv('PHAR_FILE'), 'poi.phar');
}

require APP_ROOT . '/boot/web.php';
