
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
  id: String,      // Node ID
  data: Object,    // Node data
  newWebR: Object  // WebR instance
});

const emit = defineEmits(['dataset-selected', 'text-input-changed']);

// Local state
const selectedDatasetId = ref('');  // Currently selected dataset ID
const datasets = ref([]);           // Dataset list
const userInput = ref('');          // User input text

// Fetch dataset list
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
// Update local state when the user selects a dataset
function onDatasetChange() {
  //emit('dataset-selected', selectedDatasetId.value);  // Emit event to parent component
  props.data.datasetsId = selectedDatasetId.value;
}

// Update local state and emit event when the user types
function onTextInput() {
  //emit('text-input-changed', userInput.value, props.id);  // Emit event to parent component, including node ID
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
    // Send fetch request and get the response
    const response = await fetch(`../php/ap_getumapdata.php?${params}`);

    // Make sure the request succeeded
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    // Get the response ArrayBuffer
    const arrayBuffer = await response.arrayBuffer();

    // Decompress data
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed); // Decompress with pako

    // Make sure decompression succeeded
    if (decompressed === null) {
      throw new Error('Decompression failed');
    }
    jsonString = new TextDecoder('utf-8').decode(decompressed);
    } catch (error) {
    console.error('Error fetching and processing data:', error);
  }

    // Decode decompressed data into a string
    
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
  fetchDatasets(); // Fetch dataset list when the component mounts
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