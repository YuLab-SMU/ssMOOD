<template>
    <div>
        <header>
            <nav>
                <div class="logo">
                    <img src="@/assets/logo.png" alt="Logo">
                </div>
                <ul>
                    <li class="NowPage">
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
            
                

                    <li>
                        <router-link to="/analyze">{{ $t('analyze') }}</router-link>
                    </li>
<!--
                    <li>
                        <router-link to="/search">{{ $t('search') }}</router-link>
                    </li>
-->
                    <li>
                        <router-link to="/download">{{ $t('download') }}</router-link>
                    </li>
                    <li>
                        <router-link to="/about">{{ $t('about') }}</router-link>
                    </li>
                    
                    <li>
                        </li>
                </ul>
                    <LanguageSwitcher @languageChanged="onLanguageChanged" />
            </nav>
        </header>
        <main>
            <section class="page-section">
                <div class="main-con">
                <div class="Top-container" @scroll="handleScroll">
                  <div v-if="isVisible" class="back-to-top" @click="scrollToTop">▲</div>
                <div class="container container1">
                <div class="test">
                    <img src="@/assets/ssmood.jpg" alt="Test Image">
                </div>
                <div class="image-text-container">
                    <div class="image-text-item">
                        <img src="@/assets/image1.png" alt="Image 1">
                        
                        <p>{{ $t('hv3') }}
                        <br>{{ clusterNum }}</p>
                    </div>

                    <div class="image-text-item">
                        <img src="@/assets/image3.png" alt="Image 3">
                            <p>{{ $t('hv1') }}
                            <br>{{ cellNum }}</p>
                    </div>
                    <div class="image-text-item">
                        <img src="@/assets/image4.png" alt="Image 4">
                        <p>{{ $t('hv4') }}
                            <br>{{ spatialNum }}</p>
                    </div>
                                        <div class="image-text-item">
                        <img src="@/assets/image2.png" alt="Image 2">
                        <p>{{ $t('hv2') }}
                            <br>{{ geneNum }}</p>
                    </div>
                </div>
            </div>
                <div class="container">
                    <div class="text">
                         <h2>{{ $t('hv5') }}</h2>
                        <p>{{ $t('hv6') }}</p>
                    </div>
                    <div class="image">
                        <div class="chart-switch">
                              <div
                                class="chart-item"
                                :class="{ 'active': currentChart === 0 }"
                                @click="switchChart(0)"
                              >
                                <div class="chart-dot"></div>
                                <span>单细胞(鼠)</span>
                              </div>
                              <div
                                class="chart-item"
                                :class="{ 'active': currentChart === 1 }"
                                @click="switchChart(1)"
                              >
                                <div class="chart-dot"></div>
                                <span>空转(鼠)</span>
                              </div>
                              <div
                                class="chart-item"
                                :class="{ 'active': currentChart === 2 }"
                                @click="switchChart(2)"
                              >
                                <div class="chart-dot"></div>
                                <span>单细胞(人)</span>
                              </div>
                            <div
                                class="chart-item"
                                :class="{ 'active': currentChart === 3 }"
                                @click="switchChart(3)"
                              >
                                <div class="chart-dot"></div>
                                <span>空转(人)</span>
                              </div>
                        </div>
                        <div id="myClusterChart" style="width: 900px; height: 400px;"></div>
                    </div>
                    
                </div>
                <div class="container" data-special="true">
                    <div class="image">
                        <div id="genderChart" style="width: 400px; height: 400px;"></div>
                    </div>
                    <div class="text">
                        <div id="genderChart2" style="width: 400px; height: 400px;"></div>
                    </div>
                    <div class="text">
                         <h2>{{ $t('hv7') }}</h2>
                        <p>{{ $t('hv8') }}</p>
                    </div>

                </div>
                <div class="container">
                    <div class="text">
                         <h2>{{ $t('hv9') }}</h2>
                        <p>{{ $t('hv10') }}</p>
                    </div>
                    <div class="image">
                        <div class="chart-switch">
                              <div
                                class="chart-item"
                                :class="{ 'active': currentUmapChart === 0 }"
                                @click="switchUmapChart(0)"
                              >
                                <div class="chart-dot"></div>
                                <span>单细胞(鼠)</span>
                              </div>
                              <div
                                class="chart-item"
                                :class="{ 'active': currentUmapChart === 1 }"
                                @click="switchUmapChart(1)"
                              >
                                <div class="chart-dot"></div>
                                <span>空转(鼠)</span>
                              </div>
                            <div
                                class="chart-item"
                                :class="{ 'active': currentUmapChart === 2 }"
                                @click="switchUmapChart(2)"
                              >
                                <div class="chart-dot"></div>
                                <span>单细胞(人)</span>
                              </div>
                                                            <div
                                class="chart-item"
                                :class="{ 'active': currentUmapChart === 3 }"
                                @click="switchUmapChart(3)"
                              >
                                <div class="chart-dot"></div>
                                <span>空转(人)</span>
                              </div>
                        </div>
                        <div id="umap-plot"></div>
                    </div>
                </div>
                <div class="container" data-special="true">
                    <div class="image">
                        <div id="volcanoChart"></div>
                    </div>
                    <div class="text">
                         <h2>{{ $t('hv11') }}</h2>
                        <p>{{ $t('hv12') }}</p>
                    </div>
                </div>
                <div class="container">
                        <div class="text">
                             <h2>{{ $t('hv13') }}</h2>
                            <p>{{ $t('hv14') }}</p>
                            <button @click="gotoAnalyzePage" class="goto-button">{{ $t('hv16') }}</button>
                        </div>
                        <div class="image">
                            <canvas id="plot-canvas" width="400px" height="400px"></canvas>
                        </div>
                </div>
                
                </div>
                
            </div>
            
            </section>
        </main>
    </div>
    
   
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import pako from 'pako';
import { ref, onMounted,onUnmounted} from 'vue';
import { useRouter } from 'vue-router';

