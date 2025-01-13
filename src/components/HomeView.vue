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
                <div class="test">
                    <img src="@/assets/Test.png" alt="Test Image">
                </div>
                <div class="image-text-container">
                    <div class="image-text-item">
                        <img src="@/assets/image1.png" alt="Image 1">
                        <p>{{ $t('hv1') }}
                            <br>{{ cellNum }}</p>
                    </div>
                    <div class="image-text-item">
                        <img src="@/assets/image2.png" alt="Image 2">
                        <p>{{ $t('hv2') }}
                            <br>{{ geneNum }}</p>
                    </div>
                    <div class="image-text-item">
                        <img src="@/assets/image3.png" alt="Image 3">
                        <p>{{ $t('hv3') }}
                            <br>{{ clusterNum }}</p>
                    </div>
                    <div class="image-text-item">
                        <img src="@/assets/image4.png" alt="Image 4">
                        <p>{{ $t('hv4') }}
                            <br>{{ spatialNum }}</p>
                    </div>
                </div>
                <div class="Top-container">
                <div class="container">
                    <div class="text">
                         <h2>{{ $t('hv5') }}</h2>
                        <p>{{ $t('hv6') }}</p>
                    </div>
                    <div class="image">
                        <div id="myClusterChart" style="width: 600px; height: 500px;"></div>
                    </div>
                </div>
                <div class="container" data-special="true">
                    <div class="image">
                        <div id="genderChart" style="width: 400px; height: 400px;"></div>
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
                <BackToTop />
                </div>
            </div>
            </section>
        </main>
    </div>
    
   
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import pako from 'pako';
import { ref, onMounted} from 'vue';
import { useRouter } from 'vue-router';

//----------以下为一个ssmood页面需要的最基础的东西--------------
import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
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
//获取数据库基础信息
//-------------------

const cellNum = ref(0);
const geneNum = ref(0);
const clusterNum = ref(0);
const spatialNum = ref(0);

onMounted(async() => {
    try {
        const response = await fetch(config.apiUrl+'hv_dbInfo.php'); // 示例 API
        const data = await response.json();
        cellNum.value = data.cellNum;
        geneNum.value = data.geneNum;
        clusterNum.value = data.clusterNum;
        spatialNum.value = data.spatialNum;
    } catch (error) {
        console.error('Error loading data:', error);
    }
    
    // 绘制各数据集分布
    fetch(config.apiUrl+'hv_chart1.php')
      .then(response => response.json())
      .then(dataFromPhp => {
        // 提取标签和细胞数量
        const labels = Object.keys(dataFromPhp);
        const cellCounts = Object.values(dataFromPhp);
    
        // 创建条形图数据
        const trace = {
          x: labels,
          y: cellCounts,
          type: 'bar',
          marker: {
            color: 'rgba(93, 116, 162 ,0.6)',
            line: {
              color: 'rgba(93, 116, 162 ,1)',
              width: 1
            }
          }
        };
    
        // 创建布局
        const layout = {
          title: '各分类样本数量',
          xaxis: {
            title: ''
          },
          yaxis: {
            title: '',
            showgrid: true,
            zeroline: true
          }
        };
    
        // 绘制图表
        Plotly.newPlot('myClusterChart', [trace], layout);
      })
      .catch(error => console.error('Error fetching data from chart1.php:', error));
});
//-------------------
//获取数据库比例
//-------------------
onMounted(async() => {
    fetch(config.apiUrl+'hv_gender_data.php')
        .then(response => response.json())
        .then(data => {
        const genderData = [{
                labels: ['男性', '女性'],
                values: [data.male, data.female],
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
                    text: '性别比例',
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
                    b: 50,
                    t: 80
                },
                showlegend: true,
                legend: {
                    x: 1,
                    y: 0.5,
                    bgcolor: 'rgba(0,0,0,0)',
                    bordercolor: 'grey',
                    borderwidth: 1
                }
            };
        
            Plotly.newPlot('genderChart', genderData, layout);
        })
        .catch (error => console.error('Error fetching gender data:', error));
});

//------------------------
//获取单细胞和空转的umap
//------------------------

onMounted(async() => {
 fetch(config.apiUrl+'hv_umap.php')
          .then(response => response.arrayBuffer()) // 获取ArrayBuffer响应
          .then(arrayBuffer => {
            const compressed = new Uint8Array(arrayBuffer);
            const decompressed = pako.ungzip(compressed); // 使用pako解压
            const jsonString = new TextDecoder('utf-8').decode(decompressed);
            const data = JSON.parse(jsonString); // 解析JSON字符串
        
            const umapData = data.umap;
            const clusterLabels = data.cluster;
            
            const labelMap = new Map();
            clusterLabels.forEach((label, index) => {
                labelMap.set(label, index);
            });
        
            // 对clusterLabels进行排序
            clusterLabels.sort((a, b) => {
              const partsA = a.match(/\d+/)[0];
              const partsB = b.match(/\d+/)[0];
              return parseInt(partsA, 10) - parseInt(partsB, 10);
            });
        
            const umap1 = umapData.map(d => parseFloat(d.u1));
            const umap2 = umapData.map(d => parseFloat(d.u2));
            const cellIds = umapData.map(d => d.i);
            const clusterLabelsData = umapData.map(d => d.c);
        
            const colors = Array.from(labelMap.keys()).reduce((acc, label, index) => {
                const hue = (index * 360 / labelMap.size);
                const lightness = 70 + (index % 2 === 0 ? 5 : -5);
                acc[label] = `hsl(${hue}, 40%, ${lightness}%)`;
                return acc;
            }, {});
        
            const traces = clusterLabels.map((label) => {
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
                  size: 2,
                  color: colors[label]
                }
              };
            });
        
            const layout = {
              title: '',
              xaxis: { title: 'UMAP1' },
              yaxis: { title: 'UMAP2' },
              paper_bgcolor: 'rgba(0,0,0,0)',
              plot_bgcolor: 'rgba(0,0,0,0)',
              legend: {
                orientation: 'h',
                xanchor: 'center',
                yanchor: 'bottom',
                x: 1.5,
                y: 0.3
              }
            };
        
            Plotly.newPlot('umap-plot', traces, layout);
          })
          .catch(error => console.error('Error fetching and decompressing UMAP data:', error));
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

.test {
    display: flex;
    justify-content: center; /* 水平居中 */
    align-items: flex-start; /* 靠上对齐 */
    /*height: 100vh;  使容器高度为视口高度，以便垂直居中 */
}

.test img {
    width: 100%; /* 图片宽度不超过容器宽度 */
    height: auto; /* 保持图片的纵横比 */
}
.image-text-container {
    display: flex;
    flex-wrap: wrap; /* 在较小的屏幕上换行 */
    justify-content: space-between; /* 水平分布图片和文字 */
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
.container {
            display: flex;
            justify-content: center; /* 水平居中 */
            align-items: center; /* 垂直居中 */
            height: 80vh; /* 使外部容器高度为全屏高度 */
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
#umap-plot {
    width: 900px;
    height: 600px;
}
#volcanoChart {
    width: 500px;
    height: 500px;
}
.execute-button {
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
