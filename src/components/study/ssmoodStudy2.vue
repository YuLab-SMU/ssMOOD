<template>
  <div class="navigation-container">
    <div class="image-container">
      <img src="@/assets/Human-MDD.jpg" alt="Navigation Image" class="navigation-image" />
      <!-- 按钮 1 -->
      <button class="navigation-button button-1" @click="goToPage('study1.conditions.C')">
        BA9: 16C,17MDD
      </button>
      <!-- 按钮 2 -->
      <button class="navigation-button button-2" @click="goToPage('study1.conditions.3D')">
        BA46: 2C,2MDD
      </button>
      <!-- 按钮 3  7D -->
      <button class="navigation-button button-3" @click="goToPage('study1.conditions.7D')">
        BA12: 2C,2MDD
      </button>
    <!-- 按钮 4 -->
      <button class="navigation-button button-4" @click="goToPage('study1.conditions.S')">
        B11: 2C,3MDD
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPage = (study) => {
  router.push({ name: 'scsDetail', params: { study: study } });
};
</script>

<style scoped>
.navigation-container {
  position: relative;
  width: 400px; /* 根据图片大小调整 */
  height: auto; /* 根据图片大小调整 */
}
.image-container {
  position: relative;
  width: 100%;
  height: 100%;
}
.navigation-image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* 确保图片覆盖整个容器 */
}
.navigation-button {
  position: absolute;
  padding: 5px 10px;
  color: black;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 210px;
  height: 30px;
}
/* 鼠标悬浮时的效果 */
.navigation-button:hover {
  transform: scale(1.1); /* 放大按钮 */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* 添加阴影 */
}
.button-1 {
  background-color: #b3d9ff;
  top: 60px;
  left: 132px;
}
.button-2 {
    background-color: #ffccff;
  top: 108px;
  left: 132px;
}
.button-3 {
    background-color: #fff2e5;
  top: 156px;
  left: 132px;
}
.button-4 {
    background-color: #9fdf9f;
  top: 204px;
  left: 132px;
}
</style>