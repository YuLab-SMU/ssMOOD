<template>
  <div class="webr-plot-node">

    <label for="webraceEditor">Edit R Code:</label>
    <div class="nodrag" ref="webraceEditor" style="height: 300px; width: 600px;"></div>

    <canvas
      draggable="false"
      :id="canvasId"
      width="600px"
      height="600px"
      class="nodrag"
      style="border: 2px solid #5d74a2; border-radius: 10px; margin-top: 10px;"
    ></canvas>

    <Handle type="target" :position="Position.Left" />
    <Handle type="source" :position="Position.Right" />
  </div>
</template>

<script setup>
import { ref, onMounted,onUnmounted, defineProps, inject } from 'vue';
import ace from 'ace-builds';
import 'ace-builds/src-noconflict/mode-r';
import 'ace-builds/src-noconflict/theme-monokai';
import { Handle, Position } from '@vue-flow/core';

const props = defineProps({
  id: String,      // 节点 ID
  data: Object,    // 节点数据
  newWebR: Object  // WebR 实例
});

const webraceEditor = ref(null);

const canvasId = props.id; // Use the passed id as the canvas ID
const code = ref('');
const editor = ref(null);
const eventBus = inject('eventBus');
// Initialize the ACE editor with R mode and theme
onMounted(() => {
    if (props.data.rcode!== null) {
        code.value = props.data.rcode;
    }
    console.log(props.data.rcode);
    console.log(props.data.rcode);
    editor.value = ace.edit(webraceEditor.value);
    editor.value.session.setMode('ace/mode/r');
    editor.value.setTheme('ace/theme/monokai');
    editor.value.setValue(code.value);
    editor.value.clearSelection();
    editor.value.session.on('change', () => {
        code.value = editor.value.getValue();
        // eslint-disable-next-line
        props.data.rcode = code.value; //默认不允许该方法，需要禁用eslint
    });
    if (eventBus) {
        eventBus.on('perform-action', handlePerformAction);
    }

});
onUnmounted(() => {
  if (eventBus) {
    eventBus.off('perform-action', handlePerformAction);
  }
});


const handlePerformAction = async (nodeId) => {
  //console.log(nodeid);
  //console.log(props.id);
  if(nodeId == props.id){
    await executeCode();
    eventBus.emit('task-completed', nodeId);
  }
};
// Execute the R code when the button is clicked
async function executeCode() {
  const canvas = document.getElementById(canvasId);
  canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas before plotting
  try {
    await props.newWebR.evalRVoid(`
webr::canvas()
${code.value}
dev.off()
`);

    const msgs = await props.newWebR.flush();
    msgs.forEach((msg) => {
      if (msg.type === 'canvas' && msg.data.event === 'canvasImage') {
        const canvas = document.getElementById(canvasId);
        canvas.getContext('2d').drawImage(msg.data.image, 0, 0, canvas.width, canvas.height);
      }
    });
  } catch (error) {
    console.error('Error executing R code:', error);
  }
  
}


</script>

<style scoped>
.webr-plot-node {
  display: flex;
  flex-direction: column;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
}

input[type="text"] {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}

.execute-button {
  padding: 10px 20px;
  margin-top: 10px;
  background-color: rgb(93, 116, 162);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}

#plot-canvas {
  border: 2px solid rgb(93, 116, 162);
  border-radius: 10px;
  margin-top: 10px;
}

.webr-plot-node label {
  font-weight: bold;
  margin-top: 10px;
}

select, input, button {
  margin-bottom: 10px;
}

.vue-flow__handle {
    height:24px;
    width:8px;
    border-radius:4px
}
</style>
