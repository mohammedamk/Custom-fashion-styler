const path = require( 'path' );
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {

    mode: 'development',
    entry: {
        app: './js/index.js'
    },
    context: __dirname,
    resolve: {
        extensions: [ '.js', '.jsx', '.json' ],
        alias: {
            'vue': 'vue/dist/vue.js'
        }
    },
    output: {
        path: path.resolve( __dirname, 'dist/bundle/' ),
        filename: '[name].js',
        publicPath: '//localhost:9000/'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            ["env", {
                                "targets": {
                                    "chrome": "58",
                                    "ie": "11"
                                }
                            }]
                        ]
                    }
                }
            },
            {
                test: /\.(png|jpg|gif)$/,
                use: [
                    'file-loader'
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    'file-loader'
                ]
            },
            {
                test: /\.svg$/,
                oneOf: [
                    {
                        resourceQuery: /inline/,
                        use: [
                            'vue-svg-loader',
                        ],
                    },
                    {
                        loader: 'file-loader',
                    },
                ],
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },

            {
                test: /\.css|scss$/,
                use: [
                    "style-loader", "css-loader", "postcss-loader", 'sass-loader'
                ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
    ],
    devServer: {

        compress: true,
        port: 9000,
        headers: {
            "Access-Control-Allow-Origin": "*"
        },
        devMiddleware: {
        }
    }
};
