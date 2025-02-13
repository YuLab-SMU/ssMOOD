<template>
  <div>
    <header>
      <nav>
        <div class="logo">
          <img src="@/assets/logo.png" alt="Logo">
        </div>
        <ul>
          <li>
            <router-link to="/">{{ $t('home') }}</router-link>
          </li>
          <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="has-submenu">
            <a>{{ $t('browse') }}</a>
            <!-- 二级菜单 -->
            <ul v-if="showSubMenu" class="submenu">
              <li>
                <router-link to="/browse/singlecell">{{ $t('browse-SingleCell') }}</router-link>
              </li>
              <li>
                <router-link to="/browse/spatialtranscriptome">{{ $t('browse-SpatialTranscriptome') }}</router-link>
              </li>
            </ul>
          </li>
          <li class="NowPage">
            <router-link to="/analyze">{{ $t('analyze') }}</router-link>
          </li>
          <li>
            <router-link to="/download">{{ $t('download') }}</router-link>
          </li>
          <li>
            <router-link to="/about">{{ $t('about') }}</router-link>
          </li>
        </ul>
        <LanguageSwitcher @languageChanged="onLanguageChanged" ></LanguageSwitcher>
      </nav>
    </header>

    <main>
      <section class="page-section">
    <div class="overlay-load" v-if="isLoading">
            <div class="loader">
      <div class="progress-bar"></div>
    </div>
    <div class="overlay-text">{{ $t('ap1') }}</div>
    <div class="overlay-text">{{ $t('ap2') }}</div>
    <div class="overlay-text">{{ $t('ap3') }}</div>

  </div>
  
    <div class="save-success" v-if="saveSuccess">{{ $t('ap4') }}</div>
 
        <div class="my-flow" @drop="onDrop">
          <VueFlow 
            :nodes="nodes"
            @update:nodes="updateNodes"
            :edges="edges"
            @update:edges="updateEdges"
            :node-components="nodeComponents"
            fit-view-on-init
            @edge-update="onEdgeUpdate"
            @connect="onConnect"
            @edge-update-start="onEdgeUpdateStart"
            @edge-update-end="onEdgeUpdateEnd"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
            :connectable="true" 
          >

            <template #node-dataset-select="props">
                <DatasetSelectNode
                    :id="props.id"
                    :data="props.data"
                    :newWebR="newWebR"
                    >
                </DatasetSelectNode>
            </template>
        
            <template #node-delay-node="props">
              <delayNode :id="props.id" :data="props.data"></delayNode>
            </template>
            <template #node-tips-node="props">
              <tipsNode :id="props.id" :data="props.data"></tipsNode>
            </template>
            <template #node-start-node="props">
              <StartNode :id="props.id" :data="props.data"></StartNode>
            </template>
            <template #node-webr-node="props">
                <webRPlotNode :id="props.id" :data="props.data" :newWebR="newWebR"></webRPlotNode>
             </template>
          
            <Panel class="node-panel" :position="PanelPosition.TopRight">
                <button @click="executeWorkflow" class="Panel-button" >{{ $t('ap5') }}</button>
                <button title="save graph" @click="onSave" class="Panel-button" >{{ $t('ap6') }}</button>
                <input type="file" accept=".json" @change="onRestore" ref="fileInput" style="display: none;" />
                <button title="restore graph" @click="triggerFileInput" class="Panel-button" >{{ $t('ap7') }}</button>
            </Panel>
           <!--<MiniMap />-->
            <Controls></Controls>
            
            
            <DropzoneBackground
            :style="{
              backgroundColor: isDragOver ? '#e7f3ff' : 'transparent',
              transition: 'background-color 0.2s ease',
            }"
          >
            <p v-if="isDragOver">Drop here</p>
            </DropzoneBackground>
            
          </VueFlow>
          <SidePanel></SidePanel>
        </div>
      </section>
    </main>
    <back-to-top></back-to-top>
  </div>
</template>

<script setup>
import { ref, onMounted,inject,onUnmounted} from 'vue';
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import { VueFlow, Panel, useVueFlow ,PanelPosition} from '@vue-flow/core';
//import { MiniMap } from '@vue-flow/minimap';
import { Controls } from '@vue-flow/controls'
import { useI18n } from 'vue-i18n';
import { v4 as uuidv4 } from 'uuid';
import { WebR } from 'webr';
import StartNode from './node/StartNode.vue';
import DatasetSelectNode from './node/DatasetSelectNode.vue'; 
import webRPlotNode from './node/webRPlotNode.vue'; 
import delayNode from './node/delayNode.vue'; 
import tipsNode from './node/tipsNode.vue'; 
import DropzoneBackground from './workflow/DropzoneBackground.vue';
import SidePanel from './workflow/SidePanel.vue'
import useDragAndDrop from './workflow/useDnD'

const isLoading = ref(true);


const { updateEdge, addEdges ,getConnectedEdges } = useVueFlow();
const { onDragOver, onDrop, onDragLeave, isDragOver } = useDragAndDrop()

