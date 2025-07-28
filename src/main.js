import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import zhCn from './locales/zh-cn.json';
import zhTw from './locales/zh-tw.json';
import es from './locales/es.json';
import mitt from 'mitt';
import { createTerminal } from 'vue-web-terminal';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'; // 引入整个样式文件
import './styles/element-theme.css'

// 创建国际化实例
const i18n = createI18n({
  locale: 'zh1',
  legacy: false,
  globalInjection: true,
  messages: {
    en,
    'zh1': zhCn,
    'zh2': zhTw,
    es
  }
});
const eventBus = mitt();
const app = createApp(App);

app.use(router);
app.use(i18n);

app.use(createTerminal());
app.provide('eventBus', eventBus);

app.use(ElementPlus)
// 挂载应用
app.mount('#app');
window.addEventListener('DOMContentLoaded', () => {
  const loader = document.getElementById('app-loading')
  if (loader) loader.style.display = 'none'
})