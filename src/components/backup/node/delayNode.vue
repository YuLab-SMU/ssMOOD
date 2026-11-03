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
  margin-top: 10px; /* Top margin of the message */
  font-size: 8px; /* Font size 16px; adjust as needed */
  font-weight: normal; /* Ensure text is not bold */
}
.vue-flow__handle {
    height:24px;
    width:8px;
    border-radius:4px
}
</style>