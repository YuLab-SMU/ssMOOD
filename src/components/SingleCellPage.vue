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
      <h1>{{ $t('scd21') }}</h1>
  
    
      
          <div class="marker-size-control">
            <span class="label">{{ $t('scd22') }}:</span>
            <el-input-number
              v-model="markerSize1"
              :min="1"
              :max="100"
              :step="1"
              size="small"
              controls-position="default"
              @change="updateUmap1"
            />
          </div>
        <el-row :gutter="20" class="umap-contain">
            <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
            <el-col :xs="24" :sm="24" :md="20" :lg="20">
                <div id="umap-plot" style="width: 100%; height: 100%;"></div>
            </el-col>
            <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
        </el-row>
      
      <div class="information-second">
        <div id="myClusterChart" style="width: auto; height: 100%;"></div>
      </div>
      <!-- 右侧内容：基因搜索与基因列表 -->
      <div class="information-second">
        <h1>{{ $t('scd23') }}</h1>
        <div class="gene-search-con">
          <el-input
            v-model="searchQuery"
            :placeholder="$t('scd24')"
            @input="filterGenes"
            @focus="showScroller = true"
            @blur="handleBlur"
            class="search-gene-input"
            clearable
            size="default"
          >
            <template #append>
              <el-button @click="searchgene" type="primary">
                {{ $t('scd21button') }}
              </el-button>
            </template>
          </el-input>
          <!--虚拟下拉列表-->
          <div
              v-show="showScroller"
              class="scroller-wrapper"
              ref="scrollContainer"
            >
            <VirtualList
              :data-key="'id'"
              :data-sources="filteredGenes"
              :keeps="100"
              :estimate-size="50"
              :data-component="VirtualListItem"
              style="height: 400px; overflow-y: auto"
              class="scroller"
            >
            </VirtualList>
          </div>
          <div class="marker-size-control">
            <span class="label">{{ $t('scd22') }}:</span>
            <el-input-number
              v-model="markerSize2"
              :min="1"
              :max="100"
              :step="1"
              size="small"
              controls-position="default"
              @change="updateUmap2"
            />
          </div>
        </div>

        <el-row :gutter="20" class="umap-contain">
            <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
            <el-col :xs="24" :sm="24" :md="20" :lg="20">
                <div id="umap-chart-gene" style="width: 80%; height: 100%;"></div>
            </el-col>
            <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
        </el-row>
        
        <div id="expressionHeatmap" style="width: auto; height: 100%;"></div>
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
                  <el-select v-model="group" placeholder="Select Comparison">
                    <el-option
                      label="One Cell type vs Other Cell types"
                      value="cellTypeSpecificGenes"
                    />
                  </el-select>
                </div>
                <div class="cell-type">
                  <label>{{ $t('scd28') }}</label>
                  <el-select
                    v-model="cellType"
                    placeholder="请选择"
                    size="default"
                  >
                    <el-option
                      v-for="type in cellTypes"
                      :key="type"
                      :label="type"
                      :value="type"
                    />
                  </el-select>
                </div>
                                  <!-- 标签 -->
                  <label style="white-space: nowrap; font-weight: 600;">
                    {{ $t('scd29') }}
                  </label>
                <div class="log2fc" style="display: flex; align-items: center; gap: 12px;">
                  <!-- 滑动条 -->
                  <el-slider
                    v-model="log2fc"
                    :min="0"
                    :max="10"
                    :step="0.1"
                    :format-tooltip="val => val.toFixed(1)"
                    show-tooltip
                    tooltip-class="always-show-tooltip custom-tooltip"
                    style="flex: 1;"
                  />

                <div
                  style="
                    width: 48px;
                    text-align: right;
                    font-size: 14px;
                    font-weight: bold;
                    color: #666;
                  "
                >
                  {{ log2fc.toFixed(1) }}
                </div>
                </div>

                <div class="adjusted-pvalue">
                    <label>{{ $t('scd30') }}</label>
                        <!-- 对数刻度滑动条 -->
                    <el-slider
                      v-model="pValueSliderIndex"
                      :aria-hidden="null"
                      :min="0"
                      :max="7"
                      :marks="pValueMarks"
                      :step="1"
                      :show-tooltip="false" 
                      class="mt-4"
                    />

                </div>
              <div class="DEdirection" style="margin-top: 1rem;">
                <label class="font-semibold text-gray-700 mr-4">{{ $t('scd30-1') }}</label>

                <el-radio-group v-model="selectedDirection" size="small" class="custom-radio-group">
                  <el-radio-button label="all">All</el-radio-button>
                  <el-radio-button label="up">UP</el-radio-button>
                  <el-radio-button label="down">Down</el-radio-button>
                </el-radio-group>
              </div>
            </div>
        </div>
        
        <div class="information-right">
            
            <div class="de-analysis  modern-ui">
                <el-input
                  v-model="filterDEGGenes"
                  :placeholder="$t('scd31')"
                  clearable
                  size="default"
                />
                <!-- 表格 -->
                <div class="table-container">
                  <el-table
                    :data="paginatedData"
                    @sort-change="handleSortChange"
                    style="width: 100%;"
                    :default-sort="{ prop: sortProp, order: sortOrder }"
                  >
                    <el-table-column
                      prop="i"
                      :label="$t('scd33')"
                      sortable="custom"
                    >
                      <template #default="{ row }">{{ row.i }}</template>
                    </el-table-column>

                    <el-table-column
                      prop="f"
                      :label="$t('scd34')"
                      sortable="custom"
                      min-width="100"
                    >
                      <template #default="{ row }">{{ row.f.toFixed(6) }}</template>
                    </el-table-column>

                    <el-table-column
                      prop="t1"
                      :label="$t('scd35')"
                      sortable="custom"
                    >
                      <template #default="{ row }">{{ row.t1.toFixed(3) }}</template>
                    </el-table-column>

                    <el-table-column
                      prop="t2"
                      :label="$t('scd36')"
                      sortable="custom"
                    >
                      <template #default="{ row }">{{ row.t2.toFixed(3) }}</template>
                    </el-table-column>

                    <el-table-column
                      prop="a"
                      :label="$t('scd37')"
                      sortable="custom"
                      min-width="100"
                    >
                      <template #default="{ row }">{{ row.a.toExponential(3) }}</template>
                    </el-table-column>
                  </el-table>
                <div class="pagination">
              <!-- 翻页 -->
              <div class="left-section">
              <el-pagination
                layout="prev, pager, next"
                :current-page="currentPage"
                :page-size="pageSize"
                :total="filteredData.length"
                @current-change="handlePageChange"
                small
                background
                class="page-pagination"
              />
              </div>

                  <button @click="download" class="downloadButton">{{ $t('scd32') }}</button>
                </div>
              </div>
                
            </div>
        </div>
        </div>



  <el-card class="kegg-card" shadow="never">
    <!-- 折叠面板头 -->
    <el-collapse v-model="isenrichmentExpanded1" @change="getKEGG" accordion>
      <el-collapse-item :title="$t('scd42')" name="1">
        <div class="search-container" style="margin-bottom: 12px;">
          <el-input
            v-model="filterKEGG"
            :placeholder="$t('scd31')"
            clearable
            prefix-icon="el-icon-search"
            size="default"
          />
        </div>

        <el-table
          :data="KEGGpaginatedData"
          stripe
          size="default"
          style="width: 100%"
          :default-sort="{ prop: sortProp, order: sortOrder }"
          @sort-change="handleKEGGSortChange"
        >
          <el-table-column
            prop="t"
            :label="$t('scd44')"
            sortable="custom"
            min-width="400"
          />
          <el-table-column
            prop="p"
            :label="$t('scd45')"
            sortable="custom"
          >
            <template #default="{ row }">{{ row.p.toExponential(3) }}</template>
          </el-table-column>
          <el-table-column
            prop="o"
            :label="$t('scd46')"
            sortable="custom"
          >
            <template #default="{ row }">{{ row.o.toFixed(3) }}</template>
          </el-table-column>
          <el-table-column
            prop="c"
            :label="$t('scd47')"
            sortable="custom"
          >
            <template #default="{ row }">{{ row.c.toFixed(3) }}</template>
          </el-table-column>
          <el-table-column
            :label="$t('scd48')"
          >
            <template #default="{ row }">
              <el-button
                size="mini"
                type="primary"
                @click="openKeggModal(row.g)"
              >
                {{ $t('scd49') }}
              </el-button>
            </template>
          </el-table-column>
        </el-table>

        <!-- 分页 -->
        <div class="pagination" style="margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
          <el-pagination
            background
            layout="prev, pager, next"
            :current-page="KEGGcurrentPage"
            :page-size="pageSize"
            :total="KEGGfilteredData.length"
            @current-change="handleKEGGPageChange"
            size="small"
          />
          <el-button size="default" type="primary" @click="KEGGdownload" class="downloadButton">
            {{ $t('scd32') }}
          </el-button>
        </div>
      </el-collapse-item>
    </el-collapse>

    <!-- 模态框 -->
        <el-dialog
          v-model="isKeggModalOpen"
          width="50%"
          :title="$t('scd48')"
        >
        <el-table
          :data="selectedKeggGene"
          style="width: 100%"
          size="default"
        >
        <el-table-column :label="$t('scd50')">
          <template #default="{ row }">
            {{ row }}
          </template>
        </el-table-column>
        
        <el-table-column :label="$t('scd51')">
          <template #default="{ row }">
            <el-button
              type="text"
              size="small"
              @click="openLink(row.name, 'link1')"
            >
              UNIPROT🔗
            </el-button>
            <el-button
              type="text"
              size="small"
              @click="openLink(row.name, 'link2')"
            >
              GENECARDS🔗
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </el-card>
    </div>
