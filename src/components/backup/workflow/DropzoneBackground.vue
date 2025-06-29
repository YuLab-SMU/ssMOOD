<script setup>
import { Background } from '@vue-flow/background'
</script>

<template>
  <div class="dropzone-background">
    <Background :size="2" :gap="20" pattern-color="#BDBDBD" />

    <div class="overlay">
      <slot />
    </div>
  </div>
</template>
<style scoped>

.dropzone-background {
    position:relative;
    height:100%;
    width:100%
}

.dropzone-background .overlay {
    position:absolute;
    top:0;
    left:0;
    height:100%;
    width:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:1;
    pointer-events:none;
}
</style>