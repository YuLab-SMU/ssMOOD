<template>
  <div id="app">
    <router-view/>
      <HelloInfo/>
      <CopyrightFooter/>
  </div>

</template>
<script setup>
import { onMounted} from 'vue';
import config from '@/config';
import CopyrightFooter from './components/general/CopyrightFooter.vue'
//-------------------------------------------------------
//💻 终端欢迎信息输出
//项目使用git管理，欢迎信息中的版本号自动获取，为git的branch名
//email在config/index.js中定义
//-------------------------------------------------------
import HelloInfo from './components/general/HelloInfo.vue';

//------------------------------------------------------------------------------------------------------
//⚠️ 重写ResizeObserver函数，加入防抖，避免使用element-plus的元素出现警告：
//ResizeObserver loop completed with undelivered notifications.
//    at handleError (webpack-internal:///./node_modules/webpack-dev-server/client/overlay.js:251:58)
//    at eval (webpack-internal:///./node_modules/webpack-dev-server/client/overlay.js:270:7)
//-------------------------------------------------------------------------------------------------------
import debounce from 'lodash/debounce'
// 1. 保存原始构造函数
const NativeResizeObserver = window.ResizeObserver
// 2. 重写全局 ResizeObserver：回调加防抖
window.ResizeObserver = class extends NativeResizeObserver {
  constructor(callback) {
    super(debounce(callback, 100))
  }
}
//-------------------------------------------------------
//💻 访问统计
//-------------------------------------------------------
onMounted(() => {
  fetch(config.apiUrl+`log_visit.php`)
})

</script>
<style>

</style>