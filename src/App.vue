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
//💻 Terminal welcome message
//The project is managed with git; the version in the welcome message is fetched automatically from the git branch name
//email is defined in config/index.js
//-------------------------------------------------------
import HelloInfo from './components/general/HelloInfo.vue';

//------------------------------------------------------------------------------------------------------
//⚠️ Override ResizeObserver with debouncing to avoid warnings from element-plus components:
//ResizeObserver loop completed with undelivered notifications.
//    at handleError (webpack-internal:///./node_modules/webpack-dev-server/client/overlay.js:251:58)
//    at eval (webpack-internal:///./node_modules/webpack-dev-server/client/overlay.js:270:7)
//-------------------------------------------------------------------------------------------------------
import debounce from 'lodash/debounce'
// 1. Save the original constructor
const NativeResizeObserver = window.ResizeObserver
// 2. Override the global ResizeObserver: debounce the callback
window.ResizeObserver = class extends NativeResizeObserver {
  constructor(callback) {
    super(debounce(callback, 100))
  }
}
//-------------------------------------------------------
//💻 Visit statistics
//-------------------------------------------------------
onMounted(() => {
  fetch(config.apiUrl+`log_visit.php`)
})

</script>
<style>

</style>