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
        <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="nav-item has-submenu"
          :class="{ 'active': $route.path.startsWith('/browse') }">
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
        <!--
        <li class="nav-item" :class="{ 'active': $route.path === '/analyze' }">
          <router-link to="/analyze">{{ $t('analyze') }}</router-link>
        </li>
      -->
        <li class="nav-item" :class="{ 'active': $route.path === '/download' }">
          <router-link to="/download">{{ $t('download') }}</router-link>
        </li>
        <li @mouseover="showSubMenu1 = true" @mouseleave="showSubMenu1 = false" class="nav-item has-submenu"
          :class="{ 'active': $route.path.startsWith('/about') }">
          <a>{{ $t('about') }}</a>
          <ul v-if="showSubMenu1" class="submenu">
            <li>
                <router-link to="/about/doc/Methods">{{ $t('Methods') }}</router-link>
            </li>
            <li>
              <router-link to="/about/doc/Usage">{{ $t('Usage') }}</router-link>
            </li>
            <li>
              <router-link to="/about/doc/News">{{ $t('News') }}</router-link>
            </li>
            <li>
              <router-link to="/about/VisitLog">{{ $t('AccessAnalytics') }}</router-link>
            </li>
            <li>
              <router-link to="/about/ContactUs">{{ $t('ContactUs') }}</router-link>
            </li>
          </ul>
        </li>
      </ul>
      <div class="language-switcher-container">
        <span>Language</span>
        <el-select v-model="selectedLanguage" @change="switchLanguage" placeholder="Select Language" size="default">
          <el-option label="简体中文" value="zh-CN" />
          <el-option label="繁體中文" value="zh-TW" />
          <el-option label="English" value="en" />
        </el-select>
      </div>
    </nav>

  </header>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';


const { locale } = useI18n();
//onst route = useRoute();
const showSubMenu = ref(false);
const showSubMenu1 = ref(false);
const isMenuCollapsed = ref(false);

// 创建响应式引用
const selectedLanguage = ref('');

// 加载语言偏好
const loadLanguagePreference = () => {
  const allowedLangs = ['zh-CN', 'zh-TW', 'en'];

  // 1. 先看 localStorage 里有没有
  const saved = localStorage.getItem('selectedLanguage');
  if (saved && allowedLangs.includes(saved)) {
    return saved;
  }

  // 2. 没有或不合法，就用浏览器语言
  const browserLang = navigator.language.toLowerCase();

  if (browserLang.startsWith('zh-tw') || browserLang.startsWith('zh-hk')) {
    return 'zh-TW'; // 繁体
  } else if (browserLang.startsWith('zh-cn') || browserLang.startsWith('zh')) {
    return 'zh-CN'; // 简体
  } else {
    return 'en'; // 默认英文
  }
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
/*
window.addEventListener('resize', () => {
  if (window.innerWidth <= 768) {
    isMenuCollapsed.value = true;
  } else {
    isMenuCollapsed.value = false;
  }
});
*/
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
  padding: 0 10px;
}

/* 添加active样式 */
.nav-item.active {
  background: rgba(255, 255, 255, 0.3);
}

.nav-item:hover {
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
  top: 100%;
  /* 父菜单项高度的100% */
  left: 0;
  /* 根据需要调整 */
  transition: opacity 0.3s, visibility 0.3s;
  border: 1px solid #eaeaea;
  border-radius: 8px;
  box-shadow: 0px 10px 20px 0px rgba(0, 0, 0, 0.3);

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
  white-space: nowrap;
  /* 禁止文字换行 */
  /* 移除下划线 */
  color: black;
  display: block;
  transition: background 0.3s, color 0.3s;
}

.language-switcher-container {
  padding: 5px;
  margin-right: 80px;
  border: 1px solid #5d72a2;
  border-radius: 4px;
  margin-left: auto;

  /* 不使用flex，改用inline-block水平排列 */
  white-space: nowrap;
  /* 防止换行 */
  text-align: right;
  /* 内容右对齐 */
}

.language-switcher-container>* {
  display: inline-block;
  vertical-align: middle;
  /* 让元素垂直居中 */
  margin-left: 8px;
  /* 控制间距 */
}

.language-switcher-container>*:first-child {
  margin-left: 0;
  /* 第一个元素左边距去掉 */
}

.language-switcher-container a {
  color: white;
}


/* 移动端样式 */
.toggle-button {
  font-size: 24px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1001;
}

.modal {
  background-color: rgba(255, 255, 255, 0.6);
  /* 半透明白色背景 */
  padding: 20px;
  border-radius: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  text-align: center;
  backdrop-filter: blur(5px);
  /* 添加毛玻璃效果，模糊半径为 5px */
  -webkit-backdrop-filter: blur(5px);
  /* Safari 支持 */
  position: relative;
}

.modal p {
  margin-top: 20px;
  margin-bottom: 10px;
  color: black;
}

.modal button {
  padding: 5px 10px;
  color: #aaa;
  font-size: 28px;
  font-weight: bold;
  border: none;
  background-color: rgba(255, 255, 255, 0);
  cursor: pointer;
  right: 0px;
  position: absolute;
  top: 0px;
}

.modal button:hover,
.modal button:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
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
    max-height: 500px;
    /* 足够容纳所有菜单项的高度 */
  }

  .nav-item {
    width: 100%;
    height: auto;
    padding: 10px 0;
  }

  .submenu {
    position: absolute;
    padding: 0px;
    background-color: #fff;
    z-index: 2000;
    top: auto;
    /* 父菜单项高度的100% */
    bottom: 0%;
    left: 50%;
    /* 根据需要调整 */
    transition: opacity 0.3s, visibility 0.3s;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    box-shadow: 0px 10px 20px 0px rgba(0, 0, 0, 0.3);
  }

  .submenu li {
    padding: 0;
    text-decoration: none;
    display: block;
    color: black;
    border-radius: 5px;
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