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
  
 <div class="container">
    <!-- 左侧展示区 -->
    <div class="panel left-panel">
      <div class="preview-area">
        <div class="image-preview">
                <div ref="myCanvas" id="myCanvas" style="width: 100%; height: 500px;"></div>
        </div>
        <vue-web-terminal
          class="terminal"
          :logs="terminalLogs"
          @executeCommand="handleCommand"
        />
      </div>
    </div>

    <!-- 中间代码编辑区 -->
    <div class="panel editor-panel">
      <div ref="editor" class="code-editor" @mouseup="handleSelection"></div>
    </div>

    <!-- 右侧聊天区 -->
    <div class="panel chat-panel">
      <div class="chat-container">
        <div class="chat-messages">
          <div v-for="(msg, index) in chatMessages" :key="index" 
               class="message" :class="msg.role">
            <div class="message-content">{{ msg.content }}</div>
          </div>
        </div>
        <div class="chat-input">
          <input v-model="userInput" 
                 @keyup.enter="sendMessage"
                 placeholder="输入你的需求..." />
          <button @click="sendMessage">发送</button>
        </div>
      </div>
    </div>
  </div>
  
  
  
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
      
      <button class="toolbar-btn" @click="handleExe">
        <span class="icon">▶</span>
        <span class="text">执行</span>
      </button>
      <div class="divider"></div>

      <button class="toolbar-btn" @click="toggleFullscreen">
        <span class="icon">{{ isFullscreen ? '📱' : '🖥️' }}</span>
        <span class="text">{{ isFullscreen ? '退出全屏' : '全屏' }}</span>
      </button>
    </div>
  </div>
  
  
  
  <!-- iOS风格模态窗 -->
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
import { ref, computed, onMounted, onUnmounted } from 'vue'


//----------以下为一个ssmood页面需要的最基础的东西--------------
import { useI18n } from 'vue-i18n';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
const showSubMenu = ref(false);
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
});
//----------以上为一个ssmood页面需要的最基础的东西--------------


const isLoading = ref(true);
isLoading.value = false;


import { VueWebTerminal } from 'vue-web-terminal'
import ace from 'ace-builds'
import 'ace-builds/src-noconflict/mode-javascript'
import 'ace-builds/src-noconflict/theme-monokai'
import config from '@/config';
import axios from 'axios';

// 终端相关
const terminalLogs = ref([])
const handleCommand = (cmd) => {
  terminalLogs.value.push({ type: 'log', content: `执行命令: ${cmd}` })
}



const handleExe = () => {
try {
    // 创建一个新的 Function 作用域并执行生成的代码
    new Function(codeEditor.getValue())();
  } catch (error) {
    console.error("执行代码时出错:", error);
  }
}
// 代码编辑器相关
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
// 更新模态窗位置

let selectionTimeout = null; // 用于存储定时器
const selectionDelay = 300; // 延迟300ms，判断是否完成选择

onMounted(() => {
  codeEditor = ace.edit(editor.value, {
    mode: 'ace/mode/javascript',
    theme: 'ace/theme/monokai',
    fontSize: 14,
    value: codeContent.value
  })
  
  codeEditor.session.on('change', () => {
    codeContent.value = codeEditor.getValue()
  })
  
codeEditor.selection.on('changeSelection', () => {
  const selection = codeEditor.getSelection();
  const range = selection.getRange();
  
  // 如果没有选择内容，直接隐藏模态窗
  if (selection.$isEmpty) {
    showModal.value = false;
    return;
  }

  // 如果之前有定时器，先清除掉
  if (selectionTimeout) {
    clearTimeout(selectionTimeout);
  }

  // 延迟执行，等用户选择结束后再执行
  selectionTimeout = setTimeout(() => {
    const selectionText = codeEditor.getSelectedText();
    if (selectionText.length > 0) {
      selectedCode.value = selectionText; // 获取选中的文本
      showModal.value = true; // 显示模态窗
      lastSelection = range;
      updateModalPosition(); // 更新模态窗位置
    }
  }, selectionDelay);
});
  
})


