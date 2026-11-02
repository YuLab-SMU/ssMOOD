<template>
  <aside class="sidebar" :class="{ expanded: isExpanded }">
    <button @click="toggleSidebar" class="expandButton">
      {{ isExpanded ? '>>' : '<<' }}
    </button>
    <!-- Panel toggle button list -->
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
  width: 300px; /* Initial width */
  position: fixed;
  left: 0;
  top: 60px;
  bottom: 0;
  background-color: #f5f5f5; /* Background color */
  transition: width 0.3s; /* Smooth transition */
  overflow: hidden; /* Hide overflowing content */
}

.expanded {
  width: 30px; /* Width when collapsed */
  height: 15px;
}
.expandButton{
  width: 30px; 
  height: 30px;
  border-radius: 5px; /* Rounded corners */
  border: none;
}
/* Button hover effect */
.expandButton:hover {
  background-color: white; /* Darken the gradient background */
  
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




/* Button click effect */
.expandButton:active {
  transform: translateY(2px); /* Shift down slightly */
}

/* Panel toggle button list styles */
.panel-buttons button {
  padding: 8px 15px;
  margin: 5px;
  border: none;
  border-radius: 4px; /* Rounded corners */
  background-color: white; /* Background color */
  color: #333;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.1s; /* Transition effect */
}

/* Panel toggle button hover effect */
.panel-buttons button:hover {
  background-color: #e9ecef; /* Darker background color */
  transform: translateY(-1px); /* Shift up slightly */
}

/* Panel toggle button active effect */
.panel-buttons button.active {
  background-color: rgb(093, 116, 162); /* Background color when active */
  color: white;
}

/* Panel styles */
.panel {
  border-radius: 8px; /* Rounded corners */
  overflow: hidden; /* Hide overflowing content */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Shadow */
}


</style>