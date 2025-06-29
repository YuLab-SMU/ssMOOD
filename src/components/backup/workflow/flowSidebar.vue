<script setup>
import useDragAndDrop from './useDnD'

const { onDragStart } = useDragAndDrop()
</script>

<template>
  <aside>
    <div class="nodes">
        <div class="vue-flow__node-start-node" :draggable="true" @dragstart="onDragStart($event, 'start-node')">StartNode</div>
      <div class="vue-flow__node-dataset-select" :draggable="true" @dragstart="onDragStart($event, 'dataset-select')">getUmapDataFromSC</div>
      <div class="vue-flow__node-webr-node" :draggable="true" @dragstart="onDragStart($event, 'webr-node')">WebR Code</div>
      <div class="vue-flow__node-delay-node" :draggable="true" @dragstart="onDragStart($event, 'delay-node')">Delay5s</div>
    </div>
  </aside>
</template>

<style scoped>
aside {
  color: #fff;
  font-weight: 700;
  border-right: 1px solid #eee;
  padding: 15px 10px;
  font-size: 14px; /* 增加字体大小 */
  background-color: #f9f9f9;
  -webkit-box-shadow: 0px 5px 10px 0px rgba(0,0,0,.3);
  box-shadow: 0 5px 10px #0000004d;
  display: flex;
  flex-direction: column;
  gap: 10px; /* 设置子元素之间的间隙 */
  border-radius: 8px; /* 添加圆角 */
  overflow: hidden; /* 隐藏超出部分 */
}

aside .nodes {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 10px;
}

aside .nodes>* {
  cursor: grab;
  font-weight: 500;
  background: white; /* 添加半透明背景 */
  border: 1px solid #ffffff26; /* 添加边框 */
  padding: 10px 15px; /* 增加内边距 */
  border-radius: 5px; /* 添加圆角 */
  transition: transform 0.3s ease-in-out; /* 添加过渡效果 */
}

aside .nodes>*:hover {
  transform: translateY(-5px); /* 鼠标悬停时上移 */
}
.vue-flow__node-start-node{
    color: #42b983; 
}
.vue-flow__node-dataset-select {
  color: #42b983; /* 设置特定元素的文本颜色 */
}

.vue-flow__node-webr-node {
  color: #35495e; /* 设置另一个元素的文本颜色 */
}

.vue-flow__node-delay-node {
  color: #f44336; /* 设置另一个元素的文本颜色 */
}
</style>