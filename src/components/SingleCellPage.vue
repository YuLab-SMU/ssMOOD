<template>
    <div>
        <NavigationBar></NavigationBar>
        <main ref="pageSection">
            <section class="page-section">
                <div class="main-container">
<!--##################################################-->      
<!--详细信息容器-->
<div class="information-container">
    <div class="title-bar">
        <div class="title-bar-header">
         <h1>{{ $t('scd1') }}</h1>
         </div>
    </div>
    <div class="information-content">
        <div class="information-left">
             <h1>{{ $t('scd2') }}</h1>

            <p><span class="bold-black">{{ $t('scd3') }}</span>: {{ dataset.dataset_id }}</p>
            <p><span class="bold-black">{{ $t('scd4') }}</span>: {{ dataset.species }}</p>
            <p><span class="bold-black">{{ $t('scd5') }}</span>: {{ dataset.area }}</p>
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
        <div class="title-bar-header">
         <h1>{{ $t('scd20') }}</h1>
         </div>
    </div>
    <div class="sc-cluster-content">
      <!-- 左侧内容：UMAP图与标记大小控制 -->
      <div class="information-first">
        <h1>{{ $t('scd21') }}</h1>
        <div class="marker-size-controller">
          <div class="label">{{ $t('scd22') }}:</div>
          <!-- 减少按钮 -->
          <button class="custom-plus" icon @click="decreaseSize1">
            -
          </button>
          <!-- 显示当前大小 -->
          <span class="size-value">{{ markerSize1 }}</span>
          <!-- 增加按钮 -->
          <button class="custom-plus" icon @click="increaseSize1">
            +
          </button>
        </div>
        <div id="umap-plot"></div>
      </div>
      
      <div class="information-second">
        <div id="myClusterChart" style="width: 1200px; height: 300px;"></div>
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
    <!-- 新增高度控制容器 -->
        <div 
          v-show="showScroller"
          class="scroller-wrapper"
          ref="scrollContainer"
        >
          <recycle-scroller
            class="scroller"
            :items="virtualItem"
            :max-items="20000"
            key-field="id"
            :item-size="15"
            :buffer="500"
            :key="resetKey"
          >
            <template v-slot="{ item }">
              <div 
                class="gene-item"
                :key="item.id"
                :class="{ 'is-active': item === searchQuery }"
                @mousedown="selectItem(item.content)"
              >
                {{ item.content }}
              </div>
            </template>
          </recycle-scroller>
        </div>
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


        <div id="umap-chart-gene"></div>
        <div id="expressionHeatmap" style="width: 1200px; height: 400px;"></div>
      </div>
    </div>
  </div>
