<template>
  <div class="main">
    <NavigationBar></NavigationBar>

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
 <div class="container">
    <!-- Left display panel -->
    <div class="panel left-panel">
      <div class="preview-area">
        <div class="image-preview">
                <div ref="myCanvas" id="myCanvas" style="width: 100%; height: 100%;"></div>
        </div>
        <div class="ssMOODShell">
        <ssMOODShell></ssMOODShell>
        </div>
      </div>
      
    </div>

    <!-- Center code editor -->
    <div class="panel editor-panel">
      <div ref="editor" class="code-editor" @mouseup="handleSelection"></div>
            <div 
            class="floating-toolbar"
            :style="toolbarStyle"
            @mousedown="startDrag"
            ref="toolbarRef"
          >
            <div class="toolbar-content">
              <button class="toolbar-btn" @click="handleNew">
                <span class="icon">🆕</span>
                <span class="text">新建</span>
              </button>
              
              <button class="toolbar-btn" @click="handleSave">
                <span class="icon">💾</span>
                <span class="text">保存</span>
              </button>
              <div class="divider"></div>
              <button class="toolbar-btn" @click="handleExe">
                <span class="icon">▶</span>
                <span class="text">执行</span>
              </button>
            </div>
          </div>

        

    </div>

    <!-- Right chat panel -->
    <div class="panel chat-panel">
      <div class="chat-container">
        <div class="chat-messages">
            <h3 class="chat-title">Ai对话框</h3>
          <div v-for="(msg, index) in chatMessages" :key="index" 
               class="message" :class="msg.role">
            <div class="message-content">{{ msg.content }}</div>
          </div>
        </div>
        <div class="chat-input-container">
        <div class="chat-input">
          <button @click="datasetsManage" v-if = "isInputExpand">数据集管理</button>
          <input v-model="userInput" 
                @keyup.enter="sendMessage"
                placeholder="输入你的需求..." 
                @focus="onFocus"
                @blur="onBlur"
                 />
          <button @click="sendMessage">发送</button>
        </div>
        </div>
      </div>
    </div>
  </div>
  
  

      <!-- Dataset management modal -->
    <div v-if="showModal0" class="dataset-modal">
      <div class="dataset-modal-content">
        <div class="dataset-modal-header">
            <h2>数据集管理</h2>
            <button @click="showModal0 = false">❌ </button>
        </div>
        <div class="add-dataset-content">
        <h3>添加数据集</h3>
        <!-- Add dataset -->
        <div class="add-dataset-div">
        <div class="add-dataset">
            <select v-model="selectedDataType">
                <option disabled value="">数据集类型</option>
                <option value="1">单细胞基础数据</option>
                <option value="2">单细胞基因表达量</option>
                <option value="3">空转基础数据</option>
                <option value="4">空转基因表达量</option>
            </select>
        </div>
        <!-- Add dataset -->
        <div class="add-dataset" v-if="showDatasetSelect === 1">
          <select v-model="selectedId1">
              <option disabled value="">数据集ID</option>
              <option v-for="dataset in SC_datasets" :key="dataset.id" :value="dataset.name">
                {{ dataset.name }}
              </option>
          </select>
          <select v-model="selectedType1">
            <option value="umap">umap</option>
            <option value="差异">差异</option>
          </select>
          <button @click="addDataset(1)">➕ </button>
        </div>
        
        <!-- Add dataset -->
        <div class="add-dataset" v-if="showDatasetSelect === 2">
          <select v-model="selectedId2">
              <option disabled value="">数据集ID</option>
              <option v-for="dataset in SC_datasets" :key="dataset.id" :value="dataset.name">
                {{ dataset.name }}
              </option>
          </select>
          <select v-model="selectedGene_SC">
            <option value="类型A">umap</option>
            <option value="类型C">差异</option>
          </select>
          <button @click="addDataset(2)">➕ </button>
        </div>
                <!-- Add dataset -->
        <div class="add-dataset" v-if="showDatasetSelect === 3">
          <select v-model="selectedId3">
              <option disabled value="">数据集ID</option>
              <option v-for="dataset in ST_datasets" :key="dataset.id" :value="dataset.name">
                {{ dataset.name }}
              </option>
          </select>
          <select v-model="selectedType2">
            <option value="空间位置">空间位置</option>
            <option value="差异">差异</option>
          </select>
          <button @click="addDataset(3)">➕ </button>
        </div>
                <!-- Add dataset -->
        <div class="add-dataset" v-if="showDatasetSelect === 4">
          <select v-model="selectedId4">
            <option value="分类1">分类1</option>
            <option value="分类2">分类2</option>
          </select>
          <select v-model="selectedGene_ST">
            <option value="类型A">umap</option>
            <option value="类型C">差异</option>
          </select>
          <button @click="addDataset(4)">➕ </button>
        </div>
        </div>
        </div>
        
        <!-- Dataset list -->
        <ul class="dataset-list">
          <li v-for="(dataset, index) in datasets" :key="index">
            {{ dataset.id }} - {{ dataset.type }}
            <button @click="removeDataset(index)">&#x1F5D1;</button>
          </li>
        </ul>

        
      </div>
    </div>
  
  
  <!-- iOS-style modal -->
      <div 
      v-if="showModal"
      ref="modalRef"
      class="context-modal"
      :style="modalPosition"
    >
      <div class="modal-header">
        <h3>AI 辅助编程</h3>
        <button class="close-btn" @click="closeModal">×</button>
      </div>
      
      <div class="modal-content">
        <div class="selected-code">
          <pre>{{ selectedCode }}</pre>
        </div>
        
        <div class="input-group">
          <textarea
            v-model="userInput2"
            placeholder="请输入你的问题或指令"
            class="ios-input"
          ></textarea>
          <button class="ios-button" @click="handleSubmit">发送</button>
        </div>
      </div>
  </div>
  
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref,onMounted,watch } from 'vue'
//import {  computed, onUnmounted } from 'vue'

