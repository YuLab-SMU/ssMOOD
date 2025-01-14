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
         <h1>{{ $t('scd1') }}</h1>

    </div>
    <div class="information-content">
        <div class="information-left">
             <h1>{{ $t('scd2') }}</h1>

            <p><span class="bold-black">{{ $t('scd3') }}</span>: {{ dataset.dataset_id }}</p>
            <p><span class="bold-black">{{ $t('scd4') }}</span>: {{ dataset.species }}</p>
            <p><span class="bold-black">{{ $t('scd5') }}</span>: {{ dataset.region }}</p>
            <p><span class="bold-black">{{ $t('scd6') }}</span>: {{ dataset.conditions }}</p>
            <p><span class="bold-black">{{ $t('scd7') }}</span>: {{ dataset.sex }}</p>
            <p><span class="bold-black">{{ $t('scd8') }}</span>: {{ dataset.age }}</p>
             <h2>{{ $t('scd9') }}</h2>

            <p><span class="bold-black">{{ $t('scd10') }}</span>: {{ dataset.information.DatasetInformation.NumberOfCells }}</p>
            <p><span class="bold-black">{{ $t('scd11') }}</span>: {{ dataset.information.DatasetInformation.NumberOfIdentifiedCellTypes }}</p>
        </div>
        <div class="information-right">
             <h2>{{ $t('scd12') }}</h2>

            <p><span class="bold-black">{{ $t('scd13') }}</span>: {{ dataset.information.DatasetSource1.Title }}</p>
            <p><span class="bold-black">{{ $t('scd14') }}</span>: {{ dataset.information.DatasetSource1.Methodology }}</p>
            <p><span class="bold-black">{{ $t('scd15') }}</span>: {{ dataset.information.DatasetSource1.Protocol }}</p>
            <p><span class="bold-black">{{ $t('scd16') }}</span>: {{ dataset.information.DatasetSource1.PublicDataID }}</p>
            <p><span class="bold-black">{{ $t('scd17') }}</span>: <a :href="'http://www.ncbi.nlm.nih.gov/pubmed/' + dataset.information.DatasetSource1.Pubmed" target="_blank">{{ dataset.information.DatasetSource1.Pubmed }}</a>

            </p>
            <p><span class="bold-black">{{ $t('scd18') }}</span>: <a :href="'http://doi.org/' + dataset.information.DatasetSource1.DOI" target="_blank">{{ dataset.information.DatasetSource1.DOI }}</a>

            </p>
            <p><span class="bold-black">{{ $t('scd19') }}</span>: {{ dataset.information.DatasetSource1.Statement }}</p>
        </div>
    </div>
</div>
                    
<!--##################################################-->                    
<!--细胞分类容器-->
  <div class="information-container">
    <div class="title-bar">
      <h1>{{ $t('scd20') }}</h1>
    </div>
    <div class="sc-cluster-content">
      <!-- 左侧内容：UMAP图与标记大小控制 -->
      <div class="information-first">
        <h1>{{ $t('scd21') }}</h1>
        <div class="marker-size-controller">
          <div class="label">{{ $t('scd22') }}:</div>
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
        <div id="umap-plot"></div>
      </div>
      <!-- 右侧内容：基因搜索与基因列表 -->
      <div class="information-second">
        <h1>{{ $t('scd23') }}</h1>
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
            <button @click="searchgene" class="search-btn">
              {{ $t('scd21button') }}
            </button>
          <div class="marker-size-controller">
            <div class="label">{{ $t('scd22') }}:</div>
            <!-- 减少按钮 -->
            <button class="custom-plus" @click="decreaseSize2">
              -
            </button>
            <!-- 显示当前大小 -->
            <span class="size-value">{{ markerSize2 }}</span>
            <!-- 增加按钮 -->
            <button class="custom-plus" @click="increaseSize2">
              +
            </button>
          </div>
        </div>

        <!-- 基因列表滚动显示 -->
        <recycle-scroller
          v-if="showScroller"
          class="scroller"
          ref="scroller"
          :items="filteredGenes"
          :item-size="6"
          :buffer="1000"
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

        <div id="umap-chart-gene"></div>
      </div>
    </div>
  </div>
