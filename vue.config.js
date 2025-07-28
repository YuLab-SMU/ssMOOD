const path = require('path');
const { GitRevisionPlugin } = require('git-revision-webpack-plugin');
const gitRevisionPlugin = new GitRevisionPlugin();
const webpack = require('webpack');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

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
                'process.env.VERSION': JSON.stringify(gitRevisionPlugin.version()), // 获取版本号
                'process.env.COMMITHASH': JSON.stringify(gitRevisionPlugin.commithash()), // 获取提交哈希
                'process.env.BRANCH': JSON.stringify(gitRevisionPlugin.branch()), // 获取分支名
            })
        ]
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