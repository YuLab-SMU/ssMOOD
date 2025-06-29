
<template>
  <div class="dataset-select-node">
    <h1>Get umap data</h1>
    <label class="showuuid nodrag">{{ props.id }}</label>
    <label for="datasetSelect" class="nomaltext">Select dataset</label>
    <select v-model="selectedDatasetId" @change="onDatasetChange" id="datasetSelect" class="nodrag">
      <option v-for="dataset in datasets" :key="dataset.id" :value="dataset.name" class="nodrag">
        {{ dataset.name }}
      </option>
    </select>
    <label for="userInput">R dataframe name</label>
    <input class="df-input nodrag" type="text" v-model="userInput" @input="onTextInput" id="userInput" placeholder="dataframe name">
    <Handle type="target" :position="Position.Left" />
    <Handle type="source" :position="Position.Right" />
  </div>
</template>

<script setup>
/* eslint-disable */ 
import { ref, onUnmounted,onMounted, defineProps, defineEmits ,inject } from 'vue';
import { Handle, Position } from '@vue-flow/core';
import pako from 'pako';

const props = defineProps({
  id: String,      // 节点 ID
  data: Object,    // 节点数据
  newWebR: Object  // WebR 实例
});

const emit = defineEmits(['dataset-selected', 'text-input-changed']);

// 本地状态
const selectedDatasetId = ref('');  // 当前选中的数据集 ID
const datasets = ref([]);           // 数据集列表
const userInput = ref('');          // 用户输入的文本

// 获取数据集列表
async function fetchDatasets() {
  try {
    const response = await fetch('../php/ap_getDatasetList.php'); 
    const data = await response.json();
    datasets.value = data.datasets || [];
  } catch (error) {
    console.error('Error fetching datasets:', error);
  }
}


const eventBus = inject('eventBus');
// 用户选择数据集时更新本地状态
function onDatasetChange() {
  //emit('dataset-selected', selectedDatasetId.value);  // 发射事件给父组件
  props.data.datasetsId = selectedDatasetId.value;
}

// 用户输入文本时更新本地状态并发射事件
function onTextInput() {
  //emit('text-input-changed', userInput.value, props.id);  // 发射事件给父组件，包括节点 ID
  props.data.dfName = userInput.value;
}
const handlePerformAction = async (nodeId) => {
    
  //console.log(nodeid);
  //console.log(props.id);
  if(nodeId == props.id){
      let jsonString = null;
      console.log('Node action performed for dataset ID:', selectedDatasetId.value);
      
        const params = new URLSearchParams({
          id: selectedDatasetId.value
        });
    try {
    // 发起 fetch 请求并获取响应
    const response = await fetch(`../php/ap_getumapdata.php?${params}`);

    // 确保请求成功
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    // 获取响应的 ArrayBuffer
    const arrayBuffer = await response.arrayBuffer();

    // 解压数据
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed); // 使用 pako 解压

    // 确保解压成功
    if (decompressed === null) {
      throw new Error('Decompression failed');
    }
    jsonString = new TextDecoder('utf-8').decode(decompressed);
    } catch (error) {
    console.error('Error fetching and processing data:', error);
  }

    // 将解压后的数据解码为字符串
    
    //let jsondata = JSON.stringify(jsonString);
    
    let jsonString2 = jsonString.replace(/\//g, ' ');
    //console.log(jsonString2);
    let result = await props.newWebR.evalR(`
library(jsonlite)
jsondata <- '${jsonString2}'
${userInput.value} <- fromJSON(jsondata)
`);
    console.log('Result of running `rnorm` from webR: ', result);
    eventBus.emit('task-completed', nodeId);
  }
};
onMounted(() => {
  fetchDatasets(); // 组件挂载时获取数据集列表
  if (eventBus) {
    eventBus.on('perform-action', handlePerformAction);
  }
  
  if(props.data.datasetsId!==null){
      selectedDatasetId.value = props.data.datasetsId;
  }
  if(props.data.dfName!==null){
       userInput.value = props.data.dfName;
  }
});
onUnmounted(() => {
  if (eventBus) {
    eventBus.off('perform-action', handlePerformAction);
  }
});
</script>

<style scoped>
@import './myNode.css';
.dataset-select-node {
  display: flex;
  flex-direction: column;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
}

select {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}

.df-input {
  width: 80%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}


</style>