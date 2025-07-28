<template>
  <div>
    <NavigationBar></NavigationBar>
    <main>
      <section class="page-section">
        <div class="main-container">
          <!--##################################################-->
          <!--详细信息容器-->
          <div class="information-container">
            <div class="title-bar">
              <div class="title-bar-header">
                <h1>{{ $t('std1') }}</h1>
              </div>
            </div>
            <div class="information-content">
              <div class="information-left">
                <h1>{{ $t('std2') }}</h1>

                <p><span class="bold-black">{{ $t('std3') }}</span>: {{ dataset.dataset_id }}</p>
                <p><span class="bold-black">{{ $t('std4') }}</span>: {{ dataset.species }}</p>
                <p><span class="bold-black">{{ $t('std5') }}</span>: {{ dataset.area }}</p>
                <p><span class="bold-black">{{ $t('std6') }}</span>: {{ dataset.conditions }}</p>
                <p><span class="bold-black">{{ $t('std7') }}</span>: {{ dataset.sex }}</p>
                <p><span class="bold-black">{{ $t('std8') }}</span>: {{ dataset.age }}</p>

              </div>
              <div class="information-right">
                <h1>{{ $t('std9') }}</h1>
                <p><span class="bold-black">{{ $t('std10') }}</span>: {{ dataset.information.Publication.Title }}</p>
                <p><span class="bold-black">{{ $t('std11') }}</span>: {{ dataset.information.Publication.DatePublished
                  }}</p>
                <p><span class="bold-black">{{ $t('std12') }}</span>: {{ dataset.information.Publication.Protocol }}</p>
                <p><span class="bold-black">{{ $t('std13') }}</span>: {{ dataset.information.Publication.DataSource }}
                </p>

              </div>
            </div>
          </div>

          <!--##################################################-->
          <!--细胞分类容器-->
          <div class="information-container">
            <div class="title-bar">
              <div class="title-bar-header">
                <h1>{{ $t('std14') }}</h1>
              </div>
            </div>
            <div class="information-content">
              <div class="information-left">
                <h1>{{ $t('std17') }}</h1>
                <div class="marker-size-controller">
                  <span class="label">{{ $t('std16') }}:</span>
                  <el-input-number v-model="markerSize1" :min="1" :max="100" :step="1" size="small"
                    controls-position="" @change="updateUmap1" />
                </div>
                <!-- coord_chart的容器 -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- 加载图 -->
                  <img v-if="coord_chartLoading" src="/loading.gif" alt="Loading"
                    style="position: absolute;inset: 0;margin: auto;width: 80%;height: 80%;object-fit: contain;z-index: 1;" />
                  <div id="coord_chart"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: coord_chartLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>
              <div class="information-right">
                <h1>{{ $t('std19') }}</h1>
                <div class="gene-search-con">
                  <el-input v-model="searchQuery" :placeholder="$t('scd24')"
                    @focus="showScroller = true" @blur="handleBlur" class="search-gene-input" clearable size="">
                    <template #append>
                      <el-button @click="searchgene" type="primary">
                        {{ $t('scd21button') }}
                      </el-button>
                    </template>
                  </el-input>
                  <!--虚拟下拉列表-->
                  <div v-show="showScroller" class="scroller-wrapper" ref="scrollContainer">
                    <VirtualList :data-key="'id'" :data-sources="filteredGenes" :keeps="100" :estimate-size="50"
                      :data-component="VirtualListItem" style="height: 400px; overflow-y: auto" class="scroller">
                    </VirtualList>
                  </div>
                  <div class="marker-size-control">
                    <span class="label">{{ $t('scd22') }}:</span>
                    <el-input-number v-model="markerSize2" :min="1" :max="100" :step="1" size="small"
                      controls-position="" @change="updateUmap2" />
                  </div>
                </div>
                <!-- 空间基因表达量图的容器 -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- 加载图 -->
                  <img v-if="coord_chartGeneLoading" src="/loading.gif" alt="Loading"
                    style="position: absolute;inset: 0;margin: auto;width: 80%;height: 80%;object-fit: contain;z-index: 1;" />

                  <!-- Plotly 图表容器 -->
                  <div id="coord_chart_gene"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: coord_chartGeneLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>
            </div>
            <div class="information-second">
              <!-- 🔔自定义图例，官方图例会影响图的比例 -->
              <div class="legend-wrapper">
                <el-checkbox v-model="checkAllFlag" :indeterminate="isIndeterminate" @change="toggleAll"
                  class="select-all">
                  全选
                </el-checkbox>
                <el-checkbox-group v-model="visibleLabels" @change="onCheckboxChange" class="legend-group">
                  <el-checkbox v-for="label in global_clusterLabels" :key="label" :label="label" class="checkbox-item">
                    <span class="label-box" :style="{
                      backgroundColor: visibleLabels.includes(label) ? colors[label] : 'transparent',
                      borderColor: colors[label],
                      color: visibleLabels.includes(label) ? '#fff' : colors[label]
                    }">
                      {{ label }}
                    </span>
                  </el-checkbox>
                </el-checkbox-group>
              </div>
              <div id="myClusterChart" style="width: auto; height: 100%;"></div>
            </div>
            <!-- ################################ -->
            <!-- 第二列 -->
            <div class="information-second">
              <div id="expressionHeatmap" style="width: auto; height: 100%;"></div>
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
                    <input type="range" min="-10" max="5" step="0.1" v-model="log2fc" class="custom-range" /> <span>{{
                      log2fc
                      }}</span>

                  </div>
                  <div class="adjusted-pvalue">
                    <label>{{ $t('std27') }}</label>
                    <input type="range" min="0" max="1" step="0.0001" v-model="pvalue" class="custom-range" /> <span>{{
                      pvalue
                      }}</span>

                  </div>
                </div>
              </div>

              <div class="information-right">

                <div class="de-analysis  modern-ui">
                  <input class="search-gene-input" v-model="filterDEGGenes" :placeholder="$t('scd31')" type="text" />
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
                      <button @click="nextPage" :disabled="currentPage === totalPages" class="page">{{ $t('scd41')
                        }}</button>
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
import VirtualListItem from './general/VirtualListItem.vue';
import VirtualList from 'vue3-virtual-scroll-list'
import pako from 'pako';
import { ref, onMounted, computed, watch, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
//import debounce from 'lodash.debounce';
//----------以下为一个ssmood页面需要的最基础的东西--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------
import colorMap from './color_map.js';
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
const global_clusterLabels = ref([])
const visibleLabels = ref([])
const colors = ref({})
const isIndeterminate = ref(false)
const checkAllFlag = ref(true)

const coord_chartLoading = ref(true)
// 全选/全不选
const toggleAll = () => {
  if (checkAllFlag.value) {
    visibleLabels.value = [...global_clusterLabels.value]
  } else {
    visibleLabels.value = []
  }
}

// 单个复选框变动时
const onCheckboxChange = () => {
  // 这里只处理图更新，选中状态由 watch 管
  updatePlot()
  updateGenePlot()
}

// 自动追踪 visibleLabels 的变化，更新全选与半选中状态
watch(visibleLabels, (val) => {
  const total = global_clusterLabels.value.length
  const checkedCount = val.length

  checkAllFlag.value = checkedCount === total
  isIndeterminate.value = checkedCount > 0 && checkedCount < total
  updatePlot()
  updateGenePlot()
})

const updateGenePlot = () => {
  if (isSearchgene.value === true) {
    const filteredArray = mergedGeneArray.value.filter(item =>
      visibleLabels.value.includes(item.c)
    );

    const categories = [...new Set(filteredArray.map(item => item.c))].sort();

    const traces = categories.map(category => {
      const categoryPoints = filteredArray.filter(point => point.c === category);
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
        text: categoryPoints.map(point => `${point.i}<br>${point.nc}`),
      };
    });

    const genelayout = {
      showlegend: false,
      autosize: true,
      xaxis: { title: 'UMAP1' },
      yaxis: { title: 'UMAP2' },
    };

    Plotly.react('coord_chart_gene', traces, genelayout);
  }
}

