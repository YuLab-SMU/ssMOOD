import { createRouter, createWebHashHistory } from 'vue-router';
import NProgress from 'nprogress'; // 引入 NProgress
import 'nprogress/nprogress.css';  // 引入 NProgress 样式

import HomeView from '../components/HomeView.vue';
import AnalyzePage from '../components/AnalyzePage.vue'; 
//import BrowsePage from '../components/BrowsePage.vue'; 


import scListPage from '../components/scListPage.vue'; 
import scdDetailPage from '../components/scdDetailPage.vue'; 
import scsDetailPage from '../components/scsDetailPage.vue'; 

import stListPage from '../components/stListPage.vue'; 
import stdDetailPage from '../components/stdDetailPage.vue'; 


//import SearchPage from '../components/SearchPage.vue'; 
import DownloadPage from '../components/DownloadPage.vue'; 
import AboutPage from '../components/AboutPage.vue';

const routes = [
  { path: '/', name: 'Home', component: HomeView, meta: { requiresLoading: true } },
  //{ path: '/browse', name: 'Browse', component: BrowsePage, meta: { requiresLoading: true } },
  { path: '/analyze', name: 'Analyze', component: AnalyzePage, meta: { requiresLoading: true } },
  { path: '/browse/singlecell', name: 'SingleCellList', component: scListPage, meta: { requiresLoading: true } },
  { path: '/browse/spatialtranscriptome', name: 'SpatialTranscriptomeList', component: stListPage, meta: { requiresLoading: true } },
  //{ path: '/search', name: 'Search', component: SearchPage, meta: { requiresLoading: true } },
  { path: '/download', name: 'Download', component: DownloadPage, meta: { requiresLoading: true } },
  { path: '/about', name: 'About', component: AboutPage, meta: { requiresLoading: true } },
  { path: '/browse/singlecell/:id', name: 'scdDetail', component: scdDetailPage, meta: { requiresLoading: true } },
  { path: '/browse/singlecellstudy/:study', name: 'scsDetail', component: scsDetailPage, meta: { requiresLoading: true } },
  { path: '/browse/spatialtranscriptome/:id', name: 'stdDetail', component: stdDetailPage, meta: { requiresLoading: true } }
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { left: 0, top: 0 };
    }
  }
});

router.beforeEach((to, from, next) => {
  NProgress.start(); // 开始加载进度条
  next(); // 确保调用 next() 以继续路由导航
});

router.afterEach(() => {
  NProgress.done(); // 完成加载进度条
});

export default router;