</div>
        
<!--##################################################-->  
                </div>
            </section>
        </main>
        <BackToTop></BackToTop>
    </div>
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
//import { RecycleScroller } from 'vue3-virtual-scroller';
//import VirtualList from 'vue-virtual-scroll-list';
import VirtualListItem from './general/VirtualListItem.vue';
import VirtualList from 'vue3-virtual-scroll-list'
import pako from 'pako';
import { ref, onMounted, computed, watch} from 'vue';
import { useRoute } from 'vue-router';
//import VueVirtualScrollGrid from 'vue-virtual-scroll-grid';
//import debounce from 'lodash.debounce';
//----------以下为一个ssmood页面需要的最基础的东西--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------
import colorMap from './color_map.js';



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
        dataset.value = data[0]; 
        dataset.value.information = JSON.parse(data[0].information); 
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


          const colors = clusterLabels.reduce((acc, label) => {
              acc[label] = colorMap[label] || '#000'; // 如果没有找到对应的颜色，则使用默认颜色 #000
              return acc;
          }, {});
        //console.log(colors);
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
        //const halfViewportWidth = window.innerWidth * 0.55;
        //const allViewportWidth = window.innerWidth * 0.66;
        
        const layout = {
          title: '',
          responsive: true,
          //width: allViewportWidth,
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
/*
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
*/
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
            tickangle: 45, // 将标签旋转45度
            tickmode: 'linear', // 确保标签均匀分布
            tickfont: { size: 6 } // 调整字体大小
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
//const geneCurrentPage = ref(-1)
//const genePageSize = 100;
//const noMore = ref(false)
//const virtualItems = ref([])
//const resetKey = ref(0)

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


//const virtualItem = ref([]);

const filteredGenes = computed(() => {
  let data = genes.value || [];
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    //console.log(query)
    data = data.filter(gene => (gene.content || '').toLowerCase().includes(query));
  }
  return data;
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
    id: index, // 使用数组索引作为 id
    content: gene
  }));
    //filteredGenes.value = [...genes.value];
    //virtualItem.value = genes.value.slice(0,genePageSize );
    //geneCurrentPage.value = 0;
  } catch (error) {
    console.error('Failed to load genes:', error);
    // 可以在这里处理错误，例如显示错误消息或设置错误状态
  }
});
/*
const filteredGenes = ref([]); // 用于存储过滤后的基因数据
watch(searchQuery, (newQuery) => {
  if (newQuery) {
    filteredGenes.value = genes.value.filter(gene => gene.content.toLowerCase().includes(newQuery.toLowerCase()));
  } else {
    filteredGenes.value = genes.value; // 如果没有搜索查询，显示所有基因
  }
  geneCurrentPage.value = 0;
  resetKey.value++
});
*/
/*
watch(filteredGenes, () => {
  //virtualItem.value = filteredGenes.value.slice(0,genePageSize);
  geneCurrentPage.value = 0;
  resetKey.value++
//}, { flush: 'post' }) // 在DOM更新后执行
});
*/
// 响应式数据

