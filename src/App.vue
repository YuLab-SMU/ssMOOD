<template>
  <div id="app">
    <router-view/>
      <HelloInfo/>
  </div>

</template>
<script setup>
import HelloInfo from './components/general/HelloInfo.vue';
</script>