<template>
  <header>
    <nav class="nav-container">
      <div class="toggle-button" @click="toggleMenu">&#9776;</div>
      <router-link to="/">
        <div class="logo">
          <img src="@/assets/logo.png" alt="Logo">
        </div>
      </router-link>
      <ul class="nav-menu" :class="{ 'nav-menu-collapsed': isMenuCollapsed }">
        <li class="nav-item" :class="{ 'active': $route.path === '/' }">
          <router-link to="/">{{ $t('home') }}</router-link>
        </li>
        <li
          @mouseover="showSubMenu = true"
          @mouseleave="showSubMenu = false"
          class="nav-item has-submenu"
          :class="{ 'active': $route.path.startsWith('/browse') }"
        >
          <a>{{ $t('browse') }}</a>
          <ul v-if="showSubMenu" class="submenu">
            <li>
              <router-link to="/browse/singlecell">{{ $t('browse-SingleCell') }}</router-link>
            </li>
            <li>
              <router-link to="/browse/spatialtranscriptome">{{ $t('browse-SpatialTranscriptome') }}</router-link>
            </li>
          </ul>
        </li>
        <li class="nav-item" :class="{ 'active': $route.path === '/analyze' }">
          <router-link to="/analyze">{{ $t('analyze') }}</router-link>
        </li>
        <li class="nav-item" :class="{ 'active': $route.path === '/download' }">
          <router-link to="/download">{{ $t('download') }}</router-link>
        </li>
        <li class="nav-item" :class="{ 'active': $route.path === '/about' }">
          <router-link to="/about">{{ $t('about') }}</router-link>
        </li>
      </ul>
      <div class="language-switcher-container">
        <a>Language</a>
        <select v-model="selectedLanguage" @change="switchLanguage" class="language-switcher">
          <option value="zh1">简体中文</option>
          <option value="zh2">繁體中文</option>
          <option value="en">English</option>
        </select>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
//import { useRoute } from 'vue-router';

const { locale } = useI18n();
//onst route = useRoute();
const showSubMenu = ref(false);
const isMenuCollapsed = ref(false);

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
  locale.value = selectedLanguage.value;
};

// 在组件挂载时设置初始语言
onMounted(() => {
  selectedLanguage.value = loadLanguagePreference();
  locale.value = selectedLanguage.value;
});

// 监听窗口大小变化，自动折叠或展开导航栏
window.addEventListener('resize', () => {
  if (window.innerWidth <= 768) {
    isMenuCollapsed.value = true;
  } else {
    isMenuCollapsed.value = false;
  }
});

// 切换导航栏的展开和折叠状态
const toggleMenu = () => {
  if (window.innerWidth <= 768) {
    isMenuCollapsed.value = !isMenuCollapsed.value;
  }
};
</script>

<style scoped>
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background: rgb(93, 116, 162);
  color: white;
  z-index: 1000;
  height: 8vh;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.nav-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
  padding: 0 20px;
  position: relative;
}

.logo img {
  height: 4vh;
}

.nav-menu {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
  align-items: center;
  height: 100%;
}

.nav-item {
  position: relative;
  height: 100%;
  display: flex;
  align-items: center;
  padding: 10px;
}
/* 添加active样式 */
.nav-item.active {
  background: rgba(255, 255, 255, 0.2);
}

a {
  color: white;
  text-decoration: none;
  padding: 0 15px;
  display: block;
  height: 100%;
  display: flex;
  align-items: center;
  transition: background 0.3s;
}



/* 二级菜单样式 */
.has-submenu {
  position: relative;
}

.submenu {
  position: absolute; 
  padding: 0px;
  background-color: #fff;
  z-index: 2000;
  top: 100%; /* 父菜单项高度的100% */
  left: 0; /* 根据需要调整 */
  transition: opacity 0.3s, visibility 0.3s;
  border: 1px solid #eaeaea;
  border-radius: 8px;
  box-shadow: 0px 10px 20px 0px rgba(0,0,0,0.3);
}

.submenu.show {
  opacity: 1;
  visibility: visible;
}

.submenu li {
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  color: black;
  border-radius: 5px;
}

.submenu li:hover {
    background-color: rgba(093, 116, 162, 0.3);
}
.submenu a {
    text-decoration: none;
    white-space: nowrap; /* 禁止文字换行 */
    /* 移除下划线 */
    color: black;
    display: block;
    transition: background 0.3s, color 0.3s;
}
.language-switcher-container {
  display: flex;
  align-items: center;
  padding: 5px;
  margin-right: 60px;
  border: 1px solid #5d72a2;
  border-radius: 4px;
  margin-left: auto;
  position: relative; /* 确保下拉菜单定位正确 */
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
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px 16px;
  transition: border-color 0.2s, background-color 0.2s;
  /* 确保下拉菜单在按钮正下方 */
  position: relative;
  top: 0;
  left: 0;
}

.language-switcher:focus {
  border-color: rgb(093, 116, 162);
  background-color: #f8f8f8;
}

/* 美化下拉菜单的选项 */
.language-switcher option {
  padding: 10px;
  background-color: #f2f2f2;
  border: none;
  font-size: 16px;
  color: black; /* 确保选项文本颜色与背景对比明显 */
}

/* 确保下拉菜单在按钮正下方 */
.language-switcher::-ms-expand {
  display: none; /* 隐藏 IE 浏览器的默认下拉箭头 */
}
/* 移动端样式 */
.toggle-button {
  font-size: 24px;
}

@media (max-width: 768px) {
    
.logo {
  padding: 10px 0;
}
.logo img {
  height: 3vh;
}
  .toggle-button {
    align-items: center;
  }
  
  .nav-menu {
    position: fixed;
    top: 60px;
    left: 0;
    width: 100%;
    background: rgb(93, 116, 162);
    flex-direction: column;
    height: auto;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  }
  
  .nav-menu.nav-menu-collapsed {
    max-height: 500px; /* 足够容纳所有菜单项的高度 */
  }
  
  .nav-item {
    width: 100%;
    height: auto;
    padding: 10px 0;
  }
  
  a {
    padding: 12px 20px;
    width: 100%;
  }
.language-switcher-container {
  display: flex;
  align-items: center;
  border: 1px solid #5d72a2;
  border-radius: 4px;
}

.language-switcher-container a {
    font-size: 14px;
  color: white;
  text-decoration: none;
  padding: 0px 0px;
}

.language-switcher {
  padding: 5px 10px;
  font-size: 14px;

}
  
}
</style>