const updatePlot = () => {
  const umap1 = coordinate_data.value.map(d => parseFloat(d.x));
  const umap2 = coordinate_data.value.map(d => parseFloat(d.y));
  const cellIds = coordinate_data.value.map(d => d.i);
  const clusterLabelsData = coordinate_data.value.map(d => d.c);

  const traces = global_clusterLabels.value.map((label) => {
    const show = visibleLabels.value.includes(label)
    const indices = clusterLabelsData
      .map((l, i) => l === label ? i : -1)
      .filter(i => i !== -1)

    const x = show ? indices.map(i => umap1[i]) : []
    const y = show ? indices.map(i => umap2[i]) : []
    const text = show ? indices.map(i => cellIds[i]) : []

    return {
      x,
      y,
      mode: 'markers',
      type: 'scattergl',
      name: label,
      text,
      marker: {
        size: markerSize1.value,
        color: colors.value[label]
      }
    }
  })

  const layout = {
    title: '',
    xaxis: { title: 'coords_X' },
    yaxis: { title: 'coords_Y' },
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)',
    showlegend: false,
  };

  Plotly.react('coord_chart', traces, layout)
}



onMounted(() => {
  coord_chartLoading.value = false;
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
      global_clusterLabels.value = clusterLabels;
      visibleLabels.value = [...clusterLabels];
      const umap1 = coordinate_data.value.map(d => parseFloat(d.x));
      const umap2 = coordinate_data.value.map(d => parseFloat(d.y));
      const cellIds = coordinate_data.value.map(d => d.i);
      const clusterLabelsData = coordinate_data.value.map(d => d.c);

      colors.value = clusterLabels.reduce((acc, label) => {
        acc[label] = colorMap[label] || 'rgb(128,128,128)'; // 如果没有找到对应的颜色，则使用默认颜色 #000
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

      const layout = {
        showlegend: false,
        title: '',
        xaxis: { title: 'coords_X' },
        yaxis: { title: 'coords_Y' },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
      };

      Plotly.newPlot('coord_chart', traces, layout);
      coord_chartLoading.value = false;
    })
    .catch(error => console.error('Error fetching UMAP data:', error));
});
// 更新 UMAP 图表的方法
const updateUmap1 = () => {
  Plotly.restyle('coord_chart', 'marker.size', [markerSize1.value]);
};