//----------Below is the bare minimum needed for an ssmood page--------------
//import { useI18n } from 'vue-i18n';
//import LanguageSwitcher from './general/LanguageSwitcher.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------Above is the bare minimum needed for an ssmood page--------------


const isLoading = ref(true);
isLoading.value = false;


import ssMOODShell from './analyze/ssMOODShell.vue'
import ace from 'ace-builds'
import 'ace-builds/src-noconflict/mode-javascript'
import 'ace-builds/src-noconflict/theme-monokai'
import axios from 'axios';
import { TerminalApi } from 'vue-web-terminal';
import pako from 'pako';
// Terminal
const terminalLogs = ref([])

const handleExe = async () => {
  try {
    const dynamicVariable = {}; // Stores loaded data

    // Wrap each dataset's loading logic in a Promise
    const fetchPromises = datasets.value.map(async (dataset) => {
      if (dataset.way == 1) {
        const params = new URLSearchParams({
          id: dataset.id
        });

        const response = await fetch(config.apiUrl + `scd_getumapdata.php?${params}`);
        const arrayBuffer = await response.arrayBuffer();

        const compressed = new Uint8Array(arrayBuffer);
        const decompressed = pako.ungzip(compressed); // Decompress with pako
        const jsonString = new TextDecoder('utf-8').decode(decompressed);
        const data = JSON.parse(jsonString); // Parse JSON string

        // Store data in dynamicVariable
        dynamicVariable[dataset.id + '_' + dataset.type] = data;
      }else if (dataset.way == 3) {
        const params = new URLSearchParams({
          id: dataset.id
        });

        const response = await fetch(config.apiUrl + `std_getCoordinate.php?${params}`);
        const arrayBuffer = await response.arrayBuffer();

        const compressed = new Uint8Array(arrayBuffer);
        const decompressed = pako.ungzip(compressed); // Decompress with pako
        const jsonString = new TextDecoder('utf-8').decode(decompressed);
        const data = JSON.parse(jsonString); // Parse JSON string

        // Store data in dynamicVariable
        dynamicVariable[dataset.id + '_' + dataset.type] = data;
      }
    });

    // Wait for all Promises to complete
    await Promise.all(fetchPromises);

    // Execute user-entered code
    const code = codeEditor.getValue(); // Get user-entered code
    const func = new Function('dynamicVariable', code); // Pass dynamicVariable as a parameter
    func(dynamicVariable); // Execute the function with dynamicVariable

    // Notify success
    TerminalApi.pushMessage('ssMOOD', {
      class: 'system',
      tag: 'success',
      content: '执行成功'
    });
  } catch (error) {
    // Notify failure
    TerminalApi.pushMessage('ssMOOD', {
      class: 'system',
      tag: 'fail',
      content: '执行代码时出错：' + error
    });
    console.error("执行代码时出错:", error);
  }
};
// Code editor
const editor = ref(null)
let codeEditor = null
const codeContent = ref('// 生成的代码将出现在这里\n')
const modalPosition = ref({
  top: '0px',
  left: '0px'
})
let lastSelection = null
const modalRef = ref(null)
const showModal = ref(false)
const selectedCode = ref('')
const userInput2 = ref('')
// Update modal position

let selectionTimeout = null; // Stores the timer
const selectionDelay = 300; // Wait 300ms to determine whether selection is finished