//----------以下为一个ssmood页面需要的最基础的东西--------------
import { useI18n } from 'vue-i18n';
//import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import config from '@/config';
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



//-------------------
//处理定位滚动逻辑
//-------------------
import { throttle } from 'lodash';

const handleScroll = (event) => {
  const topContainer = event.target;
  const containers = Array.from(topContainer.querySelectorAll('.container'));
  const currentScrollPosition = topContainer.scrollTop;

  let closestContainer = containers[0];
  let minDistance = Math.abs(currentScrollPosition - closestContainer.offsetTop);

  containers.forEach((container) => {
    const distance = Math.abs(currentScrollPosition - container.offsetTop);
    if (distance < minDistance) {
      minDistance = distance;
      closestContainer = container;
    }
  });

  // 添加动画类
  containers.forEach((container) => {
    container.classList.remove('fade-in');
  });
  closestContainer.classList.add('fade-in');
};

throttle(handleScroll, 300); // 限制每100ms内只执行一次
//-------------------
//返回顶部逻辑
//-------------------
const isVisible = ref(false);

const scrollToTop = () => {
  const topContainer = document.querySelector('.Top-container');
  const firstContainer = topContainer.querySelector('.container');
  
  if (firstContainer) {
    topContainer.scrollTo({
      top: firstContainer.offsetTop,
      behavior: 'smooth'
    });
  }
};



const handleTopScroll = () => {
  const topContainer = document.querySelector('.Top-container');
  if (topContainer) {
    isVisible.value = topContainer.scrollTop > 300; // 显示按钮的触发高度
  }
};

onMounted(() => {
  const topContainer = document.querySelector('.Top-container');
  topContainer?.addEventListener('scroll', handleTopScroll);
});

