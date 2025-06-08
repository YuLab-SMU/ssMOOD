<template>
  <header>
    <nav class="nav-container">
    <div class="toggle-button" @click="toggleMenu">
        &#9776;
      </div>
      <div class="logo">
        <img src="@/assets/logo.png" alt="Logo">
      </div>

      <ul class="nav-menu" :class="{ 'nav-menu-collapsed': isMenuCollapsed }">
        <li class="nav-item" :class="{ 'active': $route.path === '/' }">
          <router-link to="/">{{ $t('home') }}</router-link>
        </li>
        <li
          @mouseover="showSubMenu = true"
          @mouseleave="showSubMenu = false"
          class="nav-item has-submenu"
          :class="{ 'active': $route.path === '/browse' }"
        >
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
        <li class="nav-item">
          <router-link to="/analyze">{{ $t('analyze') }}</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/download">{{ $t('download') }}</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/about">{{ $t('about') }}</router-link>
        </li>
      </ul>
      <LanguageSwitcher @languageChanged="onLanguageChanged" ></LanguageSwitcher>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import LanguageSwitcher from './LanguageSwitcher.vue';
import { useI18n } from 'vue-i18n';

const showSubMenu = ref(false);
const isMenuCollapsed = ref(false);

const { locale } = useI18n();

const onLanguageChanged = (language) => {
  locale.value = language;
  window.localStorage.setItem('selectedLanguage', language);
};

onMounted(async () => {
  const selectedLanguage = window.localStorage.getItem('selectedLanguage') || 'zh1';
  locale.value = selectedLanguage;

});


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
}
/* 添加active样式 */
.nav-item.active {
  background: rgba(255, 255, 255, 0.2);
}

/* 二级菜单中的active项 */
.submenu li.active {
  background: rgba(255, 255, 255, 0.1);
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
  top: 100%;
  left: 0;
  background: white;
  min-width: 200px;
  list-style: none;
  padding: 10px 0;
  margin: 0;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.submenu li a {
  padding: 10px 15px;
  white-space: nowrap;
}

/* 移动端样式 */
.toggle-button {
  font-size: 24px;
}

@media (max-width: 768px) {
  .toggle-button {
    display: block;
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
  

}
</style>