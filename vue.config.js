const path = require('path');
const { GitRevisionPlugin } = require('git-revision-webpack-plugin');
const gitRevisionPlugin = new GitRevisionPlugin();
const webpack = require('webpack');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    configureWebpack: {
        plugins: [
            new webpack.DefinePlugin({
                __VUE_OPTIONS_API__: JSON.stringify(true),
                __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
                __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false)
            }),
            new BundleAnalyzerPlugin(),
            new GitRevisionPlugin(),
            new webpack.DefinePlugin({
                'process.env': {
                    VERSION: JSON.stringify(gitRevisionPlugin.version()),
                    COMMITHASH: JSON.stringify(gitRevisionPlugin.commithash()),
                    BRANCH: JSON.stringify(gitRevisionPlugin.branch())
                }
            })
        ],
        optimization: {
            minimize: true,
            minimizer: [
                new TerserPlugin({
                    terserOptions: {
                        compress: {
                            drop_console: true,   // 删除 console.log
                            drop_debugger: true   // 删除 debugger
                        },
                        format: {
                            comments: false       // 删除注释
                        }
                    },
                    extractComments: false
                })
            ]
        }
    },
    parallel: false,
    transpileDependencies: [
        'vuetify'
    ],
    chainWebpack: (config) => {
        config.resolve.alias.set('vue', 'vue');
        config.module
            .rule('vue')
            .use('vue-loader')
            .tap((options) => {
                return {
                    ...options,
                };
            });
    },
    publicPath: "./",
};
