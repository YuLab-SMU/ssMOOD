<template>
    <div>
        <header>
            <nav>
                <div class="logo">
                    <img src="@/assets/logo.png" alt="Logo">
                </div>
                <ul>
                    <li>
                        <router-link to="/">{{ $t('home') }}</router-link>
                    </li>
                    <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="has-submenu NowPage"> <a>{{ $t('browse') }}</a>

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
                    <li>
                        <router-link to="/download">{{ $t('download') }}</router-link>
                    </li>
                    <li>
                        <router-link to="/about">{{ $t('about') }}</router-link>
                    </li>
                </ul>
                <LanguageSwitcher @languageChanged="onLanguageChanged" />
            </nav>
        </header>
        <main>
            <section class="page-section">
                <div class="main-container">
<!--##################################################-->      
<!--详细信息容器-->
<div class="information-container">
    <div class="title-bar">
        <h1>{{ $t('std1') }}</h1>
    </div>
    <div class="information-content">
        <div class="information-left">
             <h1>{{ $t('std2') }}</h1>

            <p><span class="bold-black">{{ $t('std3') }}</span>: {{ dataset.dataset_id }}</p>
            <p><span class="bold-black">{{ $t('std4') }}</span>: {{ dataset.species }}</p>
            <p><span class="bold-black">{{ $t('std5') }}</span>: {{ dataset.area}}</p>
            <p><span class="bold-black">{{ $t('std6') }}</span>: {{ dataset.conditions }}</p>
            <p><span class="bold-black">{{ $t('std7') }}</span>: {{ dataset.sex }}</p>
            <p><span class="bold-black">{{ $t('std8') }}</span>: {{ dataset.age }}</p>
            
        </div>
        <div class="information-right">
            <h1>{{ $t('std9') }}</h1>
            <p><span class="bold-black">{{ $t('std10') }}</span>: {{ dataset.information.Publication.Title }}</p>
            <p><span class="bold-black">{{ $t('std11') }}</span>: {{ dataset.information.Publication.DatePublished }}</p>
            <p><span class="bold-black">{{ $t('std12') }}</span>: {{ dataset.information.Publication.Protocol }}</p>
            <p><span class="bold-black">{{ $t('std13') }}</span>: {{ dataset.information.Publication.DataSource }}</p>

        </div>
    </div>
</div>
                    
<!--##################################################-->                    
<!--细胞分类容器-->
<div class="information-container">
  <div class="title-bar">
    <h1>{{ $t('std14') }}</h1>
  </div>
  <div class="st-coord-content">
  <div class="st-information-first">

      <h1>{{ $t('std17') }}</h1>
      <div class="marker-size-controller">
        <div class="label">{{ $t('std18') }}:</div>
        <!-- 减少按钮 -->
        <v-btn class="custom-plus" icon @click="decreaseSize1">
          -
        </v-btn>
        <!-- 显示当前大小 --> 
        <span class="size-value">{{ markerSize1 }}</span>
        <!-- 增加按钮 -->
        <v-btn class="custom-plus" icon @click="increaseSize1">
          +
        </v-btn>
      </div>
      <div id="coord_chart"></div>
  </div>
    <div class="information-second">
        <div id="myClusterChart" style="width: 1200px; height: 300px;"></div>
    </div>
  <!-- ################################ -->
  <!-- 第二列 -->
  <div class="st-information-second">
      <h1>{{ $t('std19') }}</h1>
            <div class="gene-search-con">
        <input
          class="search-gene-input"
          v-model="searchQuery"
          :placeholder="$t('scd24')"
          @input="filterGenes"
          @focus="showScroller = true"
          @blur="handleBlur"
          type="text"
        />
    <!-- 新增高度控制容器 -->
        <div 
          v-show="showScroller"
          class="scroller-wrapper"
          :style="{ height: wrapperHeight + 'px' }"
        >
          <recycle-scroller
            class="scroller"
            :items="filteredGenes"
            :item-size="12"
            :buffer="50"
            page-mode="true"
            :key="resetKey"
          >
            <template v-slot="{ item }">
              <div 
                class="gene-item"
                :class="{ 'is-active': item === searchQuery }"
                @mousedown="selectItem(item)"
              >
                {{ item }}
              </div>
            </template>
          </recycle-scroller>
        </div>
        <v-btn text @click="searchgene" class="search-btn">
          {{ $t('std21') }}
        </v-btn>
      </div>
      <div class="marker-size-controller">
        <div class="label">{{ $t('std20') }}:</div>
        <!-- 减少按钮 -->
        <v-btn class="custom-plus" icon @click="decreaseSize2">
          -
        </v-btn>
        <!-- 显示当前大小 --> 
        <span class="size-value">{{ markerSize2 }}</span>
        <!-- 增加按钮 -->
        <v-btn class="custom-plus" icon @click="increaseSize2">
          +
        </v-btn>
        
        
      </div>
  <div id="coord_chart_gene"></div>
  <div id="expressionHeatmap" style="width: 1200px; height: 400px;"></div>