const updateModalPosition = () => {
  if (!lastSelection || !codeEditor) return;

  // 获取选中结束位置的屏幕坐标
  const endPos = lastSelection.end;
  const screenPos = codeEditor.renderer.textToScreenCoordinates(endPos.row, endPos.column);

  // 转换为容器相对位置
  const containerRect = editor.value.getBoundingClientRect();
  const modalWidth = 280; // 模态窗固定宽度

  // 计算理想位置：将模态窗放在选中区域的右边
  let left = screenPos.pageX - containerRect.left; // 距离选中文本右侧15px
  let top = screenPos.pageY - containerRect.top;

  // 边界检测：确保模态窗不会超出编辑器的右边界
  const maxLeft = containerRect.width - modalWidth - 20;
  if (left > maxLeft) left = maxLeft;

  const maxTop = containerRect.height - 200; // 假设模态窗高度200px
  if (top > maxTop) top = maxTop;

  // 应用位置
  modalPosition.value = {
    top: `${top}px`,
    left: `${left}px`
  };

  // 延迟显示确保位置计算完成
  setTimeout(() => showModal.value = true, 50);
};


// 窗口变化时重新定位
window.addEventListener('resize', updateModalPosition)

//-------------------------------------
//局部代码编辑
//-------------------------------------

const handleSubmit = async () => {
  if (!userInput2.value.trim()) return;

  // 处理提交逻辑
  console.log('选中的代码:', selectedCode.value);
  console.log('用户输入:', userInput2.value);
  
  // 获取AI生成的代码
  const response = await partCodefetchOpenAI('需要修改的代码：' + selectedCode.value + "，用户要求：" + userInput2.value);
  const generatedCode = response.code;
  const cleanedCode = generatedCode.replace(/```javascript|```/g, '').trim();

  // 获取选中的范围
  const selection = codeEditor.getSelection();
  const range = selection.getRange();  // 获取当前选中的范围

  // 使用选中的范围进行替换
  if (!selection.isEmpty()) {
    const session = codeEditor.session; // 定义 session
    session.replace(range, cleanedCode); // 替换选中的内容
  }

  // 执行其他逻辑（如果有）
  handleExe();
  
  closeModal();  // 关闭模态窗
  userInput2.value = '';  // 清空用户输入
};


const closeModal = () => {
  showModal.value = false
  codeEditor.clearSelection() // 清除编辑器选中状态
}



// 发送请求到 OpenAI，并获取代码
const partCodefetchOpenAI = async (userMessage) => {
    
    
    const systemMessage = `你是一个专业的JavaScript程序员，请始终以代码块形式返回代码，用户给出一段代码，按要求修改，仅返回修改后的部分，按原代码格式返回，如果原代码有空格等符号也要返回，只生成代码不要解释。如果需要绘图使用plotly库，将图片绘制到myCanvas元素，仅返回js代码，不返回html元素`;
    
    // 使用 Vue 的 ref 来存储聊天记录
    const chatHistory = ref([{ role: 'system', content: systemMessage }]);
  // 将用户消息添加到聊天记录
  chatHistory.value.push({ role: 'user', content: userMessage });

  try {
    // 发送请求到后端 (PHP)
    const response = await axios.post(config.apiUrl + 'openai_proxy.php', {
      messages: chatHistory.value // 发送聊天记录
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // 处理并返回 OpenAI 响应
    const { choices } = response.data;
    const code = choices && choices[0] ? choices[0].message.content : '';
    // 返回生成的代码和完整的 OpenAI 响应
    return {
      code, // 返回生成的代码
      rawResponse: response.data, // 返回完整的 OpenAI API 响应
    };

  } catch (error) {
    console.error('API调用失败:', error);
    throw new Error(`生成失败: ${error.message}`);
  }
};

//-------------------------------------
//聊天相关
//-------------------------------------

const chatMessages = ref([])
const userInput = ref('')
const isLoading2 = ref(false)

const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading2.value) return

  chatMessages.value.push({ 
    role: 'user', 
    content: userInput.value 
  })
  
  const prompt = codeEditor.getValue()+"用户需求："+userInput.value
  userInput.value = ''
  isLoading2.value = true

  try {
    const response = await fetchOpenAI(prompt) // 替换为你的API调用
    console.log(response)
    const generatedCode = response.code
    
    chatMessages.value.push({
      role: 'assistant',
      content: `生成的代码：\n\`\`\`javascript\n${generatedCode}\n\`\`\``
    })
    const cleanedCode = generatedCode.replace(/```javascript|```/g, '').trim();
    codeEditor.setValue(cleanedCode)
    handleExe();
  } catch (error) {
    terminalLogs.value.push({ type: 'error', content: `API错误: ${error.message}` })
  } finally {
    isLoading2.value = false
  }
}



