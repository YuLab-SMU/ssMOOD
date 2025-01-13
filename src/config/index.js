// src/config/index.js

// 开发环境配置
const devConfig = {
  apiUrl: 'http://198.168.138.132/ssmood3/php/',
};

// 生产环境配置
const prodConfig = {
  apiUrl: '../php/',
};

// 根据环境变量选择配置
const config = process.env.NODE_ENV === 'production' ? prodConfig : devConfig;

export default config;