<template>
  <div class="navigation-container">
    <div class="image-container">
      <img src="@/assets/Mouse-CSDS.png" alt="Navigation Image" class="navigation-image" />
      <!-- 按钮 1 -->
      <button class="navigation-button button-1" @click="goToPage('study1.conditions.C')">
        Control
      </button>
      <!-- 按钮 2 -->
      <button class="navigation-button button-2" @click="goToPage('study1.conditions.3D')">
        3Day
      </button>
      <!-- 按钮 3  7D -->
      <button class="navigation-button button-3" @click="goToPage('study1.conditions.7D')">
        7Day
      </button>
    <!-- 按钮 4 -->
      <button class="navigation-button button-4" @click="goToPage('study1.conditions.S')">
        Sus
      </button>
          <!-- 按钮 5 -->
      <button class="navigation-button button-5" @click="goToPage('study1.conditions.R')">
        Res
      </button>
      
        <!-- 按钮 6 -->
      <button class="navigation-button button-6" @click="goToPage('page3')">
        mPFC
      </button>
        <!-- 按钮 7 -->
      <button class="navigation-button button-7" @click="goToPage('page3')">
        Stri
      </button>
        <!-- 按钮 8 -->
      <button class="navigation-button button-8" @click="goToPage('page3')">
        HIP
      </button>
        <!-- 按钮 9 -->
      <button class="navigation-button button-9" @click="goToPage('page3')">
        VTA
      </button>
        <!-- 按钮 10 -->
      <button class="navigation-button button-10" @click="goToPage('page3')">
        DRN
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
  background-color: rgba(0, 0, 0, 0.4); /* 半透明背景 */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
/* 鼠标悬浮时的效果 */
.navigation-button:hover {
  background-color: rgba(0, 0, 0, 0.6); /* 悬浮时背景颜色加深 */
  transform: scale(1.1); /* 放大按钮 */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* 添加阴影 */
}
.button-1 {
  top: 65px;
  left: 60px;
}
.button-2 {
  top: 13px;
  left: 115px;
}
.button-3 {
  top: 65px;
  left: 160px;
}
.button-4 {
  top: 20px;
  right: 5px;
}
.button-5 {
  top: 65px;
  right: 5px;
}
.button-6 {
  bottom: 90px;
  right: 200px;
}
.button-7 {
  bottom: 90px;
  right: 115px;
}
.button-8 {
  bottom: 90px;
  right: 15px;
}
.button-9 {
  bottom: 35px;
  right: 165px;
}
.button-10 {
  bottom: 35px;
  right: 52px;
}
</style>