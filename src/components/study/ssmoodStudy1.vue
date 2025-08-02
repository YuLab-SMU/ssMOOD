<template>
  <div class="navigation-container">
    <div class="image-container">
      <img src="@/assets/Mouse-CSDS.png" alt="Navigation Image" class="navigation-image" />
      <!-- 按钮 1 -->
      <button class="navigation-button button-1" @click="goToPage('study1.conditions.0D')">
        0Day(Control)
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
      <button class="navigation-button button-6" @click="goToPage('study1.area.I')">
        I
      </button>
        <!-- 按钮 7 -->
      <button class="navigation-button button-7" @click="goToPage('study1.area.II')">
        II
      </button>
        <!-- 按钮 8 -->
      <button class="navigation-button button-8" @click="goToPage('study1.area.III')">
        III
      </button>
        <!-- 按钮 9 -->
      <button class="navigation-button button-9" @click="goToPage('study1.area.IV')">
        IV
      </button>
        <!-- 按钮 10 -->
      <button class="navigation-button button-10" @click="goToPage('study1.area.V')">
        V
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPage = (study) => {
  router.push({ name: 'scStudy', params: { study: study } });
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
  left: 40px;
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
  left: 280px;
}
.button-5 {
  top: 65px;
  left: 280px;
}
.button-6 {
  bottom: 90px;
  right: 220px;
}
.button-7 {
  bottom: 90px;
  right: 123px;
}
.button-8 {
  bottom: 90px;
  right: 23px;
}
.button-9 {
  bottom: 35px;
  right: 170px;
}
.button-10 {
  bottom: 35px;
  right: 60px;
}

/* ----------------------------------------------------------- */
/* 移动端响应 */
@media (max-width: 800px) {
  .navigation-button {
    display: none;
  }
}
</style>