onUnmounted(() => {
  const topContainer = document.querySelector('.Top-container');
  topContainer?.removeEventListener('scroll', handleTopScroll);
});
//-------------------
//获取数据库基础信息
//-------------------
// 初始化数据
onMounted(async () => {
  try {
    const response = await fetch(config.apiUrl + 'hv_dbInfo.php'); // 示例 API
    const data = await response.json();
    cellNum.value = data.cellNum;
    geneNum.value = data.geneNum;
    clusterNum.value = data.clusterNum;
    spatialNum.value = data.spatialNum;
  } catch (error) {
    console.error('Error loading data:', error);
  }
});



const currentChart = ref(0); // 当前显示的图表索引
const cellNum = ref(0);
const geneNum = ref(0);
const clusterNum = ref(0);
const spatialNum = ref(0);

// 缓存图表数据
const chartDataCache = ref([]);

const renderChart_title = ['snRNA-seq Datasets(Mouse)','ST Datasets(Mouse)','snRNA-seq Datasets(Human)','ST Datasets(Human)']

// 绘制图表
const renderChart = (index) => {
  const data = chartDataCache.value[index];
  if (!data) return; // 如果没有数据，直接返回

  // 提取标签和细胞数量
  const labels = Object.keys(data);
  const cellCounts = Object.values(data);

  // 创建条形图数据
  const trace = {
    x: labels,
    y: cellCounts,
    type: 'bar',
    marker: {
      color: 'rgba(93, 116, 162, 0.6)',
      line: {
        color: 'rgba(93, 116, 162, 1)',
        width: 1
      }
    }
  };

  // 创建布局
  const layout = {
    title: renderChart_title[index],
    xaxis: {
      title: '',
      tickangle: -45, // 将标签旋转45度
      tickmode: 'linear', // 确保标签均匀分布
      tickfont: { size: 8 } // 调整字体大小
    },
    yaxis: {
      title: '',
      showgrid: true,
      zeroline: true
    }
  };

  // 绘制图表
  Plotly.newPlot('myClusterChart', [trace], layout);
};

// 切换图表
const switchChart = (index) => {
    currentChart.value = index;
    renderChart(index);
};


// 加载数据
onMounted(async () => {
  try {
    const response = await fetch(config.apiUrl + 'hv_datasets.php');
    const arrayBuffer = await response.arrayBuffer();
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed);
    const jsonString = new TextDecoder('utf-8').decode(decompressed);
    const data = JSON.parse(jsonString);
    chartDataCache.value = data;
    renderChart(0); // 默认渲染第0张表
  } catch (error) {
    console.error('Error fetching and decompressing UMAP data:', error);
  }
});



