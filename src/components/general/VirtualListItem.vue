<template>
  <div
    class="gene-item"
    :class="{ 'is-active': source.content === searchQuery }"
    @mousedown="selectItem(source.content)"
  >
    {{ source.content }}
  </div>
</template>

<script setup>
import { defineProps, inject } from 'vue';

 defineProps({
  source: Object,
  index: Number,
  searchQuery: String
});


//const emit = defineEmits(['select-item']);
const eventBus = inject('eventBus');
function selectItem(value) {
  //emit('select-item', value);
  eventBus.emit('select-item', value);
}


</script>

<style scoped>
.gene-item {
  height: 50px; /* 每个项的高度 */
  display: flex;
  align-items: center;
  padding: 0 12px;
  cursor: pointer;
}
.gene-item:hover {
  background-color: rgba(93, 116, 162,0.2);
}
.is-active {
  background-color: #e0e0e0;
}
</style>
