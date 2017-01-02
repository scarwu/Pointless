<?php
/**
 * Theme Config
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
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
