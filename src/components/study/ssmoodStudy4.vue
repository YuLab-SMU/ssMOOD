<template>
  <div class="navigation-container">
    <img
      src="@/assets/Human-MDD.png"
      alt="Navigation Image"
      class="navigation-image"
    />


    <!-- BA46 -->
    <div class="nav-overlay ba46">
      <button class="nav-btn" @click="goToPage('study4.BA46')">BA46</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study4.BA46.Control')">2Control</button>
      <button class="nav-btn" @click="goToPage('study4.BA46.MDD')">2MDD</button>
    </div>

    <!-- BA12 -->
    <div class="nav-overlay ba12">
      <button class="nav-btn" @click="goToPage('study4.BA12')">BA12</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study4.BA12.Control')">2Control</button>
      <button class="nav-btn" @click="goToPage('study4.BA12.MDD')">2MDD</button>
    </div>
    <button class="navigation-button button-11" @click="goToPage('study4.all')">
      All
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPage = (study) => {
  router.push({ name: 'stStudy', params: { study: study } });
};
</script>

<style scoped>
.navigation-container {
  position: relative;
  display: inline-block;   /* 让容器大小跟随图片 */
}

.navigation-image {
  display: block;
  width: 100%;             /* 或者固定宽高 */
  height: auto;
}

.nav-overlay {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.65); /* 半透明底，防止图片干扰文字 */
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;     /* 防止换行 */
}

/* 根据脑区在图上的实际位置微调 left / top */
.ba46  { top: 30%; left: 35%; }
.ba12  { top: 50%; left: 35%; }
.button-11{
  bottom: 90px;
  left: 45px;
}
.navigation-button {
  position: absolute;
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.4);
  /* 半透明背景 */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.navigation-button:hover {
  background-color: rgba(0, 0, 0, 0.6);
  /* 悬浮时背景颜色加深 */
  transform: scale(1.1);
  /* 放大按钮 */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  /* 添加阴影 */
}

.nav-btn {
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.4); /* 半透明背景 */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
/* 鼠标悬浮时的效果 */
.nav-btn:hover {
  background-color: rgba(0, 0, 0, 0.6); /* 悬浮时背景颜色加深 */
  transform: scale(1.1); /* 放大按钮 */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* 添加阴影 */
}
</style>