onMounted(() => {
  codeEditor = ace.edit(editor.value, {
    mode: 'ace/mode/javascript',
    theme: 'ace/theme/clouds',
    fontSize: 14,
    value: codeContent.value
  })
  
  codeEditor.session.on('change', () => {
    codeContent.value = codeEditor.getValue()
  })
  
codeEditor.selection.on('changeSelection', () => {
  const selection = codeEditor.getSelection();
  const range = selection.getRange();
  
  // If nothing is selected, hide the modal immediately
  if (selection.$isEmpty) {
    showModal.value = false;
    return;
  }

  // Clear any existing timer first
  if (selectionTimeout) {
    clearTimeout(selectionTimeout);
  }

  // Delay execution until the user finishes selecting
  selectionTimeout = setTimeout(() => {
    const selectionText = codeEditor.getSelectedText();
    if (selectionText.length > 0) {
      selectedCode.value = selectionText; // Get the selected text
      showModal.value = true; // Show modal
      lastSelection = range;
      updateModalPosition(); // Update modal position
    }
  }, selectionDelay);
});
  
})


const updateModalPosition = () => {
  if (!lastSelection || !codeEditor) return;

  // Get screen coordinates of the selection end
  const endPos = lastSelection.end;
  const screenPos = codeEditor.renderer.textToScreenCoordinates(endPos.row, endPos.column);

  // Convert to container-relative position
  const containerRect = editor.value.getBoundingClientRect();
  const modalWidth = 280; // Fixed modal width

  // Compute ideal position: place the modal to the right of the selection
  let left = screenPos.pageX - containerRect.left; // 15px to the right of the selected text
  let top = screenPos.pageY - containerRect.top;

  // Boundary check: keep the modal within the editor's right edge
  const maxLeft = containerRect.width - modalWidth - 20;
  if (left > maxLeft) left = maxLeft;

  const maxTop = containerRect.height - 200; // Assume modal height of 200px
  if (top > maxTop) top = maxTop;

  // Apply position
  modalPosition.value = {
    top: `${top}px`,
    left: `${left}px`
  };

  // Delay showing to ensure position calculation is complete
  setTimeout(() => showModal.value = true, 50);
};


// Reposition on window resize
window.addEventListener('resize', updateModalPosition)

//-------------------------------------
//Partial code editing
//-------------------------------------

const handleSubmit = async () => {
  if (!userInput2.value.trim()) return;

  // Handle submit logic
  console.log('选中的代码:', selectedCode.value);
  console.log('用户输入:', userInput2.value);
  
  // Get AI-generated code
  const response = await partCodefetchOpenAI('需要修改的代码：' + selectedCode.value + "，用户要求：" + userInput2.value);
  const generatedCode = response.code;
  const cleanedCode = generatedCode.replace(/```javascript|```/g, '').trim();

  // Get the selected range
  const selection = codeEditor.getSelection();
  const range = selection.getRange();  // Get the currently selected range

  // Replace using the selected range
  if (!selection.isEmpty()) {
    const session = codeEditor.session; // Define session
    session.replace(range, cleanedCode); // Replace the selected content
  }

  // Run other logic (if any)
  handleExe();
  
  closeModal();  // Close modal
  userInput2.value = '';  // Clear user input
};


const closeModal = () => {
  showModal.value = false
  codeEditor.clearSelection() // Clear editor selection
}



