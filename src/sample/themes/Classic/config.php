<?php
/**
 * Theme Config
 *
 * @package     Pointless Theme - Classic
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Classic
 */

$theme = [
    'assets' => [
        'scripts' => [
            'main'
        ],
        'styles' => [
            'normalize',
            'solarized_dark',
            'main'
        ]
    ],
    'views' => [
        'side' => [
            'tag',
            'category',
            'archive'
        ]
    ],
    'handlers' => [
        'StaticPage',
        'Article',
        'Page',
        'Archive',
        'Category',
        'Tag'
    ],
    'extensions' => [
        'Atom',
        'Sitemap'
    ]
];
