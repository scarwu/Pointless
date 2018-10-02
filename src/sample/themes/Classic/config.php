<?php
/**
 * Theme Config
 *
 * @package     Pointless Theme - Classic
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Classic
 */

$config = [
    'extensions' => [
        'Atom',
        'Sitemap'
    ],
    'handlers' => [
        'Describe',
        'Article',
        'Page',
        'Archive',
        'Category',
        'Tag'
    ],
    'views' => [
        'container' => [
            'describe',
            'article',
            'page',
            'archive',
            'category',
            'tag'
        ],
        'side' => [
            'archive',
            'category',
            'tag'
        ]
    ]
];
