<?php
/**
 * Constant
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

$constant = [
    'build' => [
        'version' => '5.0.0',
        'timestamp' => (true === defined('BUILD_TIMESTAMP'))
            ? BUILD_TIMESTAMP : time()
    ],
    'formats' => [
        'Article',
        'Describe'
    ]
];
