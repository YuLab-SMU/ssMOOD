// src/config/index.js

// Development config
const devConfig = {
  apiUrl: 'http://172.16.165.245/php/',
  email:"xxxx@xxx.com"
};

// Production config
const prodConfig = {
  apiUrl: './php/',
  email:"xxxx@xxx.com"
};

// Choose config based on environment variable
const config = process.env.NODE_ENV === 'production' ? prodConfig : devConfig;

export default config;