// 方法：过滤基因

// 侦听器：监听 searchQuery 的变化
//import { nextTick } from 'vue';


import { inject } from 'vue';
const eventBus = inject('eventBus')
onMounted(() => {
    eventBus.on('select-item', handleSelectItem);
});
onUnmounted(() => {
  if (eventBus) {
    eventBus.off('select-item', handleSelectItem);
  }
});

const handleSelectItem = (item) => {
      searchQuery.value = item;
      showScroller.value = false;
};

/*
function onSelectItem(value) {
  console.log('父组件接收到选中事件，值是:', value)
  // 这里写父组件逻辑，比如更新 searchQuery
    searchQuery.value = value;
  showScroller.value = false;
}
*/

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


//import {onUnmounted} from 'vue'
const scrollContainer = ref(null);

onMounted(() => {
  scrollContainer.value.addEventListener('scroll', handleScroll);
});


onUnmounted(() => {
  scrollContainer.value.removeEventListener('scroll', handleScroll);
});


watch([geneCurrentPage,filteredGenes], () => {
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

*/

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
    //‼️ 按分类信息创建轨迹
    //按分类创建轨道,可以大幅度提高图表渲染速度和交互流畅😊。
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

    Plotly.newPlot('umap-chart-gene', traces, {
                    showlegend: false,
    });

//-----------绘制热图------------------------


  // 绘制热图
  const layout = {
    title: 'Gene expression heat map(The z axis is the number of cells)',
    xaxis: {
      title: '',
    tickangle: 45, // 将标签旋转45度
    tickmode: 'linear', // 确保标签均匀分布
    tickfont: { size: 6 }, // 调整字体大小
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

/*
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
*/



//###################################//
//差异表达分析
//###################################//

const group = ref('cellTypeSpecificGenes');
const cellTypes = ref([]);
const cellType = ref('');
const log2fc = ref(0);
//const pvalue = ref(5);
const selectedDirection = ref('all');

const DEGdata = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(10);

const filterDEGGenes = ref('');

const pValueSliderIndex = ref(5)  // 默认 0.05
// 定义 slider 的值及 label
const logPValues = [1e-6, 1e-5, 1e-4, 1e-3, 0.01, 0.05, 0.1, 1]
const pValueMarks = {
  0: '10⁻⁶',
  1: '10⁻⁵',
  2: '10⁻⁴',
  3: '10⁻³',
  4: '0.01',
  5: '0.05',
  6: '0.1',
  7: '1',
}

const formattedPValue = computed(() => {
  const val = logPValues[pValueSliderIndex.value]
  return val < 0.001 ? `10^${Math.log10(val).toFixed(0)}` : val.toFixed(3)
})


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
           adjustedPvalue <= formattedPValue.value &&
           lowerCaseItemI.includes(lowerCaseFilter)&&
           directionFilter;
  });
});
function handlePageChange(page) {
  currentPage.value = page
  // 你可以在这里重新 slice 数据用于渲染当前页内容
}
//------------------------------------------------------
//数据发生变化，需要基因富集分析数据，折叠基因富集分析面板
//------------------------------------------------------
watch(filteredData, () => {
  KEGGdata.value = [];
  isenrichmentExpanded1.value = false;
});

