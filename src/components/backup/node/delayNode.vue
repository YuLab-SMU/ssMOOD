<template>
  <div class="delay-node">
    <label for="node-label" class="node-label">Delay Node</label>
    <label for="node-message" class="node-message">{{ message }}</label>
    <Handle type="source" :position="Position.Right" />
    <Handle type="target" :position="Position.Left" />
  </div>
</template>

<script setup>
import { ref, onUnmounted, onMounted, defineProps, inject } from 'vue';
import { Handle, Position } from '@vue-flow/core';

// Define the props to receive node data (data should be part of the node object)
const props = defineProps({
  id: String,
  data: Object,
});

// Create a reactive message ref
const message = ref("");

// Define the eventBus through inject
const eventBus = inject('eventBus');

// Handle the perform-action event
const handlePerformAction = async (nodeId) => {
  if (nodeId === props.id) {
    message.value = "Delay 5s";
    await new Promise((resolve) => {
      setTimeout(resolve, 5000);
    });
    eventBus.emit('task-completed', nodeId);
  }
};

onMounted(() => {
  if (eventBus) {
    eventBus.on('perform-action', handlePerformAction);
  }
});

onUnmounted(() => {
  if (eventBus) {
    eventBus.off('perform-action', handlePerformAction);
  }
});
</script>

<style scoped>
.delay-node {
  padding: 10px;
  border: 1px solid #000;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #f9f9f9;
}

.node-label {
  font-weight: bold;
}

.node-message {
  margin-top: 10px; /* 消息顶部外边距 */
  font-size: 8px; /* 设置字号为16像素，你可以根据需要调整这个值 */
  font-weight: normal; /* 确保文本不加粗 */
}
.vue-flow__handle {
    height:24px;
    width:8px;
    border-radius:4px
}
</style>