// Send request to OpenAI and get code
const partCodefetchOpenAI = async (userMessage) => {
    
    
    const systemMessage = `你是一个专业的JavaScript程序员，请始终以代码块形式返回代码，用户给出一段代码，按要求修改，仅返回修改后的部分，按原代码格式返回，如果原代码有空格等符号也要返回，只生成代码不要解释。如果需要绘图使用plotly库，将图片绘制到myCanvas元素，仅返回js代码，不返回html元素`;
    
    // Use a Vue ref to store chat history
    const chatHistory = ref([{ role: 'system', content: systemMessage }]);
  // Add user message to chat history
  chatHistory.value.push({ role: 'user', content: userMessage });

  try {
    // Send request to backend (PHP)
    const response = await axios.post(config.apiUrl + 'openai_proxy.php', {
      messages: chatHistory.value // Send chat history
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // Process and return OpenAI response
    const { choices } = response.data;
    const code = choices && choices[0] ? choices[0].message.content : '';
    // Return generated code and the full OpenAI response
    return {
      code, // Return generated code
      rawResponse: response.data, // Return the full OpenAI API response
    };

  } catch (error) {
    console.error('API调用失败:', error);
    throw new Error(`生成失败: ${error.message}`);
  }
};
//-------------------------------------
//Dataset management
//-------------------------------------

const SC_datasets = ref([]);

onMounted(async () => {
  try {
    const response = await axios.post(config.apiUrl + 'ap_getDatasetList_sc.php'); // Assume this is your backend endpoint
    SC_datasets.value = response.data.datasets;
  } catch (error) {
    console.error('Error fetching datasets:', error);
  }
});
const ST_datasets = ref([]);

onMounted(async () => {
  try {
    const response = await axios.post(config.apiUrl + 'ap_getDatasetList_st.php'); // Assume this is your backend endpoint
    ST_datasets.value = response.data.datasets;
  } catch (error) {
    console.error('Error fetching datasets:', error);
  }
});

const selectedDataType = ref("");
const showDatasetSelect = ref();
watch(selectedDataType, (newVal) => {
    if(newVal==="1"){
        showDatasetSelect.value = 1;
    }else if(newVal==="2"){
        showDatasetSelect.value = 2;
    }else if(newVal==="3"){
        showDatasetSelect.value = 3;
    }else if(newVal==="4"){
        showDatasetSelect.value = 4;
    }
      
});
    
const showModal0 = ref(false);


const selectedId1 = ref('');
const selectedId2 = ref('');
const selectedId3 = ref('');
const selectedId4 = ref('');
const selectedType1 = ref('');
const selectedType2 = ref('');


const selectedGene_SC = ref('');
const selectedGene_ST = ref('');

const datasets = ref([]);

const datasetsManage = () => {
  showModal0.value = true;
};

const addDataset = (way) => {
  if (way==1)    
  {
    if (selectedId1.value && selectedType1.value) {
    datasets.value.push({
      type: selectedType1.value,
      id: selectedId1.value,
      way:way
    });
    selectedType1.value = '';
    selectedId1.value = '';
  }
  }
  else if (way==2){
    if (selectedId2.value && selectedGene_SC.value) {
    datasets.value.push({
      type: selectedGene_SC.value,
      id: selectedId2.value,
      way:way
    });
    selectedGene_SC.value = '';
    selectedId2.value = '';
  }
  }
    else if (way==3)    
  {
    if (selectedId3.value && selectedType2.value) {
    datasets.value.push({
      type: selectedType2.value,
      id: selectedId3.value,
      way:way
    });
    selectedType2.value = '';
    selectedId3.value = '';
  }
  }
 else if (way==4){
    if (selectedId4.value && selectedGene_ST.value) {
    datasets.value.push({
      type: selectedGene_ST.value,
      id: selectedId4.value,
      way:way
    });
    selectedGene_ST.value = '';
    selectedId4.value = '';
  }
  }

};

const removeDataset = (index) => {
  datasets.value.splice(index, 1);
};


//-------------------------------------
//Chat
//-------------------------------------
const isInputExpand = ref(true); // Show the dataset management button by default

const timer = ref(null); // Timer for delayed actions

// Triggered when the input gains focus
const onFocus = () => {
  // Clear any pending delayed action
  if (timer.value) {
    clearTimeout(timer.value);
    timer.value = null;
  }
  isInputExpand.value = false;
};

// Triggered when the input loses focus
const onBlur = () => {
  // Set delayed action
  timer.value = setTimeout(() => {
    isInputExpand.value = true;
    timer.value = null;
  }, 300); // Delay 300 ms
};

const chatMessages = ref([])
const userInput = ref('')
const isLoading2 = ref(false)
const prompt = ref('')

const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading2.value) return

  chatMessages.value.push({ 
    role: 'user', 
    content: userInput.value 
  })
  

   if (datasets.value && datasets.value.length > 0) {
      prompt.value = codeEditor.getValue()+"用户需求："+userInput.value
      datasets.value.forEach((dataset) => {
        console.log(`数据集ID: ${dataset.id}, 类型: ${dataset.type}, 方法: ${dataset.way}`);
        if(dataset.way==1){
            prompt.value=`用户在 dynamicVariable[${dataset.id}_${dataset.type}] 变量中，已存在以下格式的 JSON 数组：{
  "umap_data": [
    {
      "i": "cell1", // 细胞 ID
      "u1": 1.23,   // UMAP 维度 1
      "u2": 4.56,   // UMAP 维度 2
      "c": "cluster1" // 分类标签
    }
  ],
  "cluster_labels": [
    "cluster1",
    "cluster2"
  ]
}。这些内容已定义，无需重新定义。`+prompt.value
    }else if(dataset.way==3){
         prompt.value=`用户在 dynamicVariable[${dataset.id}_${dataset.type}] 变量中，已存在以下格式的空转坐标 JSON 数组：{
  "coordinate_data": [
    {
      "i": "cell1", // 细胞 ID
      "x": 1.23,   // x_coordinate
      "y": 4.56,   // y_coordinate
      "c": "cluster1" // 分类标签
    }
  ],
  "cluster_labels": [
    "cluster1",
    "cluster2"
  ]
}。这些内容已定义，无需重新定义。`+prompt.value
    }
       });
      
      userInput.value = ''
      isLoading2.value = true
    
      try {
        const response = await fetchOpenAI(prompt.value) // Replace with your API call
        prompt.value=""
        console.log(response)
        const generatedCode = response.code
        
        chatMessages.value.push({
          role: 'assistant',
          content: `生成的代码：\n\`\`\`javascript\n${generatedCode}\n\`\`\``
        })
        const cleanedCode = generatedCode.replace(/```javascript|```/g, '').trim();
        codeEditor.setValue(cleanedCode);
        codeEditor.clearSelection();
        handleExe();
      } catch (error) {
        terminalLogs.value.push({ type: 'error', content: `API错误: ${error.message}` })
      } finally {
        isLoading2.value = false
      }
    }
    else{
        console.error("没有选择数据集");
    }
}