//-------------------------------------
//openai接口
//-------------------------------------


const systemMessage = `你是一个专业的JavaScript绘图程序员，请始终以代码块形式返回代码，每次用户提出要求后都需要给出完整的代码，不要只给出更改的部分，只生成代码不要解释。如果需要绘图使用plotly库，将图片绘制到myCanvas元素，仅返回js代码，不返回html元素`;

// 使用 Vue 的 ref 来存储聊天记录
const chatHistory = ref([{ role: 'system', content: systemMessage }]);

// 发送请求到 OpenAI，并获取代码
const fetchOpenAI = async (userMessage) => {
  // 将用户消息添加到聊天记录
  chatHistory.value.push({ role: 'user', content: userMessage });

  try {
    // 发送请求到后端 (PHP)
    const response = await axios.post(config.apiUrl + 'openai_proxy.php', {
      messages: chatHistory.value // 发送聊天记录
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    });

    // 处理并返回 OpenAI 响应
    const { choices } = response.data;
    const code = choices && choices[0] ? choices[0].message.content : '';

    // 将 OpenAI 的响应（助手的回复）加入聊天记录
    chatHistory.value.push({ role: 'assistant', content: code });

    // 保证聊天记录最多保存10条
    if (chatHistory.value.length > 10) {
      chatHistory.value.shift(); // 删除最早的记录
    }

    // 返回生成的代码和完整的 OpenAI 响应
    return {
      code, // 返回生成的代码
      rawResponse: response.data, // 返回完整的 OpenAI API 响应
      chatHistory // 返回当前聊天记录
    };

  } catch (error) {
    console.error('API调用失败:', error);
    throw new Error(`生成失败: ${error.message}`);
  }
};





//-------------------------------------
//工具栏
//-------------------------------------
const isFullscreen = ref(false)

const handleNew = () => {
  console.log('新建操作')
  // 触发新建逻辑
}

const handleSave = () => {
  console.log('保存操作')
  // 触发保存逻辑
}

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

//-------------------------------------
//工具栏拖动
//-------------------------------------
const toolbarRef = ref(null)
const isDragging = ref(false)
const dragStartX = ref(0)
const dragStartY = ref(0)
const initialX = ref(0)
const initialY = ref(0)
const posX = ref(500)
const posY = ref(0) // 初始顶部间距

// 从本地存储读取位置
onMounted(() => {
  const savedPos = localStorage.getItem('toolbarPosition')
  if (savedPos) {
    const { x, y } = JSON.parse(savedPos)
    posX.value = x
    posY.value = y
  }
})

// 动态计算样式
const toolbarStyle = computed(() => ({
  top: `${posY.value}px`,
  left: `${posX.value}px`,
  transform: 'translate(-50%, 0)', // 保持水平居中
  cursor: isDragging.value ? 'grabbing' : 'grab'
}))