<!--##################################################-->                  
<!--差异表达分析容器-->
<div class="information-container">
    <div class="title-bar">
         <h1>{{ $t('scd25') }}</h1>

    </div>
    <div class="information-content">
        <div class="information-left">
            <div class="de-analysis  modern-ui">
                <div class="note">
                    <p>{{ $t('scd26') }}</p>
                </div>
                <div class="group">
                    <label>{{ $t('scd27') }}</label>
                    <select v-model="group" class="custom-select">
                        <option value="cellTypeSpecificGenes">One Cell type vs Other Cell types</option>
                    </select>
                </div>
                <div class="cell-type">
                    <label>{{ $t('scd28') }}</label>
                    <select v-model="cellType" class="custom-select">
                      <!-- 动态生成选项 -->
                      <option v-for="type in cellTypes" :key="type" :value="type">{{ type }}</option>
                    </select>
                </div>
                <div class="log2fc">
                    <label>{{ $t('scd29') }}</label>
                    <input type="range" min="-10" max="5" step="0.1" v-model="log2fc" class="custom-range"/> <span>{{ log2fc }}</span>

                </div>
                <div class="adjusted-pvalue">
                    <label>{{ $t('scd30') }}</label>
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
                <button @click="download" class="search-btn">{{ $t('scd32') }}</button>
                <div class="table-container">
                <table>
                  <thead>
                      <tr>
                        <th @click="sortTable(0)">{{ $t('scd33') }}</th>
                        <th @click="sortTable(1)">{{ $t('scd34') }}</th>
                        <th @click="sortTable(2)">{{ $t('scd35') }}</th>
                        <th @click="sortTable(3)">{{ $t('scd36') }}</th>
                        <th @click="sortTable(4)">{{ $t('scd37') }}</th>
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
        <BackToTop />
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
  region: '',
  condition: '',
  sex: '',
  age: '',
  information: {
    DatasetInformation: {
      NumberOfCells: null,
      NumberOfIdentifiedCellTypes: null
    },
    DatasetSource1: {
      Title: "",
      Methodology: "",
      Protocol: "",
      PublicDataID: "",
      Pubmed: null,
      DOI: "",
      Statement: ""
    },
  }
});