// 创建 WebR 实例
const newWebR = new WebR();
const waitNodeId = ref(null);

// 异步初始化 WebR 实例
async function initializeWebR() {
  await newWebR.init(); // 假设 WebR 实例需要初始化
  console.log('WebR initialized');
  await newWebR.installPackages(['jsonlite'], true);
  isLoading.value = false;
}



const nodeComponents = {
  'start-node': StartNode, // used here
  'dataset-select': DatasetSelectNode, // used here
  'webr-node': webRPlotNode,
  'delay-node': delayNode,
  'tips-node': tipsNode
 
};

// 定义响应式数据
const showSubMenu = ref(false);
const nodes = ref([]);

const edges = ref([]);

  function updateNodes(updatedNodes) {
    nodes.value = updatedNodes;
    console.log('Nodes updated:', nodes.value);
  }

  function updateEdges(updatedEdges) {
    edges.value = updatedEdges;
    console.log('Edges updated:', edges.value);
  }

//----------
//连接边
//----------
 // eslint-disable-next-line
const onEdgeUpdateStart = (edge) => {
  //console.log('start update', edge);
};
 // eslint-disable-next-line
const onEdgeUpdateEnd = (edge) => {
  //console.log('end update', edge);
};

const onEdgeUpdate = ({ edge, connection }) => {
  updateEdge(edge, connection);
  //console.log('Edge updated:', edge, connection);
};


const onConnect = (params) => {
  // 为新边生成一个 UUID
  const newEdge = {
    ...params,
    id: uuidv4(), 
    animated: true,
  };
  console.log(newEdge);
  addEdges([newEdge]);
};
//----------
//保存和加载
//----------

const { toObject } = useVueFlow();

function onSave() {
  const flowData = JSON.stringify(toObject());
  const blob = new Blob([flowData], { type: 'application/json' });
  const url = URL.createObjectURL(blob);

  // 创建下载链接并自动触发下载
  const a = document.createElement('a');
  a.href = url;
  a.download = 'flow-data.json'; // 文件名
  a.click();

  // 释放 URL 对象
  URL.revokeObjectURL(url);
}
const { fromObject } = useVueFlow();

const fileInput = ref(null);

function triggerFileInput() {
      fileInput.value?.click();
    }
 function onRestore(event) {
      const file = event.target.files[0];
      if (!file) {
        console.error('No file selected!');
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        try {
          const flow = JSON.parse(e.target.result);
          //console.log('Flow loaded:', flow); // Debug: 查看加载的内容
          fromObject(flow); // 确保 `fromObject` 方法存在
        } catch (error) {
          console.error('Error parsing flow data:', error);
        }
      };
      reader.readAsText(file);
    }

const flowKey = 'vue-flow--save-restore'

function autoSave() {
  localStorage.setItem(flowKey, JSON.stringify(toObject()));
}

//每10s执行一次 autoSave 函数，自动保存
setInterval(autoSave, 10000);

function autoLoad() {
  const flow = JSON.parse(localStorage.getItem(flowKey))

  if (flow) {
    fromObject(flow)
  }
}

const isCtrlSPressed = ref(false);

// 定义一个方法来处理键盘按下事件
function handleSaveKeyPress(event) {
  const { key } = event;
  // 检查是否同时按下了 Ctrl 和 S 键
  if (key === 's' && event.ctrlKey) {
    isCtrlSPressed.value = true;
    // 阻止浏览器默认的保存网页行为
    event.preventDefault();
    localStorage.setItem(flowKey, JSON.stringify(toObject()));
    showSaveSuccess();
  }
}
const saveSuccess = ref(false); // 控制保存成功的提示是否显示

function showSaveSuccess() {
  saveSuccess.value = true; // 显示保存成功的提示
  setTimeout(() => { // 设置一个定时器，3秒后隐藏提示
    saveSuccess.value = false;
  }, 3000); // 3000毫秒后执行
}
//----------
//语言切换
//----------
const { locale } = useI18n();

// 处理语言切换
const onLanguageChanged = (language) => {
  locale.value = language; // 更新语言
  window.localStorage.setItem('selectedLanguage', language); // 可选：存储语言选择
};

onMounted(async() => {
  const selectedLanguage = window.localStorage.getItem('selectedLanguage') || 'zh1';
  locale.value = selectedLanguage; // 设置语言
  await initializeWebR();
  autoLoad();
  window.addEventListener('keydown', handleSaveKeyPress);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleSaveKeyPress);
});