//------------------------------------------------------
//分类表
//------------------------------------------------------
onMounted(async () => {
  const params = new URLSearchParams({
    id: route.params.id
  });
  // 绘制各数据集分布
  fetch(config.apiUrl + `std_getNumberOfCluster.php?${params}`)
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
          tickfont: { size: 7 } // 调整字体大小
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


const genes = ref([]);
//const filteredGenes = ref([]);
const searchQuery = ref('');
const showScroller = ref(false);
const markerSize2 = ref(2); // 默认点大小


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
onMounted(async () => {
  const params = new URLSearchParams({
    id: route.params.id
  });
  try {
    const response = await fetch(config.apiUrl + `scd_getgene.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const compressed = await response.arrayBuffer();
    const jsonStr = pako.inflate(new Uint8Array(compressed), { to: 'string' });
    const data = JSON.parse(jsonStr);
    if (!data || !Array.isArray(data.genes)) {
      throw new Error('Invalid data structure received');
    }
    genes.value = data.genes.map((gene, index) => ({
      id: index, // 使用数组索引作为 id
      content: gene
    }));

  } catch (error) {
    console.error('Failed to load genes:', error);
  }
});


//-------------------------------------------------------------
//选中选项后的事件
//⚠️vue3-virtual-scroll-list组件自带的有问题，这里使用全局事件总线
//-------------------------------------------------------------
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

// 处理失去焦点时的方法
const handleBlur = () => {
  setTimeout(() => {
    if (!document.activeElement.closest('.scroller')) { // 滚动容器类名 'scroller'
      showScroller.value = false;
    }
  }, 100);
};


//-------------------------------------------------------------
//颜色
//-------------------------------------------------------------
const maxNc = ref(0)

const getColor = (value) => {
  if (value === 0) return 'rgba(128, 128, 128, 0.15)';
  const t = Math.min(value / maxNc.value, 1);
  const h = 0;                    // 红色
  const s = 60 + 40 * t;          // 60% → 100%
  const l = 80 - 35 * t;          // 80% → 45%
  return `hsl(${h}, ${s}%, ${l}%)`;
};
const isSearchgene = ref(false);
const mergedGeneArray = ref([]);
const coord_chartGeneLoading = ref(false);

const searchgene = async () => {
  coord_chartGeneLoading.value = true;
  // 请求参数
  const params = new URLSearchParams({
    id: route.params.id,
    gene: searchQuery.value
  });

  try {
    const response = await fetch(config.apiUrl + `std_getGeneExpression_bin.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const compressed = new Uint8Array(await response.arrayBuffer());
    const decompressed = pako.ungzip(compressed); // 使用pako解压

    const data = new TextDecoder('utf-8').decode(decompressed);
    const jsonData = JSON.parse(data);
    isSearchgene.value = true;
    // 合并数据
    const ncMap = jsonData.reduce((acc, item) => {
      acc[item.i] = parseFloat(item.nc) || 0;
      return acc;
    }, {});

    // 合并数组
    const mergedArray = coordinate_data.value.map(item => {
      item.nc = ncMap[item.i] || 0;
      return item;
    });

    mergedGeneArray.value = mergedArray;
    // 分类信息
    const categories = [...new Set(mergedArray.map(item => item.c))];

    categories.sort();

    // 安全计算最大值
    maxNc.value = mergedArray.reduce(
      (max, item) => (item.nc > max ? item.nc : max),
      -Infinity
    );
    console.log("hello")
    //-----------创建热图信息------------------------

    const ncValues = mergedArray.map(item => item.nc).filter(n => n > 0);
    const minLogNC = Math.log10(Math.min(...ncValues));
    const maxLogNC = Math.log10(Math.max(...ncValues));
    const numBins = 11;
    const heatmapData = Array.from({ length: numBins }, () =>
      Array(categories.length).fill(0)
    );

    mergedArray.forEach(item => {
      const categoryIndex = categories.indexOf(item.c);
      if (categoryIndex !== -1 && item.nc > 0) {
        const logNC = Math.log10(item.nc);

        // 归一化
        const normLogNC = (logNC - minLogNC) / (maxLogNC - minLogNC);
        const expressionIndex = Math.floor(normLogNC * (numBins - 1));

        // 边界保护
        const safeIndex = Math.min(Math.max(expressionIndex, 0), numBins - 1);

        heatmapData[safeIndex][categoryIndex]++;
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

    Plotly.newPlot('coord_chart_gene', traces, { showlegend: false, });


    //-----------绘制热图------------------------
    //各类细胞在不同表达量区间的细胞数量热图
    const layout = {
      autosize: true,
      title: 'Heatmap of Cell Counts Across Expression Levels and Cell Types',
      xaxis: {
        title: '',
        showgrid: false,
        tickangle: 45, // 将标签旋转45度
        tickmode: 'linear', // 确保标签均匀分布
        tickfont: { size: 6 }, // 调整字体大小
        tickvals: categories.map((index) => index),
        ticktext: categories,
      },
      yaxis: {
        range: [0, numBins - 1],
        type: 'linear',
        showgrid: false,
        title: 'Gene expression (log10 scale)',
        tickvals: Array.from({ length: numBins }, (_, i) => i),
        ticktext: Array.from({ length: numBins }, (_, i) => {
          const logVal = minLogNC + (i / (numBins - 1)) * (maxLogNC - minLogNC)
          return Math.pow(10, logVal).toFixed(2)  // 显示原始值
        })
      },
    };

    const trace = {
      zauto: false,
      x: categories,
      y: Array.from({ length: numBins }, (_, i) => i),
      z: heatmapData,
      type: 'heatmap',
      colorscale: [
        [0.0, 'rgba(220, 220, 220, 0.1)'],
        [1.0, 'rgb(93, 116, 162)']
      ],
      zmin: 0,  // 设置热图颜色的最小值
      zmax: Math.max(...heatmapData.flat()),
    };

    Plotly.newPlot('expressionHeatmap', [trace], layout);
    coord_chartGeneLoading.value = false;
  } catch (error) {
    console.error('Failed to load genes:', error);
  }

};

// 更新 UMAP 图2 的点大小
const updateUmap2 = () => {
  Plotly.restyle('coord_chart_gene', 'marker.size', [markerSize2.value]);
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
  fetch(config.apiUrl + `std_DEG_CellType.php?${params}`)
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
  fetch(config.apiUrl + `std_getDEG_ByCluster.php?${params}`)
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

const headers = ['gene name', 'Adjusted p-value', 'Log fold-change', 'Pct.1', 'Pct.2'];
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

.st-coord-content {
  display: flex;
  flex-direction: column;
  /* 子元素垂直排列 */
  flex: 1;
  gap: 30px;
}

/* ----------------------------------------------------------- */
</style>