//-------------------------------------
//OpenAI API
//-------------------------------------


const systemMessage = `你是一个专业的JavaScript绘图程序员，请始终以代码块形式返回代码，每次用户提出要求后都需要给出完整的代码，不要只给出更改的部分，只生成代码不要解释。如果需要绘图使用plotly库，将图片绘制到myCanvas元素，仅返回js代码，不返回html元素`;

// Use a Vue ref to store chat history
const chatHistory = ref([{ role: 'system', content: systemMessage }]);

// Send request to OpenAI and get code
const fetchOpenAI = async (userMessage) => {
  // Add user message to chat history
  chatHistory.value.push({ role: 'user', content: userMessage });

  try {
    // Send request to backend (PHP)
    const response = await axios.post(config.apiUrl + 'openai_proxy.php', {
      messages: chatHistory.value // Send chat history
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // Process and return OpenAI response
    const { choices } = response.data;
    const code = choices && choices[0] ? choices[0].message.content : '';

    // Add OpenAI's response (assistant reply) to chat history
    chatHistory.value.push({ role: 'assistant', content: code });

    // Keep at most 10 chat records
    if (chatHistory.value.length > 10) {
      chatHistory.value.shift(); // Remove the oldest record
    }

    // Return generated code and the full OpenAI response
    return {
      code, // Return generated code
      rawResponse: response.data, // Return the full OpenAI API response
      chatHistory // Return current chat history
    };

  } catch (error) {
    console.error('API调用失败:', error);
    throw new Error(`生成失败: ${error.message}`);
  }
};





//-------------------------------------
//Toolbar
//-------------------------------------
//const isFullscreen = ref(false)

const handleNew = () => {
  console.log('新建操作')
  // Trigger new-file logic
}

const handleSave = () => {
  console.log('保存操作')
  // Trigger save logic
}

/*
const toggleFullscreen = () => {
  isFullscreen.value = !isFullscreen.value
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen()
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen()
    }
  }
}
*/
//-------------------------------------
//Toolbar dragging
//-------------------------------------
/*
const toolbarRef = ref(null)
const isDragging = ref(false)
const dragStartX = ref(0)
const dragStartY = ref(0)
const initialX = ref(0)
const initialY = ref(0)
const posX = ref(500)
const posY = ref(0) // Initial top offset

// Read position from localStorage
onMounted(() => {
  const savedPos = localStorage.getItem('toolbarPosition')
  if (savedPos) {
    const { x, y } = JSON.parse(savedPos)
    posX.value = x
    posY.value = y
  }
})

// Compute style dynamically
const toolbarStyle = computed(() => ({
  top: `${posY.value}px`,
  left: `${posX.value}px`,
  transform: 'translate(-50%, 0)', // Keep horizontally centered
  cursor: isDragging.value ? 'grabbing' : 'grab'
}))

// Start dragging
const startDrag = (e) => {
  isDragging.value = true
  dragStartX.value = e.clientX
  dragStartY.value = e.clientY
  initialX.value = posX.value
  initialY.value = posY.value

  // Add global event listeners
  document.addEventListener('mousemove', onDrag)
  document.addEventListener('mouseup', stopDrag)
  document.body.style.userSelect = 'none' // Prevent text selection
}

// While dragging
const onDrag = (e) => {
  if (!isDragging.value) return
  
  const deltaX = e.clientX - dragStartX.value
  const deltaY = e.clientY - dragStartY.value
  
  // Compute new position (clamped to the visible area)
  const newX = Math.max(0, Math.min(window.innerWidth, initialX.value + deltaX))
  const newY = Math.max(0, Math.min(window.innerHeight - 50, initialY.value + deltaY)) // Clamp bottom position

  posX.value = newX
  posY.value = newY
}

// Stop dragging
const stopDrag = () => {
  isDragging.value = false
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
  document.body.style.userSelect = ''
  
  // Save position to localStorage
  localStorage.setItem('toolbarPosition', JSON.stringify({
    x: posX.value,
    y: posY.value
  }))
}

// Clean up when component unmounts
onUnmounted(() => {
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
})


*/


</script>

<style scoped>
@import 'css/MainStyles.css';
.page-section {
  padding: 0px;
  flex: 1; /* Fill the remaining space */
}
.container {
  display: flex;
  height: 92vh;
  width: 100%;
  overflow: hidden;
  background: linear-gradient(
    135deg,
    #f5f7fa 0%,     /* Soft cloud white */
    #e3e9ff 25%,    /* Light lavender gray */
    #d8e4ff 50%,    /* Very pale baby blue */
    #eceffd 75%,    /* Delicate moonlight silver */
    #f5f7fa 100%    /* Matches the start color for a seamless loop */
  );
  background-size: 100% 100%; /* Smaller background size for finer detail */
  animation: gradient 5s ease infinite; /* Gentler animation pace */
}
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}


.panel {
  flex: 1;
  min-width: 0;
  padding: 10px;
}

.left-panel {
  display: flex;
  flex-direction: column;
}

.preview-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  height:100%;
  gap:20px;
}