//-------------------
//获取物种比例
//-------------------
onMounted(async() => {
    fetch(config.apiUrl+'hv_gender_data.php')
        .then(response => response.json())
        .then(data => {
        const genderData = [{
                labels: ['Human', 'Mouse'],
                values: [data.human, data.mouse],
                    type: 'pie',
                    marker: {
                        colors: ['rgba(119, 181, 106 ,0.8)', 'rgba(232, 136, 084 ,0.8)'],
                        line: {
                            color: 'white',
                            width: 2
                        }
                    },
                    textinfo: 'label+percent',
                    hoverinfo: 'label+percent',
                    hole: 0.4,
                    pull: [0.05, 0],
                }
            ];
        
            const layout = {
                title: {
                    text: 'Species Ratio',
                    font: {
                        family: 'Arial, sans-serif',
                        size: 20,
                        color: 'black'
                    }
                },
                height: 400,
                width: 400,
                paper_bgcolor: 'rgba(0,0,0,0)', // 设置图表背景透明
                plot_bgcolor: 'rgba(0,0,0,0)', // 设置绘图区域背景透明
                margin: {
                    l: 50,
                    r: 50,
                    b: 100,
                    t: 80
                },
                showlegend: true,
                legend: {
                    x: 0.3,
                    y: -0.5, // 将图例放在下方中间
                    orientation: 'w', // 图例水平排列
                    bgcolor: 'rgba(0,0,0,0)',
                    bordercolor: 'grey',
                    borderwidth: 1
                }
            };
        
            Plotly.newPlot('genderChart', genderData, layout);
        })
        .catch (error => console.error('Error fetching gender data:', error));
});
//-------------------
//获取单细胞和空转比例
//-------------------
onMounted(async() => {
    fetch(config.apiUrl+'hv_scandstRaio.php')
        .then(response => response.json())
        .then(data => {
        const genderData = [{
                labels: ['Single Cell', 'Spatial Transcriptome'],
                values: [data.sc_cells_sum, data.st_cells_sum],
                    type: 'pie',
                    marker: {
                        colors: ['rgba(93, 116, 162 ,0.8)', 'rgba(142, 45, 48 ,0.8)'],
                        line: {
                            color: 'white',
                            width: 2
                        }
                    },
                    textinfo: 'label+percent',
                    hoverinfo: 'label+percent',
                    hole: 0.4,
                    pull: [0.05, 0],
                }
            ];
        
            const layout = {
                title: {
                    text: 'Cells Ratio',
                    font: {
                        family: 'Arial, sans-serif',
                        size: 20,
                        color: 'black'
                    }
                },
                height: 400,
                width: 400,
                paper_bgcolor: 'rgba(0,0,0,0)', // 设置图表背景透明
                plot_bgcolor: 'rgba(0,0,0,0)', // 设置绘图区域背景透明
                margin: {
                    l: 50,
                    r: 50,
                    b: 100,
                    t: 80
                },
                showlegend: true,
                legend: {
                    x: 0.2,
                    y: -0.5, // 将图例放在下方中间
                    bgcolor: 'rgba(0,0,0,0)',
                    bordercolor: 'grey',
                    borderwidth: 1
                }
            };
        
            Plotly.newPlot('genderChart2', genderData, layout);
        })
        .catch (error => console.error('Error fetching gender data:', error));
});
//------------------------
//获取单细胞和空转的umap
//------------------------

const currentUmapChart = ref(0); // 默认显示第0张表
const umapData = ref({}); // 存储4张表的数据
const title_x= ["UMAP1","coordinate_X","UMAP1","coordinate_X"];
const title_y= ["UMAP2","coordinate_Y","UMAP2","coordinate_Y"];

const pointSize = [5,2.5,5,3]

// 切换UMAP图表
const switchUmapChart = (index) => {
  currentUmapChart.value = index;
  renderUmapChart(index);
};


// 渲染UMAP图表
const renderUmapChart = (index) => {
  const data = umapData.value[index];
  if (!data) return;

  const oneUmapData = data.umap;
  const clusterLabels = data.cluster;

  const sortedClusterLabels = [...clusterLabels].sort((a, b) => {
        const partsA = a.match(/\d+/);
        const partsB = b.match(/\d+/);
        if (partsA && partsB) {
            // 如果两个字符串都包含数字，则按数字排序
            return parseInt(partsA[0], 10) - parseInt(partsB[0], 10);
        } else if (partsA) {
            // 如果只有 a 包含数字，则 a 排在 b 前面
            return -1;
        } else if (partsB) {
            // 如果只有 b 包含数字，则 b 排在 a 前面
            return 1;
        } else {
            // 如果两个字符串都不包含数字，则按字母顺序排序
            return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
        }
    });
  // 使用标签的哈希值生成颜色，确保相同标签颜色一致
  const colors = sortedClusterLabels.reduce((acc, label) => {
    // 使用简单的哈希函数生成颜色
    let hash = 0;
    for (let i = 0; i < label.length; i++) {
      hash = ((hash << 5) - hash) + label.charCodeAt(i);
      hash = hash & hash; // 转换为32位整数
    }
    const hue = Math.abs(hash % 360);
    const lightness = 70 + (hash % 2 === 0 ? 5 : -5);
    acc[label] = `hsl(${hue}, 40%, ${lightness}%)`;
    return acc;
  }, {});

  const umap1 = oneUmapData.map(d => parseFloat(d.u1));
  const umap2 = oneUmapData.map(d => parseFloat(d.u2));
  const cellIds = oneUmapData.map(d => d.i);
  const clusterLabelsData = oneUmapData.map(d => d.c);

  const traces = sortedClusterLabels.map((label) => {
    const x = umap1.filter((_, i) => clusterLabelsData[i] === label);
    const y = umap2.filter((_, i) => clusterLabelsData[i] === label);
    const text = cellIds.filter((_, i) => clusterLabelsData[i] === label);

    return {
      x: x,
      y: y,
      mode: 'markers',
      type: 'scattergl',
      name: label,
      text: text,
      marker: {
        size: pointSize[index],
        color: colors[label]
      }
    };
  });

  const layout = {
    title: '',
    showlegend: false,
    xaxis: {
      title: title_x[index]
    },
    yaxis: {
      title: title_y[index]
    },
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)',
  };

  Plotly.newPlot('umap-plot', traces, layout);
};