</div>
</div>
</div>
<!--##################################################-->                  
<!--差异表达分析容器-->
<div class="information-container">
    <div class="title-bar">
         <h1>{{ $t('std22') }}</h1>

    </div>
    <div class="information-content">
        <div class="information-left">
            <div class="de-analysis  modern-ui">
                <div class="note">
                    <p>{{ $t('std23') }}</p>
                </div>
                <div class="group">
                    <label>{{ $t('std24') }}</label>
                    <select v-model="group" class="custom-select">
                        <option value="cellTypeSpecificGenes">One Cell type vs Other Cell types</option>
                    </select>
                </div>
                <div class="cell-type">
                    <label>{{ $t('std25') }}</label>
                    <select v-model="cellType" class="custom-select">
                      <!-- 动态生成选项 -->
                      <option v-for="type in cellTypes" :key="type" :value="type">{{ type }}</option>
                    </select>
                </div>
                <div class="log2fc">
                    <label>{{ $t('std26') }}</label>
                    <input type="range" min="-10" max="5" step="0.1" v-model="log2fc" class="custom-range"/> <span>{{ log2fc }}</span>

                </div>
                <div class="adjusted-pvalue">
                    <label>{{ $t('std27') }}</label>
                    <input type="range" min="0" max="1" step="0.0001" v-model="pvalue" class="custom-range"/> <span>{{ pvalue }}</span>

                </div>
            </div>
        </div>
        
        <div class="information-right">
            
            <div class="de-analysis  modern-ui">
            <input
              class="search-gene-input"
              v-model="filterDEGGenes"
              :placeholder="$t('scd31')"
              type="text"
            />
                <button @click="download" class="search-btn">{{ $t('std28') }}</button>
                <div class="table-container">
                <table>
                  <thead>
                      <tr>
                        <th @click="sortTable(0)">{{ $t('std29') }}</th>
                        <th @click="sortTable(1)">{{ $t('std30') }}</th>
                        <th @click="sortTable(2)">{{ $t('std31') }}</th>
                        <th @click="sortTable(3)">{{ $t('std32') }}</th>
                        <th @click="sortTable(4)">{{ $t('std33') }}</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in paginatedData" :key="item.i">
                      <td>{{ item.i }}</td>
                      <td>{{ item.f.toFixed(6) }}</td> <!-- 保留6位小数 -->
                      <td>{{ item.t1.toFixed(3) }}</td> <!-- 保留3位小数 -->
                      <td>{{ item.t2.toFixed(3) }}</td> <!-- 保留3位小数 -->
                      <td>{{ item.a.toExponential(3) }}</td> <!-- 使用e表示法 -->
                    </tr>
                  </tbody>
                </table>
                <div class="pagination">
                  <button @click="prevPage" :disabled="currentPage === 1" class="page">{{ $t('scd38') }}</button>
                  <span>{{ $t('scd39') }} {{ currentPage }} {{ $t('scd40') }} {{ totalPages }}</span>
                  <button @click="nextPage" :disabled="currentPage === totalPages" class="page">{{ $t('scd41') }}</button>
                </div>
              </div>
                
            </div>
        </div>
    </div>
</div>
        
<!--##################################################-->  
                </div>
            </section>
        </main>
        <back-to-top></back-to-top>
    </div>
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import { RecycleScroller } from 'vue3-virtual-scroller';
import pako from 'pako';
import { ref, onMounted, computed, watch} from 'vue';
import { useRoute } from 'vue-router';
//import debounce from 'lodash.debounce';
//----------以下为一个ssmood页面需要的最基础的东西--------------
import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import config from '@/config';
const showSubMenu = ref(false);//二级菜单
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

const route = useRoute();