.image-preview {
  flex: 2;
  border: 1px solid #eee;
  padding: 10px;
  border-radius: 15px; /* Rounded border */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional shadow effect */
  border: 2px solid rgba(0, 0, 0, 0.1); /* Add a visible border */
  backdrop-filter: blur(10px); /* Frosted-glass effect */
  background: rgba(245, 245, 245, 0.4); /* Semi-transparent background */
}

.terminal {
  flex: 1;
  margin-top: 10px;
  border: 1px solid #333;
}

.ssMOODShell{
  height: 30%;
  width: 100%;
}

/* ############################## */
/* Code editor styles */
/* ############################## */
.editor-panel{
  display: flex;
  align-items: center; /* Center vertically */
  justify-content: center; /* Center horizontally */
  flex-direction: column;
  gap:10px;
}


.code-editor {

  width: 95%; /* Fill the parent container's width */
  height: 85%; /* Fill the parent container's height */
  border-radius: 15px; /* Rounded border */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional shadow effect */
  padding: 10px; /* Optional padding */
  font-family: monospace; /* Editor font */
  font-size: 14px; /* Font size */
  white-space: pre-wrap; /* Preserve line breaks and spaces */
  overflow: hidden; /* Allow scrolling if content exceeds the height */
  border: 2px solid rgba(0, 0, 0, 0.1); /* Add a visible border */
  padding: 10px;
}
/* ############################## */
/* Chat message container */
/* ############################## */
.chat-container {
  display: flex;
  flex-direction: column;
  height: 100%;
  gap:10px;
}

.chat-title {
  font-size: 1.5rem; /* Title font size */
  font-weight: bold; /* Bold font */
  margin-bottom: 10px; /* Spacing below */
  text-align: center; /* Center alignment */
  color: #333; /* Title color */
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  background: rgba(245, 245, 245, 0.4); /* Semi-transparent background */
  backdrop-filter: blur(10px); /* Frosted-glass effect */
  border-radius: 16px; /* Rounded corners */
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); /* Shadow effect */
  box-sizing: border-box;
  border: 2px solid rgba(0, 0, 0, 0.1); /* Add a visible border */
}
/* Scrollbar styling (iOS style) */
.chat-messages::-webkit-scrollbar {
  width: 8px; /* Scrollbar width */
}

.chat-messages::-webkit-scrollbar-track {
  background: transparent; /* Transparent track background */
  border-radius: 10px; /* Match the container's rounded border */
  margin: 10px 0;
}

.chat-messages::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2); /* Scrollbar color */
  border-radius: 10px; /* Scrollbar corner radius matches the container */
}

.chat-messages::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.4); /* Scrollbar color on hover */
}
.message {
  margin: 10px 0;
  padding: 8px;
  border-radius: 16px; /* Larger corner radius for an iOS look */
  max-width: 60%; /* Limit message width */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
  font-size: 14px; /* Use a smaller font size */
  line-height: 1.4; /* Adjust line height */
  position: relative;
  display: block;
  width: fit-content;
}

.message.user {
  background: #e3f2fd; /* User message background color */
  margin-left: auto; /* Right-align with flexbox */
  margin-right: 20px; /* Right margin */
  color: #333; /* Text color */
  text-align: left;
  padding-left: 30px;
  padding-right: 30px;
}

