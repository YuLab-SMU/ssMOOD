<template>
  <aside class="sidebar" :class="{ expanded: isExpanded }">
    <button @click="toggleSidebar" class="expandButton">
      {{ isExpanded ? '>>' : '<<' }}
    </button>
    <!-- 面板切换按钮列表 -->
    <div class="panel-buttons">
      <button
        v-for="(panel, index) in panels"
        :key="index"
        :class="{ active: panel.isVisible }"
        @click="switchPanel(index)"
      >
        {{ panel.title }}
      </button>
    </div>
    <div v-for="(panel, index) in panels" :key="index" class="panel" :class="{ hidden: !panel.isVisible }">
      <div class="panel-header">
        {{ panel.title }}
      </div>
      <div class="panel-content">
        <component :is="panel.component" />
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref,markRaw } from 'vue';
import PanelA from './panelNode.vue';
import PanelB from './panelDemo.vue';

const panels = ref([
  { title: '节点', component: markRaw(PanelA), isVisible: true },
  { title: '示例', component: markRaw(PanelB), isVisible: false },
]);

const isExpanded = ref(false);

const toggleSidebar = () => {
  isExpanded.value = !isExpanded.value;
};

const switchPanel = (index) => {
  panels.value.forEach((panel, idx) => {
    panel.isVisible = idx === index;
  });
};

</script>

<style scoped>
.sidebar {
  width: 300px; /* 初始宽度 */
  position: fixed;
  left: 0;
  top: 60px;
  bottom: 0;
  background-color: #f5f5f5; /* 背景颜色 */
  transition: width 0.3s; /* 平滑过渡效果 */
  overflow: hidden; /* 隐藏溢出的内容 */
}

.expanded {
  width: 30px; /* 收缩后的宽度 */
  height: 15px;
}
.expandButton{
  width: 30px; 
  height: 30px;
  border-radius: 5px; /* 圆角 */
  border: none;
}
/* 按钮悬停效果 */
.expandButton:hover {
  background-color: white; /* 渐变背景变深 */
  
}
.hidden {
  display: none;
}

.panel {
  border-bottom: 1px solid #ccc;
  padding: 10px;
}

.panel-header {
  cursor: pointer;
  color: #808080;
}




/* 按钮点击效果 */
.expandButton:active {
  transform: translateY(2px); /* 轻微下移 */
}

/* 面板切换按钮列表样式 */
.panel-buttons button {
  padding: 8px 15px;
  margin: 5px;
  border: none;
  border-radius: 4px; /* 圆角 */
  background-color: white; /* 背景色 */
  color: #333;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.1s; /* 过渡效果 */
}

/* 面板切换按钮悬停效果 */
.panel-buttons button:hover {
  background-color: #e9ecef; /* 背景色变深 */
  transform: translateY(-1px); /* 轻微上移 */
}

/* 面板切换按钮激活效果 */
.panel-buttons button.active {
  background-color: rgb(093, 116, 162); /* 激活时的背景色 */
  color: white;
}

/* 面板样式 */
.panel {
  border-radius: 8px; /* 圆角 */
  overflow: hidden; /* 隐藏溢出的内容 */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* 阴影 */
}


</style>