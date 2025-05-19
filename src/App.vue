<template>
  <div id="app">
    <router-view/>
      <HelloInfo/>
  </div>

</template>
<script setup>
import HelloInfo from './components/general/HelloInfo.vue';
//项目使用git管理，欢迎信息中的版本号自动获取，为git的branch名
//email在config/index.js中定义
</script>