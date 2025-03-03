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
          <img v-if="previewImage" :src="previewImage" alt="生成预览" />
          <div v-else class="image-placeholder">图片预览区域</div>
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
      <div ref="editor" class="code-editor"></div>
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

      <div class="divider"></div>

      <button class="toolbar-btn" @click="toggleFullscreen">
        <span class="icon">{{ isFullscreen ? '📱' : '🖥️' }}</span>
        <span class="text">{{ isFullscreen ? '退出全屏' : '全屏' }}</span>
      </button>
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

// 终端相关
const terminalLogs = ref([])
const handleCommand = (cmd) => {
  terminalLogs.value.push({ type: 'log', content: `执行命令: ${cmd}` })
}

// 代码编辑器相关
const editor = ref(null)
let codeEditor = null
const codeContent = ref('// 生成的代码将出现在这里\n')

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
})

// 聊天相关
const chatMessages = ref([])
const userInput = ref('')
const isLoading2 = ref(false)

const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading2.value) return

  chatMessages.value.push({ 
    role: 'user', 
    content: userInput.value 
  })
  
  const prompt = userInput.value
  userInput.value = ''
  isLoading2.value = true

  try {
    const response = await fetchOpenAI(prompt) // 替换为你的API调用
    const generatedCode = response.code
    
    chatMessages.value.push({
      role: 'assistant',
      content: `生成的代码：\n\`\`\`javascript\n${generatedCode}\n\`\`\``
    })
    
    codeEditor.setValue(generatedCode)
  } catch (error) {
    terminalLogs.value.push({ type: 'error', content: `API错误: ${error.message}` })
  } finally {
    isLoading2.value = false
  }
}

// 模拟API调用
const fetchOpenAI = async (prompt) => {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve({
        code: `// 根据需求生成的代码\nfunction ${prompt.toLowerCase().replace(/ /g, '_')}() {\n  // 实现代码\n}`
      })
    }, 1000)
  })
}





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

</style>