//------------------------------------------------------
//加载数据集详细信息
//------------------------------------------------------
const dataset = ref({
  dataset_id: '',
  species: '',
  area: '',
  condition: '',
  sex: '',
  age: '',
  information: {
    Publication: {
      Title: '',
      Authors: '',
      DatePublished: '',
      DateAdded: '',
      Journal: '',
      DOI: '',
      Protocol: '',
      DataSource: ''
    },
    StudyDesign: {
      Species: '',
      NumberOfSamples: null,
      Region: {
        Mouse: '',
        Human: ''
      }
    }
  }
});
onMounted(() => {
      const params = new URLSearchParams({
        id: route.params.id // 使用 route.params 获取路由参数
      });

      fetch(`${config.apiUrl}std_getSTDatasetDetail.php?${params}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json(); // 直接解析为 JSON，而不是 text
        })
        .then(dataArray => {
          if (dataArray && dataArray.length > 0 && dataArray[0].information) {
            const parsedData = JSON.parse(dataArray[0].information);
            dataset.value = { ...dataArray[0], information: parsedData }; // 使用 ref 的 value 更新数据
          }

          console.log(dataset.value); // 打印更新后的 dataset
        })
        .catch(error => {
          console.error('Error fetching dataset details:', error);
        });
 });
 
//------------------------------------------------------
//加载空间位置图
//------------------------------------------------------
const coordinate_data = ref([]);
const markerSize1 = ref(2); 

onMounted(() => {
  const params = new URLSearchParams({
    id: route.params.id
  });

  fetch(`${config.apiUrl}std_getCoordinate.php?${params}`)
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
      const compressed = new Uint8Array(arrayBuffer);
      const decompressed = pako.ungzip(compressed); // 使用 pako 解压
      const jsonString = new TextDecoder('utf-8').decode(decompressed);
      const data = JSON.parse(jsonString); // 解析 JSON 字符串

      coordinate_data.value = data.coordinate_data;
      const clusterLabels = data.cluster_labels;
      const labelMap = new Map();

      clusterLabels.forEach((label, index) => {
        labelMap.set(label, index);
      });

        clusterLabels.sort((a, b) => {
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
            return a.localeCompare(b);
          }
        });

      const umap1 = coordinate_data.value.map(d => parseFloat(d.x));
      const umap2 = coordinate_data.value.map(d => parseFloat(d.y));
      const cellIds = coordinate_data.value.map(d => d.i);
      const clusterLabelsData = coordinate_data.value.map(d => d.c);

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
            size: markerSize1.value,
            color: colors[label]
          }
        };
      });

      const halfViewportWidth = window.innerWidth * 0.55;
      const allViewportWidth = window.innerWidth * 0.66;

      const layout = {
        title: '',
        width: allViewportWidth,
        height: halfViewportWidth,
        xaxis: { title: 'coords_X' },
        yaxis: { title: 'coords_Y' },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        legend: {
          orientation: 'v',
          xanchor: 'left',
          yanchor: 'bottom',
          x: 1,
          y: 0,
          font: {
            size: 12
          },
          itemwidth: 30,
          itemheight: 20
        }
      };

      Plotly.newPlot('coord_chart', traces, layout);
    })
    .catch(error => console.error('Error fetching UMAP data:', error));
});
// 更新 UMAP 图表的方法
const updateUmap1 = () => {
  Plotly.restyle('coord_chart', 'marker.size', [markerSize1.value]);
};

// 减小标记大小的方法
const decreaseSize1 = () => {
  if (markerSize1.value > 1) {
    markerSize1.value -= 1;
    updateUmap1();
  }
};

// 增大标记大小的方法
const increaseSize1 = () => {
  if (markerSize1.value < 10) {
    markerSize1.value += 1;
    updateUmap1();
  }
};
//------------------------------------------------------
//分类表
//------------------------------------------------------
onMounted(async() => {
        const params = new URLSearchParams({
          id: route.params.id
        });
    // 绘制各数据集分布
    fetch(config.apiUrl+`std_getNumberOfCluster.php?${params}`)
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
          title: 'Num of Cluster',
          xaxis: {
            title: '',
            tickangle: -90, // 将标签旋转45度
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
      })
      .catch(error => console.error('Error fetching data from scd_getNumberOfCluster.php:', error));
});

//------------------------------------------------------
//基因搜索框
//------------------------------------------------------

// 计算 wrapper 的高度
const wrapperHeight = computed(() => {
  const itemCount = filteredGenes.value.length;
  const maxHeight = 400; // 36px/item * 11 items ≈ 400px
  return Math.min(itemCount * 36, maxHeight);
});

const genes = ref([]);
//const filteredGenes = ref([]);
const searchQuery = ref('');
const showScroller = ref(false);
const markerSize2 = ref(2); // 默认点大小
//加载基因
onMounted(async() => {
  const params = new URLSearchParams({
    id: route.params.id
  });
  try {
    const response = await fetch(config.apiUrl+`scd_getgene.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (!data || !Array.isArray(data.genes)) {
      throw new Error('Invalid data structure received');
    }
    genes.value = data.genes.map(gene => gene.toLowerCase());
    filteredGenes.value = [...genes.value];
  } catch (error) {
    console.error('Failed to load genes:', error);
    // 可以在这里处理错误，例如显示错误消息或设置错误状态
  }
});
// 响应式数据