.message.assistant {
  background: #fff; /* Assistant message background color */
  margin-left: 20px; /* Left margin */
  margin-right: auto; /* Left-align with flexbox */
  color: #666; /* Text color */
  text-align: left;
  padding-right: 20px;
  padding-left: 20px;
}

/* ############################## */
/* User input */
/* ############################## */
.chat-input-container {
  display: flex;
  justify-content: center;
}
.chat-input {
  display: inline-flex;
  width: fit-content; /* Let the width adjust to the content */
  padding: 10px;
  background: rgba(245, 245, 245, 0.5); /* Semi-transparent background */
  backdrop-filter: blur(10px); /* Frosted-glass blur effect */
  border-radius: 20px; /* Rounder corners */
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  border: 1px solid rgba(0, 0, 0, 0.1); /* Soft border */
  gap:5px;
  
}

.chat-input input {
  padding: 5px;
  border: 1px solid rgba(0, 0, 0, 0.1); /* Soft input border */
  border-radius: 10px; /* Rounded input corners */
  background-color: rgba(255, 255, 255, 0.2); /* Input background */
  color: #333; /* Input text color */
  font-size: 14px; /* Font size */
  outline: none; /* Remove the border on focus */
  transition: width 0.5s ease; /* Smooth transition */
  width: 8vw; /* Smaller initial width */
  max-width: 30vw; /* Limit maximum width */
}

.chat-input input:focus {
  width: 20vw; /* Widen on focus */
  padding: 10px 20px; /* Optional: adjust padding */
}

.chat-input input::placeholder {
  color: #aaa; /* Input placeholder color */
}

.chat-input button {
  padding: 10px 20px; /* Adjust button size */
  border: none;
  background: linear-gradient(135deg, #e3f2fd, #d1e8ff);
  color: black;
  border-radius: 20px; /* Moderate corner radius */
  cursor: pointer;
  font-size: 14px; /* Font size */
  transition: background-color 0.3s ease, transform 0.3s ease; /* Transition for background color and click effect */
  font-weight: 500; /* Slightly bolder font for clarity */
  box-shadow: none; /* Remove shadow */
  transition: width 0.3s ease; /* Smooth transition */
}

.chat-input button:hover {
  background: linear-gradient(135deg, #d1e8ff, #e3f2fd); /* Reverse the gradient on hover */
  box-shadow: 0 6px 20px rgba(106, 17, 203, 0.3); /* More prominent shadow */
  transform: translateY(-1px); /* Slight lift on hover */
}

.chat-input button:active {
  transform: translateY(1px); /* Slight press-down effect on click */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); /* Fine shadow */
  background: linear-gradient(135deg, #d1e8ff, #e3f2fd); /* Keep the reversed gradient while pressed */
}



/* ############################## */
/* Floating toolbar */
/* ############################## */
.floating-toolbar:dragging {
  z-index: 9999;
}

.floating-toolbar {
  align-items: center; /* Center vertically */
  justify-content: center; /* Center horizontally */
  background: rgba(093, 116, 162, 0.3);
  backdrop-filter: blur(20px) saturate(180%);
  border-radius: 14px;
  padding: 8px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.18);
  display: inline-flex;
  width: fit-content; /* Let the width adjust to the content */
}

.toolbar-content {
  display: flex;
  gap: 12px;
  align-items: center;
    white-space: nowrap; /* Disable text wrapping */
  flex-wrap: nowrap;   /* Disable flex wrapping */
}

.toolbar-btn {
  display: flex;
  align-items: center;
  padding: 10px 16px;
  background: rgba(255, 255, 255, 0.3);
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #1c1c1e;
  font-size: 14px;
  font-weight: 500;
}

.toolbar-btn:hover {
  background: rgba(255, 255, 255, 0.5);
  transform: translateY(-1px);
}

.toolbar-btn:active {
  transform: translateY(1px);
}

.icon {
  margin-right: 8px;
  font-size: 16px;
}

.divider {
  width: 1px;
  height: 24px;
  background: rgba(60, 60, 67, 0.18);
  margin: 0 8px;
}

/* Fullscreen mode adjustments */
.fullscreen-mode {
  top: 40px;
  background: rgba(255, 255, 255, 0.8);
}

/* Mobile adjustments */
@media (max-width: 768px) {
  .floating-toolbar {
    top: 10px;
    padding: 6px;
  }
  
  .toolbar-btn {
    padding: 8px 12px;
    font-size: 12px;
  }
  
  .icon {
    font-size: 14px;
  }
}

/* Compatibility fallback */
@supports not (backdrop-filter: blur(20px)) {
  .floating-toolbar {
    background: rgba(255, 255, 255, 0.95);
  }
}


/* ############################## */
/* AI modal */
/* ############################## */


/* Modal overlay */
.context-modal {
  position: absolute;
  width: 320px;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(20px);
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  z-index: 1000;
  transform-origin: left top;
  animation: slideIn 0.25s ease-out;
}

/* Modal container */
.modal-container {
  max-width: 320px;
  backdrop-filter: blur(20px) saturate(180%);
  border-radius: 14px;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.18);
  overflow: hidden;
}

