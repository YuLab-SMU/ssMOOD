<template>
    <div class="nodes">
        <div class="vue-flow-node" :draggable="true" @dragstart="onDragStart($event, 'start-node')">StartNode</div>
        <div class="vue-flow-node" :draggable="true" @dragstart="onDragStart($event, 'dataset-select')">getUmapDataFromSC</div>
        <div class="vue-flow-node" :draggable="true" @dragstart="onDragStart($event, 'webr-node')">WebR Code</div>
        <div class="vue-flow-node" :draggable="true" @dragstart="onDragStart($event, 'delay-node')">Delay5s</div>
        <div class="vue-flow-node" :draggable="true" @dragstart="onDragStart($event, 'tips-node')">TipsNode</div>
    </div>
</template>

<script setup>
import useDragAndDrop from './useDnD'


const { onDragStart } = useDragAndDrop()

</script>

<style scoped>
.nodes {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.vue-flow-node {
  padding: 20px;
  border: 1px solid #ccc;
  margin: 10px;
  color: #808080;
  align-items: center;
  justify-content: space-between;
  display: flex;
  width:100%;
  
}
.vue-flow-node:hover {
  background-color: white; /* 渐变背景变深 */
  transform: translateY(-2px); /* 轻微上移 */
  
}
</style>