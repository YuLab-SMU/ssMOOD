const path = require('path');
const { GitRevisionPlugin } = require('git-revision-webpack-plugin');
const gitRevisionPlugin = new GitRevisionPlugin();
const webpack = require('webpack');

module.exports = {
    configureWebpack: {
        plugins: [
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