// 加载数据
onMounted(async () => {
  try {
    const response = await fetch(config.apiUrl + 'hv_umap.php');
    const arrayBuffer = await response.arrayBuffer();
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed);
    const jsonString = new TextDecoder('utf-8').decode(decompressed);
    const data = JSON.parse(jsonString);

    umapData.value = {
      0: data[0], // 单细胞(鼠)
      1: data[1], // 空转(鼠)
      2: data[2], // 单细胞(人)
      3: data[3]  // 空转(人)
    };
    
    renderUmapChart(0); // 默认渲染第0张表
  } catch (error) {
    console.error('Error fetching and decompressing UMAP data:', error);
  }
});

//------


//------------------------
//跳转到分析页面
//------------------------
const router = useRouter();
const gotoAnalyzePage = () => {
  router.push('/analyze'); // 替换 '/target-page' 为目标路由路径
};

</script>

<style scoped>
@import 'css/MainStyles.css';
.main{
    overflow-y: hidden;
}
.page-section{
  padding: 0px;
  flex: 1; /* 占据剩余的空间 */
}
.Top-container {
  overflow-y: scroll;
  height: 92vh;
  scroll-snap-type: y mandatory; /* 启用滚动捕获 */
}
/* 滚动条整体宽度 */
.Top-container::-webkit-scrollbar {
  width: 10px; /* 宽度 */
}

/* 滚动条的轨道背景 */
.Top-container::-webkit-scrollbar-track {
  background: #f1f1f1; /* 轨道背景颜色 */
  border-radius: 10px; /* 轨道圆角 */
}

/* 滚动条的滑块（可拖动部分） */
.Top-container::-webkit-scrollbar-thumb {
  background: rgba(093, 116, 162, 0.8); /* 滑块颜色 */
  border-radius: 10px; /* 滑块圆角 */
}

/* 滑块悬停时的样式 */
.Top-container::-webkit-scrollbar-thumb:hover {
  background: #555; /* 悬停时的颜色 */
}
.container {
    scroll-snap-align: start; /* 捕获到容器的起始位置 */
    display: flex;
    justify-content: center; /* 水平居中 */
    align-items: center; /* 垂直居中 */
    height: 92vh;
    width: 100%;
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}

.fade-in {
  animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
.container1 {
  display: flex;
  flex-direction: column; /* 子元素垂直排列 */
  justify-content: center; /* 水平居中（如果子元素是行内元素） */
  align-items: center; /* 垂直居中 */
}

.container[data-special="true"] {
    background-color: rgba(84, 164, 255, 0.1);
}
.container .image {
    margin-right: 20px;
    /* 图片与文字之间的间距 */
}
.container .text {
    max-width: 400px;
    /* 限制文字宽度为300px */
    /* 或使用 width 代替 max-width 来设定固定宽度 */
    word-wrap: break-word;
    /* 自动换行 */
}
.container.fade-in {
  opacity: 1;
  transform: translateY(0);
}
.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: rgb(093, 116, 162);
  color: #fff;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  opacity: 0.8;
  transition: all 0.3s ease;
  z-index:1000;
}

