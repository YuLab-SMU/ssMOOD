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

/* New class that adds a thick green border */
.start-node-green-border {
  border: 4px solid #90EE90; /* Soft green, thick border */
  animation: hideBorder 5s forwards; /* Hide the border after 5 seconds */
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
  margin-top: 10px; /* Top margin of the message */
  font-size: 6px; /* Font size 16px; adjust as needed */
  font-weight: normal; /* Ensure text is not bold */
}

.vue-flow__handle {
    height:24px;
    width:8px;
    border-radius:4px
}
</style>