<!--##################################################-->                  
<!--差异表达分析容器-->
<div class="information-container">
    <div class="title-bar">
        <div class="title-bar-header">
         <h1>{{ $t('scd25') }}</h1>
         </div>
    </div>
    <div class="sc-deg-content">
        <div class="information-deg-first">
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
                  <div class="DEdirection">
                    <label>DE direction:</label>
                    <div class="radio-group">
                      <input type="radio" id="all" value="all" v-model="selectedDirection" />
                      <label for="all" class="radio-label">All</label>
                      <input type="radio" id="up" value="up" v-model="selectedDirection" />
                      <label for="up" class="radio-label">UP</label>
                      <input type="radio" id="down" value="down" v-model="selectedDirection"/>
                      <label for="down" class="radio-label">Down</label>
                    </div>
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
                <div class="left-section">
                  <button @click="prevPage" :disabled="currentPage === 1" class="page">{{ $t('scd38') }}</button>
                  <span>{{ $t('scd39') }} {{ currentPage }} {{ $t('scd40') }} {{ totalPages }}</span>
                  <button @click="nextPage" :disabled="currentPage === totalPages" class="page">{{ $t('scd41') }}</button>
                  <span>{{ $t('scd39-1') }} {{ filteredData.length  }}{{ $t('scd39-2') }}</span>
                </div>
                  <button @click="download" class="downloadButton">{{ $t('scd32') }}</button>
                </div>
              </div>
                
            </div>
        </div>
        </div>
        <div class="information-deg-second">
        <div class="kegg-expand-button"  @click="enrichment_expand_button1" :class="{ 'enrichment-button-expanded': isenrichmentExpanded1 }">{{ $t('scd42') }} </div>
        <div class="kegg-analysis modern-ui" v-if = "isenrichmentExpanded1" >
            <input
              class="search-gene-input"
              v-model="filterKEGG"
              :placeholder="$t('scd31')"
              type="text"
            />
                <div class="table-container">
                <table>
                  <thead>
                      <tr>
                        <th @click="sortTable(0)">{{ $t('scd44') }}</th>
                        <th @click="sortTable(1)">{{ $t('scd45') }}</th>
                        <th @click="sortTable(2)">{{ $t('scd46') }}</th>
                        <th @click="sortTable(3)">{{ $t('scd47') }}</th>
                        <th>{{ $t('scd48') }}</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in KEGGpaginatedData" :key="item.t">
                      <td>{{ item.t }}</td>
                      <td>{{ item.p.toExponential(3) }}</td> <!-- 保留6位小数 -->
                      <td>{{ item.o.toFixed(3) }}</td> <!-- 保留3位小数 -->
                      <td>{{ item.c.toFixed(3) }}</td> <!-- 保留3位小数 -->
                        <td>
                            <button @click="openKeggModal(item.g)" class="geneModelButton" >{{ $t('scd49') }}</button>
                        </td>
                    </tr>
                  </tbody>
                </table>
                
                <!-- 模态窗 -->
                <div v-if="isKeggModalOpen" class="keggModal">
                    <div class="keggModal-content"> <span class="close" @click="closeKeggModal">&times;</span>
                        <table>
                          <thead>
                            <tr>
                              <th>{{ $t('scd50') }}</th>
                              <th>{{ $t('scd51') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="gene in selectedKeggGene" :key="gene">
                              <td>{{ gene }}</td>
                              <td>
                                <button @click="openLink(gene, 'link1')" class="geneModelButton">UNIPROT🔗</button>
                                <button @click="openLink(gene, 'link2')" class="geneModelButton">GENECARDS🔗</button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="pagination">
                    <div class="left-section">
                  <button @click="KEGGprevPage" :disabled="KEGGcurrentPage === 1" class="page">{{ $t('scd38') }}</button>
                  <span>{{ $t('scd39') }} {{ KEGGcurrentPage }} {{ $t('scd40') }} {{ KEGGtotalPages }}</span>
                  <button @click="KEGGnextPage" :disabled="KEGGcurrentPage === KEGGtotalPages" class="page">{{ $t('scd41') }}</button>
                  </div>
                   <button @click="KEGGdownload" class="downloadButton">{{ $t('scd32') }}</button>
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
//import VueVirtualScrollGrid from 'vue-virtual-scroll-grid';
import debounce from 'lodash.debounce';
//----------以下为一个ssmood页面需要的最基础的东西--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------


const route = useRoute();


//###################################//
//加载数据集详细信息
//###################################//
const dataset = ref({
  dataset_id: '',
  species: '',
  area: '',
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
      return response.json();
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



//###################################//
//加载Umap图
//###################################//
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

//###################################//
//分类表
//###################################//
onMounted(async() => {
        const params = new URLSearchParams({
          id: route.params.id
        });
    // 绘制各数据集分布
    fetch(config.apiUrl+`scd_getNumberOfCluster.php?${params}`)
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
            tickfont: { size: 9 } // 调整字体大小
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



//###################################//
//基因搜索框
//###################################//



//const isGeneLoad = ref(false);
// 计算 wrapper 的高度

/*
const wrapperHeight = computed(() => {
  const itemCount = filteredGenes.value.length;
  const maxHeight = 400; // 36px/item * 11 items ≈ 400px
  return Math.min(itemCount * 36, maxHeight);
});
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
const markerSize2 = ref(4); // 默认点大小

// 分页状态管理
const geneCurrentPage = ref(0)
const genePageSize = 100;
//const noMore = ref(false)
//const virtualItems = ref([])

/*
watch(geneCurrentPage, () => {
  virtualItems.value = filteredGenes.value.slice(geneCurrentPage.value * genePageSize,geneCurrentPage.value * genePageSize+genePageSize );
  console.log(virtualItems.value);
}, { flush: 'post', immediate: true }) // 在DOM更新后执行


watch(virtualItems, () => {
  resetKey.value++ // 触发虚拟滚动组件重新渲染
}, { flush: 'post' }) // 在DOM更新后执行

*/
/*
const filteredGenes = computed(() => {
  if (!searchQuery.value) {
    return genes.value.slice(geneCurrentPage.value * genePageSize,geneCurrentPage.value * genePageSize+genePageSize );
  }
  const data = genes.value.filter(gene => gene.toLowerCase().includes(searchQuery.value.toLowerCase()))
  // 将搜索查询转换为小写并进行过滤
  return data.slice(geneCurrentPage.value * genePageSize,geneCurrentPage.value * genePageSize+genePageSize );
});
const filteredGenes = computed(() => {
  if (!searchQuery.value) {
    return genes.value;
  }
  const data = genes.value.filter(gene => gene.toLowerCase().includes(searchQuery.value.toLowerCase()))
  // 将搜索查询转换为小写并进行过滤
  return data;
});
*/


const virtualItem = ref([]);
const filteredGenes = computed(() => {
  let data = genes.value;
  if (searchQuery.value) {
    data = data.filter(gene => gene.content.includes(searchQuery.value.toLowerCase()));
  }
  return data
});

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
    genes.value = data.genes.map((gene, index) => ({
    id: index + 1, // 使用数组索引作为 id
    content: gene.toLowerCase()
  }));
    filteredGenes.value = [...genes.value];
    virtualItem.value = genes.value.slice(0,genePageSize );
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


watch([filteredGenes, searchQuery], () => {
  resetKey.value++ // 触发虚拟滚动组件重新渲染
}, { flush: 'post' }) // 在DOM更新后执行


watch(searchQuery, () => {
  virtualItem.value = filteredGenes.value.slice(0,genePageSize);
  geneCurrentPage.value = 0;
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
//---------------测试代码-------------start
/*
import { onClickOutside } from '@vueuse/core';
import {defineEmits,watchEffect} from 'vue';

const totalItems = ref(0)
// 使用computed缓存数据长度

watchEffect(() => {
  totalItems.value = filteredGenes.value.length;
});


const virtualItem = ref([]);
const dropdownHeight =300;

const isOpen = ref(false);
const selectedItem = ref(null);
const dropdown = ref(null);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    fetchPage(0); // 初始加载第一页数据
  }
};

const selectItem_test = (item) => {
  selectedItem.value = item;
  isOpen.value = false;
  emit('change', item);
};

// 直接返回数据切片，无需维护visibleItems
const fetchPage = async (page) => {
  const start = page * 40
  virtualItem.value = filteredGenes.value.slice(start, start + 40)
  return virtualItem.value
}


const emit = defineEmits(['change']);

onMounted(() => {
  onClickOutside(dropdown, () => {
    isOpen.value = false;
  });
});

const handleScroll = debounce(() => {
  const { scrollTop, scrollHeight, clientHeight } = scrollContainer.value;
  const scrollBottom = scrollHeight - (scrollTop + clientHeight);
  const threshold = 100; // 提前100px触发加载

  // 滚动到底部
  if (scrollBottom < threshold && geneCurrentPage.value < 50 ) {
    geneCurrentPage.value++;
  }

  // 滚动到顶部
  if (scrollTop < threshold && geneCurrentPage.value > 0) {
    geneCurrentPage.value--;
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
  }
}, 100);

*/
//import {onUnmounted} from 'vue'
const scrollContainer = ref(null);

onMounted(() => {
  scrollContainer.value.addEventListener('scroll', handleScroll);
});

/*
onUnmounted(() => {
  scrollContainer.value.removeEventListener('scroll', handleScroll);
});
*/

watch(geneCurrentPage, () => {
  virtualItem.value = virtualItem.value.concat(filteredGenes.value.slice(geneCurrentPage.value * genePageSize,geneCurrentPage.value * genePageSize+genePageSize ));
}) // 在DOM更新后执行

const handleScroll = debounce(() => {
  const { scrollTop, scrollHeight, clientHeight } = scrollContainer.value;
  const scrollBottom = scrollHeight - (scrollTop + clientHeight);
  const threshold = 100; // 提前100px触发加载

  // 滚动到底部
  if (scrollBottom < threshold && geneCurrentPage.value < 50 ) {
    geneCurrentPage.value++;
  }
}, 100);


//---------------测试代码-------------end

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
    const response = await fetch(config.apiUrl+`scd_getGeneExpression_bin.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const compressed = new Uint8Array(await response.arrayBuffer());
    const decompressed = pako.ungzip(compressed); // 使用pako解压
    
    const data = new TextDecoder('utf-8').decode(decompressed);
     const jsonData = JSON.parse(data);

    // 合并数据
    const ncMap = jsonData.reduce((acc, item) => {
      acc[item.i] = parseFloat(item.nc) || 0;
      return acc;
    }, {});

    // 合并数组
    const mergedArray = umapData.value.map(item => {
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

    //------------------------------------------------------
    //按分类信息创建轨迹
    //重要优化点！！！
    //按分类创建轨道,可以大幅度提高图表渲染速度和交互流畅。
    //------------------------------------------------------
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




//###################################//
//差异表达分析
//###################################//

const group = ref('cellTypeSpecificGenes');
const cellTypes = ref([]);
const cellType = ref('');
const log2fc = ref(-10);
const pvalue = ref(1);
const selectedDirection = ref('all');

const DEGdata = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(10);

const filterDEGGenes = ref('');


//----------------------------------
//基因富集分析部分的变量
const KEGGdata = ref([]);
const isenrichmentExpanded1 = ref(false);

//----------------------------------


//------------------------------------------------------//
//默认加载第一个细胞类型的差异数据
//------------------------------------------------------//
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

//------------------------------------------------------//
//检测用户更换细胞类型
//------------------------------------------------------//
watch(cellType, async (newcellType) => {
  //获取差异数据
  const params = new URLSearchParams({
    id: route.params.id,
    cluster: newcellType
  });
  fetch(config.apiUrl+`scd_getDEG_ByCluster.php?${params}`)
    .then((response) => response.json())
    .then((data) => {
      //console.log(data);
      DEGdata.value = data.data; 
      currentPage.value = 1;//回到第一页
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
    currentPage.value = 1;
    const logFoldChange = parseFloat(item.f);
    const adjustedPvalue = parseFloat(item.a);
    // 将 filterDEGGenes.value 和 item.i 都转换为小写，然后检查是否包含
    const lowerCaseFilter = filterDEGGenes.value.toLowerCase();
    const lowerCaseItemI = item.i.toLowerCase();
    
    let directionFilter = true;
    if (selectedDirection.value === 'up' && logFoldChange < 0) {
      directionFilter = false;
    } else if (selectedDirection.value === 'down' && logFoldChange > 0) {
      directionFilter = false;
    }

    return Math.abs(logFoldChange) >= log2fc.value &&
           adjustedPvalue <= pvalue.value &&
           lowerCaseItemI.includes(lowerCaseFilter)&&
           directionFilter;
  });
});

//------------------------------------------------------
//数据发生变化，需要基因富集分析数据，折叠基因富集分析面板
//------------------------------------------------------
watch(filteredData, () => {
  KEGGdata.value = [];
  isenrichmentExpanded1.value = false;
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
  link.download = "ssMOOD-"+route.params.id+"-differential_expression.csv"; // 指定下载的文件名
  link.click();
};

//------------------------------------------------------
//差异数据排序
//------------------------------------------------------


//###################################//
//KEGG分析
//###################################//


//const isenrichmentExpanded1 = ref(false);定义在差异部分

//const KEGGdata = ref([]);定义在差异部分
const KEGGcurrentPage = ref(1);
const KEGGitemsPerPage = ref(10);

const filterKEGG = ref('');

const KeggGenes = computed(() => {
      return filteredData.value.map(item => item.i);
});


//------------------------------------------------------//
//向服务器请求kegg数据
//------------------------------------------------------//
const getKEGG = () => {
  // 将 KeggGenes.value 转换为 JSON 字符串
  const genesJson = JSON.stringify(KeggGenes.value);
  
  // 创建一个 FormData 对象来存储请求参数
  const params = new FormData();
  params.append('genes', genesJson);
  params.append('gene_sets', "Mouse_GO_2024.gmt");
  
  // 发送 POST 请求
  fetch(config.apiUrl + 'enrichment.php', {
    method: 'POST', // 指定请求方法为 POST
    body: params, // 添加请求体
  })
  .then((response) => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json(); // 解析 JSON 响应
  })
  .then((data) => {
      // kegg面板展开时才将获取的数据存储到 KEGGdata
      if(isenrichmentExpanded1.value == true){
          KEGGdata.value = data; 
      }
    
    //console.log(KEGGdata.value);
  })
  .catch((error) => {
    console.error("Failed to load DEGs:", error);
  });
};


//------------------------------------------------------//
//展开或关闭kegg面板
//------------------------------------------------------//
const enrichment_expand_button1 =() => {
    isenrichmentExpanded1.value = !isenrichmentExpanded1.value
    
    if(isenrichmentExpanded1.value == true){
        getKEGG();
    }else{
        KEGGdata.value = [];
    }
}


//------------------------------------------------------//
//按名字过滤通路
//------------------------------------------------------//
const KEGGfilteredData = computed(() => {
  return KEGGdata.value.filter(item => {
    KEGGcurrentPage.value = 1;
    // 将 filterDEGGenes.value 和 item.i 都转换为小写，然后检查是否包含
    const lowerCaseFilter = filterKEGG.value.toLowerCase();
    const lowerCaseItemI = item.t.toLowerCase();
    

    return lowerCaseItemI.includes(lowerCaseFilter);
  });
});
console.log(KEGGfilteredData.value);

const KEGGtotalPages = computed(() => {
  // 总页数基于筛选后的数据集计算
  return Math.ceil(KEGGfilteredData.value.length / KEGGitemsPerPage.value);
});

const KEGGpaginatedData = computed(() => {
  // 分页应用于筛选后的数据集
  const start = (KEGGcurrentPage.value - 1) * KEGGitemsPerPage.value;
  const end = start + KEGGitemsPerPage.value;
  return KEGGfilteredData.value.slice(start, end);
});

const KEGGprevPage = () => {
  if (KEGGcurrentPage.value > 1) {
    KEGGcurrentPage.value--;
  }
};

const KEGGnextPage = () => {
  if (KEGGcurrentPage.value < KEGGtotalPages.value) {
    KEGGcurrentPage.value++;
  }
};

//------------------------------------------------------//
//保存kegg数据为CSV
//------------------------------------------------------//
const KEGGheaders = ['Term', 'Adjusted p-value', 'Odds Ratio','Combined Score','Genes'];
const KEGGdownload = () => {
  // 创建一个二维数组，每个元素都是表格的一行
  const dataForTable = KEGGfilteredData.value.map((item) => {
    return Object.values(item);
  });

  // 创建 CSV 字符串
  const csvContent = [KEGGheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  // 创建一个下载链接
  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-"+route.params.id+"-kegg.csv"; // 指定下载的文件名
  link.click();
};


//------------------------------------------------------//
//基因列表模态窗
//------------------------------------------------------//

// 模态窗状态
const isKeggModalOpen = ref(false);
const selectedKeggGene = ref([]);

// 打开模态窗
const openKeggModal = (gene) => {
  selectedKeggGene.value = gene.split(';');
  isKeggModalOpen.value = true;
};

// 关闭模态窗
const closeKeggModal = () => {
  isKeggModalOpen.value = false;
};

const openLink = (gene, linkType) => {
  let url;
  if (linkType === 'link1') {
    url = `https://www.uniprot.org/uniprotkb/?query=${gene}`; // 示例链接1
  } else if (linkType === 'link2') {
    url = `https://www.genecards.org/cgi-bin/carddisp.pl?gene=${gene}`; // 示例链接2
  }
  window.open(url, '_blank'); // 在新标签页中打开链接
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
th,td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}


button {
  margin: 0 5px;
}

 /* ----------------------------------------------------------- */
.scroller-wrapper{
    height: 400px;
}
</style>