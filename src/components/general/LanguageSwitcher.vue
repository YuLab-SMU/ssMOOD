<template>
  <div class="language-switcher-container">
    <span class="language-label">Language</span>
    <el-select
      v-model="selectedLanguage"
      @change="switchLanguage"
      size="small"
      placeholder="Select"
    >
      <el-option label="简体中文" value="zh1" />
      <el-option label="繁體中文" value="zh2" />
      <el-option label="English" value="en" />
    </el-select>
  </div>
</template>

<script setup>
import { ref, onMounted ,defineEmits} from 'vue';

// 创建响应式引用
const selectedLanguage = ref('');

// 加载语言偏好
const loadLanguagePreference = () => {
  return localStorage.getItem('selectedLanguage') || 'zh1';
};

// 保存语言偏好
const saveLanguagePreference = (language) => {
  localStorage.setItem('selectedLanguage', language);
};

// 切换语言
const switchLanguage = () => {
  saveLanguagePreference(selectedLanguage.value);
  emit('languageChanged', selectedLanguage.value);
};

// 在组件挂载时设置初始语言
onMounted(() => {
  selectedLanguage.value = loadLanguagePreference();
  emit('languageChanged', selectedLanguage.value);
});

// 定义 emit 函数来触发父组件的事件监听器
const emit = defineEmits(['languageChanged']);
</script>

<style scoped>
.language-switcher-container {
  position: relative;
  display: inline-block;
  padding: 5px;
  margin-right: 60px;
  border: 1px solid #5d72a2;
  border-radius: 4px;
  margin-left: auto;
}

.language-switcher-container a {
  margin-right: 10px;
  color: white;
  text-decoration: none;
}

.language-switcher {
  padding: 5px 20px;
  margin: 0;
  border: none;
  border-radius: 4px;
  background-color: white;
  font-size: 16px;
  cursor: pointer;
  outline: none;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px 16px;
  transition: border-color 0.2s, background-color 0.2s;
}

.language-switcher:focus {
  border-color: #5d72a2;
  background-color: #f8f8f8;
}

/* 美化下拉菜单的选项 */
.language-switcher option {
  padding: 10px;
  background-color: #f2f2f2;
  border: none;
  font-size: 16px;
}
</style>