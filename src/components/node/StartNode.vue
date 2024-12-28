<template>
  <div class="start-node" :class="{ 'start-node-green-border': hasGreenBorder }">
    <label for="node-label" class="node-label">{{ $t('apn-sn1') }}</label>
    <label for="node-message" class="node-message">{{ message }}</label>
    <Handle type="source" :position="Position.Right" />
  </div>
</template>

<script setup>
import { ref, onUnmounted, onMounted, defineProps, inject } from 'vue';
import { Handle, Position } from '@vue-flow/core';
const hasGreenBorder = ref(false);
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
const handlePerformAction = async(nodeId) => {
   
  if (nodeId === props.id) {
    hasGreenBorder.value = true;
    message.value = "Welcome to ssMOOD workflow";
    hasGreenBorder.value = false;
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
.start-node {
  padding: 10px;
  border: 1px;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #f9f9f9;
}

/* 定义一个新类，用于添加粗绿色边框 */
.start-node-green-border {
  border: 4px solid #90EE90; /* 柔和绿色，粗边框 */
  animation: hideBorder 5s forwards; /* 5秒后隐藏边框 */
}
@keyframes hideBorder {
  to {
    border-width: 0;
  }
}

.node-label {
  font-weight: bold;
}

.node-message {
  margin-top: 10px; /* 消息顶部外边距 */
  font-size: 6px; /* 设置字号为16像素，你可以根据需要调整这个值 */
  font-weight: normal; /* 确保文本不加粗 */
}

.vue-flow__handle {
    height:24px;
    width:8px;
    border-radius:4px
}
</style>