// src/config/index.js

// 开发环境配置
const devConfig = {
  apiUrl: 'http://172.16.165.250/ssmood3/php/',
  email:"xxxx@xxx.com"
};

// 生产环境配置
const prodConfig = {
  apiUrl: '../php/',
  email:"xxxx@xxx.com"
};

// 根据环境变量选择配置
const config = process.env.NODE_ENV === 'production' ? prodConfig : devConfig;

export default config;