//----------
//执行工作流
//----------
const eventBus = inject('eventBus');
// 执行工作流
const executeWorkflow = async() => {
  console.log('Executing workflow...');
  // 定义一个队列来存储待处理的节点
  const queue = [];
  // 定义一个集合来存储已访问的节点
  const visited = new Set();

  // 找到起始节点并将其加入队列
  const startNode = nodes.value.find(node => node.type === 'start-node');
  if (startNode) {
    queue.push(startNode);
    visited.add(startNode.id);
  }

  // 循环处理队列中的节点
  while (queue.length > 0) {
    // 从队列中取出一个节点
    const currentNode = queue.shift();
    console.log(`Processing node: ${currentNode.id}`);
    // 执行当前节点的业务逻辑
    if (currentNode.type === 'end') {
      // 执行结束节点的逻辑
      console.log('Workflow completed.');
    }
    else {
    await eventBus.emit('perform-action', currentNode.id);
    } 
    
    // 获取当前节点的所有邻居节点
    const edgesFromCurrent = getConnectedEdges(currentNode.id ,edges).filter((edge) => edge.source === currentNode.id);
    
    for (const edge of edgesFromCurrent) {
      const neighborNode = nodes.value.find(node => node.id === edge.target);
      if (neighborNode && !visited.has(neighborNode.id)) {
        queue.push(neighborNode);
        visited.add(neighborNode.id);
      }
    }
    await waitForNode(currentNode.id);
  }
};

const waitForNode = (id) => {
  return new Promise((resolve) => {
    const checkCondition = setInterval(() => {
      if (waitNodeId.value === id) {
        //console.log('执行成功');
        clearInterval(checkCondition);
        resolve();
      }
    }, 100); // 每 100ms 检查一次
  });
};
onMounted(() => {
  eventBus.on('task-completed', handleTaskCompletion);
  
});

const handleTaskCompletion = (data) => {
  waitNodeId.value = data; // 设置为 false，表示等待结束
  console.log("Node process completely:", waitNodeId.value);
  // 执行下一个节点的逻辑...
};

onUnmounted(() => {
  eventBus.off('task-completed', handleTaskCompletion);
});

// 更新节点状态
// eslint-disable-next-line
const updateNodeStatus = (nodesArray, status) => {
  nodesArray.forEach(node => {
    updateNodeData(node.id, { status });
  });
};

// 更新节点数据
const updateNodeData = (nodeId, newData) => {
  const node = nodes.value.find(n => n.id === nodeId);
  if (node) {
    const updatedNode = { ...node, ...newData };
    nodes.value = [...nodes.value.map(n => n.id === nodeId ? updatedNode : n)];
  }
};
</script>

<style scoped>
@import 'css/MainStyles.css';
@import "@vue-flow/core/dist/style.css";
@import "@vue-flow/core/dist/theme-default.css";
@import '@vue-flow/controls/dist/style.css';
@import '@vue-flow/minimap/dist/style.css';

.page-section {
    padding: 20px;
    min-height: 100vh;
    overflow: hidden;
}
.my-flow {
  width: 100%;
  height: calc(100vh - 120px); 
  background-color: #f0f0f0;
}
.node-panel {
  padding: 10px;
  background-color: #f9f9f9;
  border: 1px solid #ccc;
  border-radius: 4px;
  /* 其他面板样式 */
}

.node-button {
  margin: 5px;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: white;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.node-button:hover {
  background-color: #0056b3;
}

.Panel-button{
  margin: 5px;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  background-color: rgb(093, 116, 162);
  color: white;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.Panel-button:hover {
  background-color: rgba(093, 116, 162, 0.8);
}



.overlay-load {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(255, 255, 255); /* 半透明背景 */
  z-index: 10; /* 确保遮罩层在最上层 */
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.overlay-text {
  font-size: 20px; /* 根据需要调整字体大小 */
  color: #333; /* 文本颜色 */
}
.loader {
  position: relative;
  width: 100px;
  height: 100px;
}

.progress-bar {
  position: absolute;
  width: 100%;
  height: 4px;
  background-color: #ddd;
  animation: progress-bar-animation 1.5s ease-in-out infinite;
}

.progress-bar::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #3498db;
  animation: progress-bar-animation 1.5s ease-in-out infinite;
}

@keyframes progress-bar-animation {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}



aside {
  color: #fff;
  font-weight: 700;
  border-right: 1px solid #eee;
  padding: 15px 10px;
  font-size: 14px; /* 增加字体大小 */
  background-color: #f9f9f9;
  -webkit-box-shadow: 0px 5px 10px 0px rgba(0,0,0,.3);
  box-shadow: 0 5px 10px #0000004d;
  display: flex;
  flex-direction: column;
  gap: 10px; /* 设置子元素之间的间隙 */
  border-radius: 8px; /* 添加圆角 */
  overflow: hidden; /* 隐藏超出部分 */
}

aside .nodes {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 10px;
}

aside .nodes>* {
  cursor: grab;
  font-weight: 500;
  background: white; /* 添加半透明背景 */
  border: 1px solid black; /* 添加边框 */
  padding: 10px 15px; /* 增加内边距 */
  border-radius: 5px; /* 添加圆角 */
  transition: transform 0.3s ease-in-out; /* 添加过渡效果 */
}

aside .nodes>*:hover {
  transform: translateY(-5px); /* 鼠标悬停时上移 */
}
.save-success {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 10px 20px;
  color: #fff;
  background-color: #4CAF50;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  transition: opacity 0.5s ease-in-out;
  opacity: 1;
}
</style>
