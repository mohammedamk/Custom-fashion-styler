const path = require( 'path' );
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {

    mode: 'production',
    entry: {
        app: './js/index.js'
    },
    context: __dirname,
    resolve: {
        extensions: [ '.js', '.jsx', '.json' ],
        alias: {
            'vue': 'vue/dist/vue.min.js'
        }
    },
    output: {
        path: path.resolve( __dirname, 'dist/bundle/' ),
        filename: '[name].min.js'
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
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    "css-loader", "postcss-loader", 'sass-loader'
                ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin( {
            filename: "[name].min.css",
            chunkFilename: "[id].css"
        }),
    ],
    optimization: {
        minimizer: [
            new UglifyJsPlugin(),
            new CssMinimizerPlugin(),
        ]
    }
};