/*
const totalPages = computed(() => {
  // 总页数基于筛选后的数据集计算
  return Math.ceil(filteredData.value.length / itemsPerPage.value);
});
*/
const paginatedData = computed(() => {
  // 分页应用于筛选后的数据集
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return sortedData.value.slice(start, end);
});

/*
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
*/
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

const sortProp = ref('') // 当前排序字段
const sortOrder = ref('') // asc / desc

// 排序计算属性
const sortedData = computed(() => {
  if (!sortProp.value || !sortOrder.value) {
    return filteredData.value
  }
  // 排序逻辑
  return [...filteredData.value].sort((a, b) => {
    const prop = sortProp.value
    let res = 0
    if (prop === 'i') {
      // 字符串排序
      res = a.i.localeCompare(b.i)
    } else {
      // 数字排序
      res = a[prop] - b[prop]
    }
    return sortOrder.value === 'ascending' ? res : -res
  })
})

// 监听排序变化
function handleSortChange({ prop, order }) {
  currentPage.value = 1;
  sortProp.value = prop
  sortOrder.value = order
}

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
/*
const enrichment_expand_button1 =() => {
    isenrichmentExpanded1.value = !isenrichmentExpanded1.value
    
    if(isenrichmentExpanded1.value == true){
        getKEGG();
    }else{
        KEGGdata.value = [];
    }
}
*/


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
//console.log(KEGGfilteredData.value);


