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
  font-size: 14px; /* Larger font size */
  background-color: #f9f9f9;
  -webkit-box-shadow: 0px 5px 10px 0px rgba(0,0,0,.3);
  box-shadow: 0 5px 10px #0000004d;
  display: flex;
  flex-direction: column;
  gap: 10px; /* Gap between child elements */
  border-radius: 8px; /* Rounded corners */
  overflow: hidden; /* Hide overflow */
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
  background: white; /* Semi-transparent background */
  border: 1px solid #ffffff26; /* Add border */
  padding: 10px 15px; /* More padding */
  border-radius: 5px; /* Rounded corners */
  transition: transform 0.3s ease-in-out; /* Add transition effect */
}

aside .nodes>*:hover {
  transform: translateY(-5px); /* Lift up on hover */
}
.vue-flow__node-start-node{
    color: #42b983; 
}
.vue-flow__node-dataset-select {
  color: #42b983; /* Set the text color of a specific element */
}

.vue-flow__node-webr-node {
  color: #35495e; /* Set the text color of another element */
}

.vue-flow__node-delay-node {
  color: #f44336; /* Set the text color of another element */
}
</style>