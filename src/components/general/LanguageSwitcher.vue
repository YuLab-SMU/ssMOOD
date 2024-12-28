<template>
  <div class="language-switcher-container">
    <a>Language</a>
    <select v-model="selectedLanguage" @change="switchLanguage" class="language-switcher">
      <option value="zh1">简体中文</option>
      <option value="zh2">繁體中文</option>
      <option value="en">English</option>
      <option value="es">español</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'LanguageSwitcher',
  data() {
    return {
      selectedLanguage: this.loadLanguagePreference() || 'zh1',
    };
  },
  methods: {
    switchLanguage() {
      this.saveLanguagePreference(this.selectedLanguage);
      this.$emit('languageChanged', this.selectedLanguage);
    },
    loadLanguagePreference() {
      return localStorage.getItem('selectedLanguage');
    },
    saveLanguagePreference(language) {
      localStorage.setItem('selectedLanguage', language);
    },
  },
  mounted() {
    this.$emit('languageChanged', this.selectedLanguage);
  },
};
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