.back-to-top:hover {
  opacity: 1;
  transform: scale(1.1);
}

.test {
    display: flex;
    justify-content: center; /* 水平居中 */
    align-items: flex-start; /* 从容器顶部开始对齐 */
    margin: 0; /* 容器没有额外间距 */
    position: relative; /* 设置相对定位 */
}

.test img {
    width: 80%; /* 图片宽度不超过容器宽度 */
    height: auto; /* 保持图片的纵横比 */
    position: relative; /* 设置相对定位 */
    top: 20px; /* 图片距离顶部 20px */
}

.chart-switch {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.chart-item {
  display: flex;
  align-items: center;
  cursor: pointer;
  margin: 0 10px;
}

.chart-dot {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background-color: #ccc;
  margin-right: 8px; /* 点和文字之间的间距 */
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.chart-item.active .chart-dot {
  background-color: rgb(093, 116, 162);
  animation: jump 0.5s forwards;
}

@keyframes jump {
  0% {
    transform: scale(1);
  }
  50% {
    transform: translateY(-10px) scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}

.chart-item span {
  font-size: 14px;
  color: #333;
}

.chart-item.active span {
  color: rgb(093, 116, 162);
}

@keyframes jump {
  0% {
    transform: scale(1);
  }
  50% {
    transform: translateY(-10px) scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}
.image-text-container {
    display: flex;
    flex-wrap: wrap; /* 在较小的屏幕上换行 */
    justify-content: space-between; /* 水平分布图片和文字 */
    gap:5vw;
}

.image-text-item {
    display: flex;
    align-items: center; /* 图片和文字在垂直方向上居中 */
    margin: 10px;
    text-align: left; /* 文字左对齐 */
    max-width: calc(25% - 20px); /* 确保项目在较小屏幕上能适当排列，计算每个项目的最大宽度 */
}

.image-text-item img {
    max-width: 100px; /* 设置图片的最大宽度 */
    height: auto; /* 保持图片的纵横比 */
    margin-right: 10px; /* 图片和文字之间的间距 */
}

.image-text-item p {
    margin: 0; /* 文字的外边距为零，防止额外间距 */
}



#umap-plot {
    width: 600px;
    height: 600px;
}
#volcanoChart {
    width: 500px;
    height: 500px;
}
.goto-button {
  padding: 10px 20px;
  margin-top: 10px;
  background-color: rgb(093, 116, 162); 
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer; /* 鼠标悬停时显示手型指针 */
  outline: none; /* 移除焦点时的轮廓 */
}

.execute-button:hover {
  background-color: rgba(093, 116, 162, 0.8); /* 鼠标悬停时的背景颜色 */
}



 /* ----------------------------------------------------------- */
  /* 竖屏响应*/
 @media (orientation: portrait) or (max-width: 800px) {
    .image-text-container {
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* 在图片和文字之间均匀分布空间 */
        height: auto;
        width: auto;
        gap: 5px;
    }
    
    .image-text-container p {
        white-space: nowrap; /* 阻止文本换行 */
    }
    .container .text {
            max-width: 800px; /* 限制文字宽度为300px */
            /* 或使用 width 代替 max-width 来设定固定宽度 */
        }
    .container {
        flex-direction: column;
        display: flex;
        justify-content: center; /* 水平居中 */
        align-items: center; /* 垂直居中 */
        height: auto; /* 使外部容器高度为全屏高度 */
        width: 100%;
    }
    .Top-container{
        width: 100%;
        flex-direction: column;
        display: flex;
        align-items: center; /* 垂直居中 */
        justify-content: center; /* 水平居中 */
        gap: 50px;
    }
    .main-con{
        width: 100%;
        gap: 15px;
    }
    #umap-plot {
        width: 100%; /* 宽度填满屏幕 */
        height: auto; /* 高度自动调整 */
    }
    #volcanoChart {
        width: 100%; /* 宽度填满屏幕 */
        height: auto; /* 高度自动调整 */
    }
}

</style>
