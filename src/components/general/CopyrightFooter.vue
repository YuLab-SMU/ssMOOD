<template>
  <div id="app">
    <router-view />

    <!-- 左下角悬浮气泡 -->
    <el-tooltip
      content="Copyright © 2025 Jinchen Li. All rights reserved."
      placement="right"
      effect="light"
    >
      <div class="floating-bubble">
        ⓘ
      </div>
    </el-tooltip>
  </div>
</template>

<style scoped>
.floating-bubble {
  position: fixed;
  bottom: 10px;
  left: 10px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: rgba(93, 116, 162, 0.3);
  color: white;
  font-size: 15px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  cursor: default;
  transition: transform 0.2s;
}

.floating-bubble:hover {
  transform: scale(1.1);
}
</style>