// 方法：过滤基因

// 侦听器：监听 searchQuery 的变化
//import { nextTick } from 'vue';
const resetKey = ref(0)
const filteredGenes = computed(() => {
  if (!searchQuery.value) {
    return genes.value;
  }
  // 将搜索查询转换为小写并进行过滤
  return genes.value.filter(gene => gene.toLowerCase().includes(searchQuery.value.toLowerCase()));
});
watch([filteredGenes, searchQuery], () => {
  resetKey.value++ // 触发虚拟滚动组件重新渲染
}, { flush: 'post' }) // 在DOM更新后执行

// 选择项时触发的方法
const selectItem = (item) => {
  searchQuery.value = item;
  showScroller.value = false;
};

// 处理失去焦点时的方法
const handleBlur = () => {
  setTimeout(() => {
    if (!document.activeElement.closest('.scroller')) { // 滚动容器类名 'scroller'
      showScroller.value = false;
    }
  }, 100);
};


const getColor = (value) => {
    if (value > 2) {
        return 'rgb(142, 45, 48)';
      } else if (value > 0) {
        return 'rgb(93, 116, 162)';
      } else {
        return 'rgba(128, 128, 128, 0.2)'; 
      }
};

 
 
const searchgene = async() => {

  // 请求参数
  const params = new URLSearchParams({
    id: route.params.id,
    gene: searchQuery.value
  });

  try {
    const response = await fetch(config.apiUrl+`std_geneExpression.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();

    // 合并数据
    const ncMap = data.data.reduce((acc, item) => {
      acc[item.i] = parseFloat(item.nc) || 0;
      return acc;
    }, {});

    // 合并数组
    const mergedArray = coordinate_data.value.map(item => {
      item.nc = ncMap[item.i] || 0;
      return item;
    });
    // 分类信息
    const categories = [...new Set(mergedArray.map(item => item.c))];
    categories.sort();
    //-----------创建热图信息------------------------
const numCategories = categories.length;

const heatmapData = Array.from({ length: 11 }, () => 
  Array.from({ length: numCategories }, () => 0)
);


// 填充热图数据数组
mergedArray.forEach(item => {
  const categoryIndex = categories.indexOf(item.c);
    if (categoryIndex !== -1 && categoryIndex < numCategories) {
        // 限制 nc 在 0-5 范围内
        const cappedNC = Math.min(Math.max(item.nc, 0), 5);
        const expressionIndex = Math.floor(cappedNC / 0.5);
        
        if (expressionIndex >= 0 && expressionIndex < 11) {
          heatmapData[expressionIndex][categoryIndex]++;
        } else {
          console.warn(`Invalid expressionIndex: ${expressionIndex}`, item);
        }
      }
});

    //---------------------------------------
    // 按分类信息创建轨迹
    const traces = categories.map(category => {
      const categoryPoints = mergedArray.filter(point => point.c === category);
      
       const colors = categoryPoints.map(point => getColor(point.nc));
      return {
        x: categoryPoints.map(point => point.x),
        y: categoryPoints.map(point => point.y),
        mode: 'markers',
        type: 'scattergl',
        name: category,
        marker: {
          color: colors, 
          size: markerSize2.value,
        },
        text: categoryPoints.map(point => `${point.i}<br>${point.nc}`), // 显示 cell_id 和 nc 信息
      };
    });

    // 绘制图表
    const halfViewportWidth = window.innerWidth * 0.48;
    Plotly.newPlot('coord_chart_gene', traces, {
                    showlegend: false,
                    width: halfViewportWidth,
                    height: halfViewportWidth,
    });

//-----------绘制热图------------------------


  // 绘制热图
  const layout = {
    title: 'Gene expression heat map(The z axis is the number of cells)',
    xaxis: {
      title: '',
      tickvals: categories.map((category, index) => index),
      ticktext: categories
    },
    yaxis: {
      title: 'Gene expression',
      tickvals: Array.from({ length: 11 }, (_, i) => i ),
      ticktext: Array.from({ length: 11 }, (_, i) => (i* 0.5 ).toFixed(1))
    },
    autosize: true
  };

  const trace = {
    x: categories,
    y: Array.from({ length: 11 }, (_, i) => i ),
    z: heatmapData,
    type: 'heatmap',
  colorscale: [
    [0, 'rgb(255, 255, 255)'],   // 对应值为0时的颜色（#5D74A2）
    [1, 'rgb(93, 116, 162)']     // 对应值为1时的颜色（#FF6347，番茄红）
  ],
    zmin: 0,  // 设置热图颜色的最小值
    zmax: 5,  // 设置热图颜色的最大值（对应于最大表达量）
  };

  Plotly.newPlot('expressionHeatmap', [trace], layout);
  
  } catch (error) {
    console.error('Failed to load genes:', error);
  }

};

// 更新 UMAP 图2 的点大小
const updateUmap2 = () => {
  Plotly.restyle('coord_chart_gene', 'marker.size', [markerSize2.value]);
};

// 减少 UMAP 图2 的点大小
const decreaseSize2 = () => {
  if (markerSize2.value > 1) {
    markerSize2.value -= 1;
    updateUmap2();
  }
};

// 增加 UMAP 图2 的点大小
const increaseSize2 = () => {
  if (markerSize2.value < 10) {
    markerSize2.value += 1;
    updateUmap2();
  }
};


//------------------------------------------------------
//差异表达分析
//------------------------------------------------------

const group = ref('cellTypeSpecificGenes');
const cellTypes = ref([]);
const cellType = ref('');
const log2fc = ref(-10);
const pvalue = ref(1);
//const direction = ref('all');
const DEGdata = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(10);

const filterDEGGenes = ref('');

onMounted(() => {
    const params = new URLSearchParams({
    id: route.params.id,
  });
  fetch(config.apiUrl+`std_DEG_CellType.php?${params}`)
    .then((response) => response.json())
    .then((data) => {
      cellTypes.value = data; 
      cellType.value = cellTypes.value[0];
    })
    .catch((error) => {
      console.error("Failed to load DEGs:", error);
    });
});


watch(cellType, async (newcellType) => {
  //获取差异数据
  const params = new URLSearchParams({
    id: route.params.id,
    cluster: newcellType
  });
  fetch(config.apiUrl+`std_getDEG_ByCluster.php?${params}`)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      DEGdata.value = data.data; 
    })
    .catch((error) => {
      console.error("Failed to load DEGs:", error);
    });
});


//------------------------------------------------------
//差异表达分析分页计算
//------------------------------------------------------
const filteredData = computed(() => {
  return DEGdata.value.filter(item => {
    const logFoldChange = parseFloat(item.f);
    const adjustedPvalue = parseFloat(item.a);
    // 将 filterDEGGenes.value 和 item.i 都转换为小写，然后检查是否包含
    const lowerCaseFilter = filterDEGGenes.value.toLowerCase();
    const lowerCaseItemI = item.i.toLowerCase();
    return Math.abs(logFoldChange) >= log2fc.value &&
           adjustedPvalue <= pvalue.value &&
           lowerCaseItemI.includes(lowerCaseFilter);
  });
});

const totalPages = computed(() => {
  // 总页数基于筛选后的数据集计算
  return Math.ceil(filteredData.value.length / itemsPerPage.value);
});

const paginatedData = computed(() => {
  // 分页应用于筛选后的数据集
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredData.value.slice(start, end);
});

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const headers = ['gene name', 'Adjusted p-value', 'Log fold-change','Pct.1','Pct.2'];
const download = () => {
  // 创建一个二维数组，每个元素都是表格的一行
  const dataForTable = filteredData.value.map((item) => {
    return Object.values(item);
  });

  // 创建 CSV 字符串
  const csvContent = [headers.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  // 创建一个下载链接
  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "data.csv"; // 指定下载的文件名
  link.click();
};

</script>

<style scoped>
@import 'css/MainStyles.css';
@import 'css/SCDStyles.css';


 /* ----------------------------------------------------------- */
 /* 差异表达分析右容器表格,避免影响全局样式 */
.table-container {
  max-width: 100%;
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th,
td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.pagination {
  margin-top: 10px;
}
button {
  margin: 0 5px;
}
.st-coord-content{
    display: flex;
    flex-direction: column; /* 子元素垂直排列 */
    flex:1;
}
 /* ----------------------------------------------------------- */
</style>