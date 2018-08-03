<?php
/**
 * Theme Config
 *
 * @package     Pointless Theme - Unique
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/PointlessTheme-Unique
 */

$config = [
    'assets' => [
        'scripts' => [
            'theme'
        ],
        'styles' => [
            'theme'
        ]
    ],
    'views' => [
        'side' => [
            'about',
            'archive',
            'tag'
        ]
    ],
    'handlers' => [
        'About',
        'StaticPage',
        'Article',
        'Page',
        'Archive',
        'Tag'
    ],
    'extensions' => [
        'Atom',
        'Sitemap'
    ]
];
