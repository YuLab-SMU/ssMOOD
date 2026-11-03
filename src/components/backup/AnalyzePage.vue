<template>
  <div class="main">
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
            <!-- Submenu -->
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
import { Controls } from '@vue-flow/controls';
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

// Create WebR instance
const newWebR = new WebR();
const waitNodeId = ref(null);

// Initialize WebR instance asynchronously
async function initializeWebR() {
  await newWebR.init(); // Assume the WebR instance needs initialization
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

// Define reactive data
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
//Connect edges
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
  // Generate a UUID for the new edge
  const newEdge = {
    ...params,
    id: uuidv4(), 
    animated: true,
  };
  console.log(newEdge);
  addEdges([newEdge]);
};
//----------
//Save and load
//----------

const { toObject } = useVueFlow();

function onSave() {
  const flowData = JSON.stringify(toObject());
  const blob = new Blob([flowData], { type: 'application/json' });
  const url = URL.createObjectURL(blob);

  // Create a download link and trigger the download automatically
  const a = document.createElement('a');
  a.href = url;
  a.download = 'flow-data.json'; // File name
  a.click();

  // Release the URL object
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
          //console.log('Flow loaded:', flow); // Debug: inspect loaded content
          fromObject(flow); // Make sure the `fromObject` method exists
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

//Run autoSave every 10s for automatic saving
setInterval(autoSave, 10000);

function autoLoad() {
  const flow = JSON.parse(localStorage.getItem(flowKey))

  if (flow) {
    fromObject(flow)
  }
}

const isCtrlSPressed = ref(false);

// Handler for keydown events
function handleSaveKeyPress(event) {
  const { key } = event;
  // Check whether Ctrl and S are pressed together
  if (key === 's' && event.ctrlKey) {
    isCtrlSPressed.value = true;
    // Prevent the browser's default save-page behavior
    event.preventDefault();
    localStorage.setItem(flowKey, JSON.stringify(toObject()));
    showSaveSuccess();
  }
}
const saveSuccess = ref(false); // Controls whether the save-success message is shown

function showSaveSuccess() {
  saveSuccess.value = true; // Show the save-success message
  setTimeout(() => { // Set a timer to hide the message after 3 seconds
    saveSuccess.value = false;
  }, 3000); // Runs after 3000 ms
}
//----------
//Language switching
//----------
const { locale } = useI18n();

// Handle language switch
const onLanguageChanged = (language) => {
  locale.value = language; // Update language
  window.localStorage.setItem('selectedLanguage', language); // Optional: persist the language choice
};

onMounted(async() => {
  const selectedLanguage = window.localStorage.getItem('selectedLanguage') || 'zh1';
  locale.value = selectedLanguage; // Set language
  await initializeWebR();
  autoLoad();
  window.addEventListener('keydown', handleSaveKeyPress);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleSaveKeyPress);
});


//----------
//Run workflow
//----------
const eventBus = inject('eventBus');
// Run workflow
const executeWorkflow = async() => {
  console.log('Executing workflow...');
  // Queue of nodes to process
  const queue = [];
  // Set of visited nodes
  const visited = new Set();

  // Find the start node and enqueue it
  const startNode = nodes.value.find(node => node.type === 'start-node');
  if (startNode) {
    queue.push(startNode);
    visited.add(startNode.id);
  }

  // Process nodes in the queue
  while (queue.length > 0) {
    // Dequeue a node
    const currentNode = queue.shift();
    console.log(`Processing node: ${currentNode.id}`);
    // Run the current node's logic
    if (currentNode.type === 'end') {
      // Run the end node's logic
      console.log('Workflow completed.');
    }
    else {
    await eventBus.emit('perform-action', currentNode.id);
    } 
    
    // Get all neighbors of the current node
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
        //console.log('Execution succeeded');
        clearInterval(checkCondition);
        resolve();
      }
    }, 100); // Check every 100ms
  });
};
onMounted(() => {
  eventBus.on('task-completed', handleTaskCompletion);
  
});

const handleTaskCompletion = (data) => {
  waitNodeId.value = data; // Set to false to indicate waiting is over
  console.log("Node process completely:", waitNodeId.value);
  // Run the next node's logic...
};

onUnmounted(() => {
  eventBus.off('task-completed', handleTaskCompletion);
});

// Update node status
// eslint-disable-next-line
const updateNodeStatus = (nodesArray, status) => {
  nodesArray.forEach(node => {
    updateNodeData(node.id, { status });
  });
};

// Update node data
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


.main{
    overflow: hidden;
}
.page-section {
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
  /* Other panel styles */
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
  background-color: rgb(255, 255, 255); /* Semi-transparent background */
  z-index: 10; /* Keep the overlay on top */
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.overlay-text {
  font-size: 20px; /* Adjust font size as needed */
  color: #333; /* Text color */
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
  font-size: 14px; /* Larger font size */
  background-color: #f9f9f9;
  -webkit-box-shadow: 0px 5px 10px 0px rgba(0,0,0,.3);
  box-shadow: 0 5px 10px #0000004d;
  display: flex;
  flex-direction: column;
  gap: 10px; /* Gap between child elements */
  border-radius: 8px; /* Rounded corners */
  overflow: hidden; /* Hide overflow */
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
  background: white; /* Semi-transparent background */
  border: 1px solid black; /* Add border */
  padding: 10px 15px; /* More padding */
  border-radius: 5px; /* Rounded corners */
  transition: transform 0.3s ease-in-out; /* Add transition effect */
}

aside .nodes>*:hover {
  transform: translateY(-5px); /* Lift up on hover */
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
