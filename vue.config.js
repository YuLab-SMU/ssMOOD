const path = require('path');

module.exports = {
    parallel: false,
  transpileDependencies: [
    'vuetify'
  ],
  chainWebpack: (config) => {
    config.resolve.alias.set('vue', '@vue/compat');
    config.module
      .rule('vue')
      .use('vue-loader')
      .tap((options) => {
        return {
          ...options,
          compilerOptions: {
            compatConfig: {
              MODE: 2 // 设置兼容模式
            }
          }
        };
      });
  },
  publicPath: "./",
};