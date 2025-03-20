<template>
  <div 
    v-if="isVisible" 
    class="back-to-top" 
    @click="scrollToTop"
  >
    ▲
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineProps } from 'vue';

const props = defineProps({
  targetSelector: {
    type: String,
    default: 'main', // 默认目标为 `body`，可指定 `.Top-container`、`.scrollable-div` 等
  },
  scrollThreshold: {
    type: Number,
    default: 300, // 滚动触发的阈值，默认 300px
  },
});

const isVisible = ref(false);
let targetElement = null;

const scrollToTop = () => {
  if (targetElement) {
    targetElement.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const handleScroll = () => {
  if (targetElement) {
    isVisible.value = targetElement.scrollTop > props.scrollThreshold;
  }
};

onMounted(() => {
  targetElement = document.querySelector(props.targetSelector);

  if (targetElement) {
    targetElement.addEventListener('scroll', handleScroll);
  } else {
    console.warn(`Element with selector "${props.targetSelector}" not found.`);
  }
});

onUnmounted(() => {
  targetElement?.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: rgb(093, 116, 162);
  color: #fff;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  opacity: 0.8;
  transition: all 0.3s ease;
  z-index: 999; /* 确保按钮显示在其他元素上方 */
}

.back-to-top:hover {
  opacity: 1;
  transform: scale(1.1);
}
</style>