/* Modal header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.modal-header h3 {
  margin: 0;
  font-weight: 600;
  color: #1c1c1e;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 24px;
  color: #666;
  cursor: pointer;
  padding: 0 8px;
}

/* Content area */
.modal-content {
  padding: 16px;
}

.selected-code {
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 16px;
  max-height: 150px;
  overflow: auto;
  color:black;
}

.selected-code pre {
  margin: 0;
  font-family: 'Menlo', monospace;
  font-size: 13px;
  color: #333;
}

/* Input area */
.input-group {
  display: flex;
  gap: 12px;
  align-items: flex-end;
}

.ios-input {
  flex: 1;
  padding: 12px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.8);
  min-height: 80px;
  font-family: -apple-system, sans-serif;
  font-size: 16px;
  resize: none;
}

.ios-input:focus {
  outline: none;
  border-color: #007AFF;
}

.ios-button {
  background: #007AFF;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.ios-button:active {
  transform: scale(0.95);
  opacity: 0.8;
}

/* Animations */
.modal-container {
  animation: modalEnter 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

@keyframes modalEnter {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Compatibility handling */
@supports not (backdrop-filter: blur(20px)) {
  .modal-container {
    background: rgba(255, 255, 255, 0.95);
  }
}

/* ############################## */
/* Dataset management modal */
/* ############################## */

.dataset-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1002;
}

.dataset-modal-content {
  display: flex; /* Use flexbox layout */
  justify-content: center;
  align-items: center; /* Center vertically */
  flex-direction: column;
  background: rgb(255, 255, 255);
  padding: 20px;
  border-radius: 12px;
  width: 50vw;
  max-width: 90%;

}
.dataset-modal-header {
    display: flex; /* Use flexbox layout */
    justify-content: center;
    align-items: center; /* Center vertically */
    padding: 10px; /* Add padding */
    width: 100%;
}

.dataset-modal-header h2 {
    position: fixed;
    margin: 0; /* Remove default margin */
    font-size: 1.5rem; /* Title font size */
    text-align: center; /* Center the title */
    flex: 1; /* Let the title fill the remaining space */
    
}

.dataset-modal-header button {
    margin: 0; /* Remove default margin */
    padding: 5px 10px; /* Add button padding */
    margin-left: auto;
    font-size: 1rem; /* Button font size */
    cursor: pointer; /* Show pointer cursor on hover */
    border: 0px; /* Button border */
    background-color: #fff; /* Button background color */
}
.add-dataset-content {
    border: 1px solid #ccc; /* Add a gray border */
    padding: 10px; /* Add padding */
    margin-bottom: 10px; /* Add margin */
    border-radius: 5px; /* Add rounded border */
    background-color: #f9f9f9; /* Add background color */
    width: 80%;
    justify-content: center;
    display: flex; /* Use flexbox layout */
    flex-direction: column;
}


.add-dataset-content h3{
     display: flex; /* Use flexbox layout */
    margin: 0; /* Remove default margin */
    justify-content: center;
}
.add-dataset-div{
    display: flex; /* Use flexbox layout */
    flex-direction: row;
    gap: 10px;
    
}

.add-dataset {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}


.add-dataset select {
    padding: 10px 15px;
    font-size: 1rem;
    color: #333;
    outline: none;
    box-sizing: border-box;
     border-radius: 15px; /* Add rounded border */
}

.add-dataset button {
    margin: 0; /* Remove default margin */
    padding: 5px 10px; /* Add button padding */
    margin-left: auto;
    font-size: 1rem; /* Button font size */
    cursor: pointer; /* Show pointer cursor on hover */
    border: 0px; /* Button border */
    background-color: #f9f9f9; /* Add background color */
}

.dataset-list {
    display: flex;
  list-style: none;
  padding: 0;
}

.dataset-list li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 6px;
}
.dataset-list button{
    margin: 0; /* Remove default margin */
    padding: 5px 10px; /* Add button padding */
    margin-left: auto;
    font-size: 1rem; /* Button font size */
    cursor: pointer; /* Show pointer cursor on hover */
    border: 0px; /* Button border */
    background-color: #fff; /* Button background color */
}
</style>
