'use strict';
/**
 * Webpack Config
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

var path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        main: './src/editor/boot/scripts/main.jsx'
    },
    output: {
        filename: '[name].min.js'
    },
    resolve: {
        modules: [
            path.resolve('./src/editor/boot/scripts'),
            'node_modules'
        ],
        extensions: [
            '.js',
            '.jsx'
        ]
    },
    externals: {

    },
    module: {
        rules: [
            {
                test: /.jsx$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        query: {
                            cacheDirectory: true,
                            plugins: [
                                'transform-class-properties'
                            ],
                            presets: [
                                'stage-3',
                                'stage-2',
                                'stage-1',
                                'stage-0',
                                'es2017',
                                'es2016',
                                'es2015',
                                'react'
                            ]
                        }
                    }
                ]
            }
        ]
    }
}
