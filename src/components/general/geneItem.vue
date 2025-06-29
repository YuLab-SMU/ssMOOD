<template>
  <div
    class="gene-item"
    :class="{ 'is-active': source.content === searchQuery }"
    @mousedown="selectItem(source)"
  >
    {{ source.content }}
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
// eslint-disable-next-line
const props = defineProps({
  source: Object,
  index: Number,
});

const emit = defineEmits(['selectItem']);

const selectItem = (item) => {
  emit('selectItem', item);
};
</script>

<style scoped>
.gene-item {
  padding: 8px;
  cursor: pointer;
}
.gene-item.is-active {
  background-color: #f0f0f0;
}
</style>