onMounted(() => {
  const params = new URLSearchParams({
    id: route.params.id
  });

  fetch(config.apiUrl+`scd_getSCDatasetDetail.php?${params}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json(); // 直接使用 json() 方法，不需要转换为 text()
    })
    .then(data => {
      if (data && data.length > 0 && data[0].information) {
        dataset.value = data[0]; // 直接赋值，不需要使用 $set
        dataset.value.information = JSON.parse(data[0].information); // 直接赋值，不需要使用 $set
      }
    })
    .catch(error => {
      console.error('Error fetching dataset details:', error);
    });
});



//------------------------------------------------------
//加载Umap图
//------------------------------------------------------
const umapData = ref([]);
onMounted(() => {
    const params = new URLSearchParams({
          id: route.params.id
        });
    fetch(config.apiUrl+`scd_getumapdata.php?${params}`)
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => {
            
        const compressed = new Uint8Array(arrayBuffer);
        const decompressed = pako.ungzip(compressed); // 使用pako解压
        const jsonString = new TextDecoder('utf-8').decode(decompressed);
        const data = JSON.parse(jsonString); // 解析JSON字符串
        
        //console.log(data);
          umapData.value = data.umap_data;
          const clusterLabels = data.cluster_labels;
          const labelMap = new Map();
            clusterLabels.forEach((label, index) => {
              labelMap.set(label, index);
            });

        clusterLabels.sort((a, b) => {
          const partsA = a.match(/\d+/)[0];
          const partsB = b.match(/\d+/)[0];
          return parseInt(partsA, 10) - parseInt(partsB, 10);
        });

            //console.log(clusterLabels);
          const umap1 = umapData.value.map(d => parseFloat(d.u1));
          const umap2 = umapData.value.map(d => parseFloat(d.u2));
          const cellIds = umapData.value.map(d => d.i);
          const clusterLabelsData = umapData.value.map(d => d.c);


        const colors = Array.from(labelMap.keys()).reduce((acc, label, index) => {
            const hue = (index * 360 / labelMap.size);
            const lightness = 70 + (index % 2 === 0 ? 5 : -5);
            acc[label] = `hsl(${hue}, 40%, ${lightness}%)`;
            return acc;
        }, {});
        console.log(colors);
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
                size:  markerSize1.value,
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
          xaxis: { title: 'UMAP1' },
          yaxis: { title: 'UMAP2' },
          paper_bgcolor: 'rgba(0,0,0,0)',
          plot_bgcolor: 'rgba(0,0,0,0)',
          legend: {
            orientation: 'v', // 设置图例垂直排列
            xanchor: 'left', // 图例的x轴锚点设置为左对齐
            yanchor: 'bottom', // 图例的y轴锚点设置为底部对齐
            x: 1, // 图例在水平方向上的位置，1表示最右侧
            y: 0, // 图例在垂直方向上的位置，0.5表示中间
            font: {
              size: 12 // 设置图例文本的大小
            },
            itemwidth: 30, // 控制每个图例项的宽度
            itemheight: 20 // 控制每个图例项的高度
          },

        };

    
          Plotly.newPlot('umap-plot', traces, layout);
          
        })
        .catch(error => console.error('Error fetching UMAP data:', error));
});


//更新点大小
const markerSize1 = ref(4); // 默认点大小


// 更新 UMAP 图1 的点大小
const updateUmap1 = () => {
  Plotly.restyle('umap-plot', 'marker.size', [markerSize1.value]);
};

// 减少 UMAP 图1 的点大小
const decreaseSize1 = () => {
  if (markerSize1.value > 1) {
    markerSize1.value -= 1;
    updateUmap1();
  }
};

// 增加 UMAP 图1 的点大小
const increaseSize1 = () => {
  if (markerSize1.value < 10) {
    markerSize1.value += 1;
    updateUmap1();
  }
};

//------------------------------------------------------
//基因搜索框
//------------------------------------------------------
/*
import { throttle } from 'lodash';

const onScroll = throttle(() => {
}, 200); // 每 200 毫秒触发一次

// 添加监听
const scrollerElement = document.querySelector('.scroller');
scrollerElement.addEventListener('scroll', onScroll);
*/

const genes = ref([]);
//const filteredGenes = ref([]);
const searchQuery = ref('');
const showScroller = ref(false);
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
const filteredGenes = computed(() => {
  if (!searchQuery.value) {
    return genes.value;
  }
  // 将搜索查询转换为小写并进行过滤
  return genes.value.filter(gene => gene.toLowerCase().includes(searchQuery.value.toLowerCase()));
});


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
    const response = await fetch(config.apiUrl+`scd_geneExpression.php?${params}`);
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
    const mergedArray = umapData.value.map(item => {
      item.nc = ncMap[item.i] || 0;
      return item;
    });

    // 按分类信息创建轨迹
    const categories = [...new Set(mergedArray.map(item => item.c))];
    const traces = categories.map(category => {
      const categoryPoints = mergedArray.filter(point => point.c === category);
      
       const colors = categoryPoints.map(point => getColor(point.nc));
      return {
        x: categoryPoints.map(point => point.u1),
        y: categoryPoints.map(point => point.u2),
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
    Plotly.newPlot('umap-chart-gene', traces, {
                    showlegend: false,
                    width: halfViewportWidth,
                    height: halfViewportWidth,
    });
  } catch (error) {
    console.error('Failed to load genes:', error);
  }
};

const markerSize2 = ref(4); // 默认点大小
// 更新 UMAP 图2 的点大小
const updateUmap2 = () => {
  Plotly.restyle('umap-chart-gene', 'marker.size', [markerSize2.value]);
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
  fetch(config.apiUrl+`scd_DEG_CellType.php?${params}`)
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
  fetch(config.apiUrl+`scd_getDEG_ByCluster.php?${params}`)
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

//------------------------------------------------------
//排序
//------------------------------------------------------

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
th,td {
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

 /* ----------------------------------------------------------- */
</style>