// 开始拖动
const startDrag = (e) => {
  isDragging.value = true
  dragStartX.value = e.clientX
  dragStartY.value = e.clientY
  initialX.value = posX.value
  initialY.value = posY.value

  // 添加全局事件监听
  document.addEventListener('mousemove', onDrag)
  document.addEventListener('mouseup', stopDrag)
  document.body.style.userSelect = 'none' // 防止文本选中
}

// 拖动中
const onDrag = (e) => {
  if (!isDragging.value) return
  
  const deltaX = e.clientX - dragStartX.value
  const deltaY = e.clientY - dragStartY.value
  
  // 计算新位置（限制在可视区域内）
  const newX = Math.max(0, Math.min(window.innerWidth, initialX.value + deltaX))
  const newY = Math.max(0, Math.min(window.innerHeight - 50, initialY.value + deltaY)) // 限制底部位置

  posX.value = newX
  posY.value = newY
}

// 停止拖动
const stopDrag = () => {
  isDragging.value = false
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
  document.body.style.userSelect = ''
  
  // 保存位置到本地存储
  localStorage.setItem('toolbarPosition', JSON.stringify({
    x: posX.value,
    y: posY.value
  }))
}

// 组件卸载时清理
onUnmounted(() => {
  document.removeEventListener('mousemove', onDrag)
  document.removeEventListener('mouseup', stopDrag)
})





</script>

<style scoped>
@import 'css/MainStyles.css';

.container {
  display: flex;
  height: 85vh;
  overflow: hidden;
}

.panel {
  flex: 1;
  min-width: 0;
  padding: 10px;
  border-right: 1px solid #ddd;
}

.left-panel {
  display: flex;
  flex-direction: column;
}

.preview-area {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.image-preview {
  flex: 2;
  border: 1px solid #eee;
  padding: 10px;
}

.terminal {
  flex: 1;
  margin-top: 10px;
  border: 1px solid #333;
}

.code-editor {
  width: 100%;
  height: 100%;
}

.chat-container {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  background: #f5f5f5;
}

.message {
  margin: 10px 0;
  padding: 8px;
  border-radius: 4px;
}

.message.user {
  background: #e3f2fd;
  margin-left: 20%;
}

.message.assistant {
  background: #fff;
  margin-right: 20%;
}

.chat-input {
  padding: 10px;
  display: flex;
  gap: 10px;
}

.chat-input input {
  flex: 1;
  padding: 8px;
}

.image-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  background: #f0f0f0;
  color: #666;
}




.floating-toolbar:dragging {
  z-index: 9999;
}

.floating-toolbar {
  position: fixed;
  top: 0px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1000;
  background: rgba(093, 116, 162, 0.3);
  backdrop-filter: blur(20px) saturate(180%);
  border-radius: 14px;
  padding: 8px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.18);
}

.toolbar-content {
  display: flex;
  gap: 12px;
  align-items: center;
    white-space: nowrap; /* 禁用自动换行 */
  flex-wrap: nowrap;   /* 禁用flex换行 */
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

/* 全屏模式适配 */
.fullscreen-mode {
  top: 40px;
  background: rgba(255, 255, 255, 0.8);
}

/* 移动端适配 */
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

/* 兼容性备用方案 */
@supports not (backdrop-filter: blur(20px)) {
  .floating-toolbar {
    background: rgba(255, 255, 255, 0.95);
  }
}





/* 模态窗遮罩层 */
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

/* 模态窗容器 */
.modal-container {
  max-width: 320px;
  backdrop-filter: blur(20px) saturate(180%);
  border-radius: 14px;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.18);
  overflow: hidden;
}

/* 模态窗头部 */
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

/* 内容区域 */
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

/* 输入区域 */
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

/* 动画效果 */
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

/* 兼容性处理 */
@supports not (backdrop-filter: blur(20px)) {
  .modal-container {
    background: rgba(255, 255, 255, 0.95);
  }
}
</style>
