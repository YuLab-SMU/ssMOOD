<template>
  <div class="navigation-container">
    <img
      src="@/assets/Human-MDD.png"
      alt="Navigation Image"
      class="navigation-image"
    />

    <!-- BA9 -->
    <div class="nav-overlay ba9">
      <span class="nav-label">BA9:</span>
      <button class="nav-btn" @click="goToPage('study1.conditions.C')">16Control</button>
      <button class="nav-btn" @click="goToPage('study1.conditions.17MDD')">17MDD</button>
    </div>

    <!-- BA46 -->
    <div class="nav-overlay ba46">
      <span class="nav-label">BA46:</span>
      <button class="nav-btn" @click="goToPage('study1.conditions.3D')">2Control</button>
      <button class="nav-btn" @click="goToPage('study1.conditions.2MDD')">2MDD</button>
    </div>

    <!-- BA12 -->
    <div class="nav-overlay ba12">
      <span class="nav-label">BA12:</span>
      <button class="nav-btn" @click="goToPage('study1.conditions.7D')">2Control</button>
      <button class="nav-btn" @click="goToPage('study1.conditions.2MDD')">2MDD</button>
    </div>

    <!-- BA11 -->
    <div class="nav-overlay ba11">
      <span class="nav-label">BA11:</span>
      <button class="nav-btn" @click="goToPage('study1.conditions.S')">2Control</button>
      <button class="nav-btn" @click="goToPage('study1.conditions.3MDD')">3MDD</button>
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
.ba9   { top: 10%; left: 35%; }
.ba46  { top: 30%; left: 35%; }
.ba12  { top: 50%; left: 35%; }
.ba11  { top: 70%; left: 35%; }


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