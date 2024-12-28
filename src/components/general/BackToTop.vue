<template>
  <transition name="fade">
    <div v-if="visible" class="back-to-top" @click="goToTop">
      <transition name="scale">
        <button>↑</button>
      </transition>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'BackToTop',
  data() {
    return {
      visible: false
    };
  },
  methods: {
    goToTop() {
      window.scrollTo(0, 0);
    },
    handleScroll() {
      this.visible = window.scrollY > 100; // 滚动超过100px时显示按钮
    }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll);
  }
};
</script>

<style scoped>
.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  cursor: pointer;
  padding: 10px;
  border-radius: 50%; /* 使div圆形 */
  background-color: rgba(93, 116, 162 ,1);
  color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  display: flex; /* 使按钮居中 */
  align-items: center;
  justify-content: center;
  width: 50px;  /* 设置一个具体的宽度 */
  height: 50px; /* 设置一个具体的高度，与宽度相同 */
}

.back-to-top:hover {
  background-color: rgba(93, 116, 162 ,0.8);
}

/* 定义按钮的样式 */
.back-to-top button {
  background: none;
  border: none;
  font-size: 24px;
  color: white;
  cursor: pointer;
  border-radius: 50%; /* 使按钮圆形 */
  padding: 0; /* 移除内边距 */
  width: 50px;  /* 设置一个具体的宽度 */
  height: 50px;
}

/* 定义淡入淡出的动画 */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

/* 定义缩放动画 */
.scale-enter-active, .scale-leave-active {
  transition: transform 0.3s ease;
}
.scale-enter, .scale-leave-to /* .scale-leave-active in <2.1.8 */ {
  transform: scale(0);
}
</style>