/*
const KEGGtotalPages = computed(() => {
  // 总页数基于筛选后的数据集计算
  return Math.ceil(KEGGfilteredData.value.length / KEGGitemsPerPage.value);
});
*/

const KEGGpaginatedData = computed(() => {
  // 分页应用于筛选后的数据集
  const start = (KEGGcurrentPage.value - 1) * KEGGitemsPerPage.value;
  const end = start + KEGGitemsPerPage.value;
  return KEGGsortedData.value.slice(start, end);
});

/*
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
*/
function handleKEGGPageChange(page) {
  KEGGcurrentPage.value = page
  // 你可以在这里重新 slice 数据用于渲染当前页内容
}

const KEGGsortProp = ref('') // 当前排序字段
const KEGGsortOrder = ref('') // asc / desc

// 排序计算属性
const KEGGsortedData = computed(() => {
  if (!KEGGsortProp.value || !KEGGsortOrder.value) {
    return KEGGfilteredData.value
  }
  // 排序逻辑
  return [...KEGGfilteredData.value].sort((a, b) => {
    const prop = KEGGsortProp.value
    let res = 0
    if (prop === 'i') {
      // 字符串排序
      res = a.i.localeCompare(b.i)
    } else {
      // 数字排序
      res = a[prop] - b[prop]
    }
    return KEGGsortOrder.value === 'ascending' ? res : -res
  })
})

// 监听排序变化
function handleKEGGSortChange({ prop, order }) {
  KEGGcurrentPage.value = 1;
  KEGGsortProp.value = prop
  KEGGsortOrder.value = order
}
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

/*
// 关闭模态窗
const closeKeggModal = () => {
  isKeggModalOpen.value = false;
};
*/
const openLink = (gene, linkType) => {
  let url;
  if (linkType === 'link1') {
    url = `https://www.uniprot.org/uniprotkb/?query=${gene}`; // 示例链接1
  } else if (linkType === 'link2') {
    url = `https://www.genecards.org/cgi-bin/carddisp.pl?gene=${gene}`; // 示例链接2
  }
  window.open(url, '_blank'); // 在新标签页中打开链接
};




//------
onMounted(() => {
  window.addEventListener('resize', resizeMyChart);
});
const resizeMyChart = () => {
  Plotly.Plots.resize('myClusterChart');
  Plotly.Plots.resize('umap-plot');
};

import { onUnmounted } from 'vue';
onUnmounted(() => {
  window.removeEventListener('resize', resizeMyChart);
});


</script>

<style scoped>
@import 'css/MainStyles.css';
@import 'css/SCDStyles.css';

 /* ----------------------------------------------------------- */
.scroller-wrapper{
    height: 400px;
}
/* 修改 el-select 的主题颜色 */
:deep(.el-select .el-input__wrapper) {
  border-color: rgb(93, 116, 162);
  box-shadow: 0 0 0 1px rgb(93, 116, 162);
}

/* 选中项颜色 */
:deep(.el-select .el-input.is-focus .el-input__wrapper),
:deep(.el-select .el-input__wrapper:hover) {
  border-color: rgb(93, 116, 162);
  box-shadow: 0 0 0 2px rgba(93, 116, 162, 0.2);
}

/* 下拉选项 hover 颜色 */
:deep(.el-select-dropdown__item:hover) {
  background-color: rgba(93, 116, 162, 0.1);
  color: rgb(93, 116, 162);
}

/* 被选中项的颜色 */
:deep(.el-select-dropdown__item.selected) {
  color: rgb(93, 116, 162);
  font-weight: bold;
}
</style>