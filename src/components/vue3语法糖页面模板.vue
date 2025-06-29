<template>
  <div>
    <header>
        <!-- 导航栏 -->
        <nav>
            <div class="logo"><img src="@/assets/logo.png" alt="Logo"></div>
            <ul>
                <li><router-link to="/">{{ $t('home') }}</router-link></li>
                <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="has-submenu NowPage"> <a>{{ $t('browse') }}</a>
                    <ul v-if="showSubMenu" class="submenu">
                        <li><router-link to="/browse/singlecell">{{ $t('browse-SingleCell') }}</router-link></li>
                        <li><router-link to="/browse/spatialtranscriptome">{{ $t('browse-SpatialTranscriptome') }}</router-link></li>
                    </ul>
                </li>
                <li><router-link to="/analyze">{{ $t('analyze') }}</router-link></li>
                <li><router-link to="/download">{{ $t('download') }}</router-link></li>
                <li><router-link to="/about">{{ $t('about') }}</router-link></li>
            </ul>
            <LanguageSwitcher @languageChanged="onLanguageChanged" /><!-- 翻译组件 -->
        </nav>
    </header>

    <main>
      <section class="page-section">
          <!-- 页面主体在这 -->
      </section>
    </main>
    <back-to-top /><!-- 返回顶部组件 -->
  </div>
</template>

<script setup>
import { ref, onMounted} from 'vue';


//----------以下为一个ssmood页面需要的最基础的东西--------------
import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
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

</script>

<style scoped>
@import 'css/MainStyles.css';
</style>
