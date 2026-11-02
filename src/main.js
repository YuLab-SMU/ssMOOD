import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import zhCn from './locales/zh-cn.json';
import zhTw from './locales/zh-tw.json';
import mitt from 'mitt';
import { createTerminal } from 'vue-web-terminal';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css';
import './styles/element-theme.css';

// Create the i18n instance
const i18n = createI18n({
  locale: 'en',
  legacy: false,
  globalInjection: true,
  messages: {
    en,
    'zh-CN': zhCn,
    'zh-TW': zhTw,
  }
});
const eventBus = mitt();
const app = createApp(App);

app.use(router);
app.use(i18n);

app.use(createTerminal());
app.provide('eventBus', eventBus);

app.use(ElementPlus)
// Mount the app
app.mount('#app');
// Fade out the startup loading screen once the app has mounted (styles live in public/index.html)
const loader = document.getElementById('app-loading')
if (loader) {
  requestAnimationFrame(() => loader.classList.add('done'))
  setTimeout(() => { loader.style.display = 'none' }, 600)
}