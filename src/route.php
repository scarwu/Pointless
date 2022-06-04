<?php
/**
 * Built-in Web Server Route
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

// Define Variables
define('APP_ENV', getenv('APP_ENV'));
define('APP_ROOT', getenv('APP_ROOT'));

// Load Phar
if ('production' === APP_ENV) {
    Phar::loadPhar(getenv('PHAR_FILE'), 'poi.phar');
}

// Load Bootstrap
require APP_ROOT . '/boot.php';
