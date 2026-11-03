<template>
  <div>
    <NavigationBar></NavigationBar>
    <main>
      <section class="page-section">
        <div class="main-con">
          <!-- <div class="Top-container" @scroll="handleScroll"> -->
          <div class="Top-container">

            <!-- First container -->
            <el-row :gutter="20">
              <el-col :span="24">
                <div class="test">
                  <video ref="video" src="/ssmood.mov" muted autoplay playsinline loop :playbackRate="0.8"
                    @ended="handleEnded" @loadedmetadata="handleLoaded" style="width: 80%;" preload="auto"></video>
                </div>
              </el-col>
            </el-row>
            <el-row :gutter="10" class="container1">
              <el-col :xs="0" :sm="2" :md="2" :lg="2"></el-col>
              <el-col :xs="12" :sm="5" :md="5" :lg="5" v-for="(item, index) in items" :key="index">
                <div class="image-text-item">
                  <img :src="item.image" :alt="item.alt">
                  <p>{{ $t(item.textKey) }}<br>{{ item.value }}</p>
                </div>
                <el-col :xs="0" :sm="2" :md="2" :lg="2"></el-col>
              </el-col>
            </el-row>
            <!-- Second container -->
            <el-row :gutter="20" class="container">
              <el-col :xs="0" :sm="0" :md="2" :lg="2">
              </el-col>
              <!-- Text section -->
              <el-col :xs="20" :sm="20" :md="8" :lg="8">
                <div class="text">
                  <h2>{{ $t('hv5') }}</h2>
                  <p>{{ $t('hv6') }}</p>
                </div>
              </el-col>
              <!-- Image and chart toggle section -->
              <el-col :xs="20" :sm="20" :md="12" :lg="12">
                <div class="image">
                  <div class="chart-switch">
                    <div class="chart-item" :class="{ 'active': currentChart === 3 }" @click="switchChart(3)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-4') }}</span>
                    </div>
                    <div class="chart-item" :class="{ 'active': currentChart === 2 }" @click="switchChart(2)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-3') }}</span>
                    </div>
                    <div class="chart-item" :class="{ 'active': currentChart === 1 }" @click="switchChart(1)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-2') }}</span>
                    </div>

                    <div class="chart-item" :class="{ 'active': currentChart === 0 }" @click="switchChart(0)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-1') }}</span>
                    </div>


                  </div>
                  <div id="myClusterChart" style="width: 100%; height: auto;"></div>
                </div>
              </el-col>
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
            </el-row>
            <!-- Third container -->
            <el-row :gutter="20" class="container" data-special="true">
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
              <!-- Column 1: chart 1 -->
              <el-col :xs="24" :sm="12" :md="7" :lg="7">
                <div class="chart-wrapper">
                  <div id="genderChart" style="width: auto; height: 100%;"></div>
                </div>
              </el-col>

              <!-- Column 2: chart 2 -->
              <el-col :xs="24" :sm="12" :md="7" :lg="7">
                <div class="chart-wrapper">
                  <div id="genderChart2" style="width: auto; height: 100%;"></div>
                </div>
              </el-col>

              <!-- Column 3: text content -->
              <el-col :xs="20" :sm="20" :md="6" :lg="6">
                <div class="text-content">
                  <h2>{{ $t('hv7') }}</h2>
                  <p>{{ $t('hv8') }}</p>
                </div>
              </el-col>
            </el-row>
            <!-- Fourth container -->
            <el-row :gutter="20" class="container">
              <!-- Text section -->
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
              <el-col :xs="20" :sm="20" :md="10" :lg="10">
                <div class="text">
                  <h2>{{ $t('hv9') }}</h2>
                  <ul>
                    <li>{{ $t('hv10-1') }}</li>
                  </ul>
                  <ul>
                    <li>{{ $t('hv10-2') }}</li>
                  </ul>
                  <ul>
                    <li>{{ $t('hv10-3') }}</li>
                  </ul>
                </div>
              </el-col>
              <!-- Image and chart toggle section -->
              <el-col :xs="20" :sm="20" :md="10" :lg="10">
                <div class="image">
                  <div class="chart-switch">
                    <div class="chart-item" :class="{ 'active': currentUmapChart === 3 }" @click="switchUmapChart(3)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-4') }}</span>
                    </div>
                    <div class="chart-item" :class="{ 'active': currentUmapChart === 2 }" @click="switchUmapChart(2)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-3') }}</span>
                    </div>
                    <div class="chart-item" :class="{ 'active': currentUmapChart === 1 }" @click="switchUmapChart(1)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-2') }}</span>
                    </div>
                    <div class="chart-item" :class="{ 'active': currentUmapChart === 0 }" @click="switchUmapChart(0)">
                      <div class="chart-dot"></div>
                      <span>{{ $t('hv6-1') }}</span>
                    </div>
                  </div>
                  <!-- UMAP plot container -->
                  <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                    <!-- Loading image -->
                    <ChartLoading v-if="umapLoading" />

                    <!-- Plotly chart container -->
                    <div id="hv-umap-plot"
                      :style="{ width: '100%', aspectRatio: '1 / 1', visibility: umapLoading ? 'hidden' : 'visible' }">
                    </div>
                  </div>

                </div>
              </el-col>
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
            </el-row>
            <!-- Fifth container -->
            <el-row :gutter="20" class="container" data-special="true">
              <!-- Image section -->
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
              <el-col :xs="20" :sm="20" :md="10" :lg="10">
                <div class="image">
                  <img src="@/assets/demo1.png" width="80%" />
                </div>
              </el-col>
              <!-- Text section -->
              <el-col :xs="20" :sm="20" :md="10" :lg="10">
                <div class="text">
                  <h2>{{ $t('hv11') }}</h2>
                  <p>{{ $t('hv12') }}</p>
                </div>
              </el-col>
              <el-col :xs="0" :sm="0" :md="2" :lg="2"></el-col>
            </el-row>
            <!-- Sixth container -->
            <!-- <div class="container">
                  <div class="text">
                    <h2>{{ $t('hv13') }}</h2>
                    <p>{{ $t('hv14') }}</p>
                    <button @click="gotoAnalyzePage" class="goto-button">{{ $t('hv16') }}</button>
                  </div>
                  <div class="image">
                    <canvas id="plot-canvas" width="400px" height="400px"></canvas>
                  </div>
                </div> -->

          </div>
          <BackToTop :target-selector="'.Top-container'"></BackToTop>

        </div>

      </section>

    </main>

  </div>


</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import pako from 'pako';
import { ref, onMounted } from 'vue';
//import { useRouter } from 'vue-router';

//----------Below is the bare minimum needed for an ssmood page--------------
//import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
//import LanguageSwitcher from './general/LanguageSwitcher.vue';
import NavigationBar from './general/NavigationBar.vue';
import ChartLoading from './general/ChartLoading.vue';
import config from '@/config';
//----------Above is the bare minimum needed for an ssmood page--------------

import colorMap from './color_map.js';
const video = ref(null)

function handleLoaded() {
  const el = video.value
  el.play()
}

function handleEnded() {
  const el = video.value

  // Avoid flicker: jump to 0.01 immediately instead of 0
  el.currentTime = 0.01
  el.play()
}
//-------------------
//Handle scroll-positioning logic
//-------------------
/*
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

  // Add animation class
  containers.forEach((container) => {
    container.classList.remove('fade-in');
  });
  closestContainer.classList.add('fade-in');
};

throttle(handleScroll, 300); // Run at most once per 100ms
*/

//-------------------
//Fetch basic database info
//-------------------
// Initialize data
onMounted(async () => {
  try {
    const response = await fetch(config.apiUrl + 'hv_dbInfo.php'); // Example API
    const data = await response.json();
    cellNum.value = data.cellNum;
    geneNum.value = data.geneNum;
    clusterNum.value = data.clusterNum;
    spatialNum.value = data.spatialNum;
  } catch (error) {
    console.error('Error loading data:', error);
  }
});



const currentChart = ref(3); // Index of the currently displayed chart
const cellNum = ref(0);
const geneNum = ref(0);
const clusterNum = ref(0);
const spatialNum = ref(0);
const items = [
  { image: require('@/assets/image1.png'), alt: 'Image 1', textKey: 'hv3', value: clusterNum },
  { image: require('@/assets/image3.png'), alt: 'Image 3', textKey: 'hv1', value: cellNum },
  { image: require('@/assets/image4.png'), alt: 'Image 4', textKey: 'hv4', value: spatialNum },
  { image: require('@/assets/image2.png'), alt: 'Image 2', textKey: 'hv2', value: geneNum }
];
// Cached chart data
const chartDataCache = ref([]);

const renderChart_title = ['snRNA-seq Datasets(Mouse)', 'ST Datasets(Mouse)', 'snRNA-seq Datasets(Human)', 'ST Datasets(Human)']

// Draw the chart
const renderChart = (index) => {
  const data = chartDataCache.value[index];
  if (!data) return; // Return early if there is no data

  // Extract labels and cell counts
  const labels = Object.keys(data);
  const cellCounts = Object.values(data);

  // Build bar chart data
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

  // Build the layout
  const layout = {
    title: renderChart_title[index],
    xaxis: {
      title: '',
      tickangle: -45, // Rotate labels 45 degrees
      tickmode: 'linear', // Keep labels evenly spaced
      tickfont: { size: 8 } // Adjust font size
    },
    yaxis: {
      title: '',
      showgrid: true,
      zeroline: true
    }
  };

  // Draw the chart
  Plotly.newPlot('myClusterChart', [trace], layout);
};

// Switch chart
const switchChart = (index) => {
  currentChart.value = index;
  renderChart(index);
};


// Load data
onMounted(async () => {
  try {
    const response = await fetch(config.apiUrl + 'hv_datasets.php');
    const arrayBuffer = await response.arrayBuffer();
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed);
    const jsonString = new TextDecoder('utf-8').decode(decompressed);
    const data = JSON.parse(jsonString);
    chartDataCache.value = data;
    renderChart(3); // Render chart 0 by default
  } catch (error) {
    console.error('Error fetching and decompressing UMAP data:', error);
  }
});



//-------------------
//Fetch species proportions
//-------------------
onMounted(async () => {
  fetch(config.apiUrl + 'hv_gender_data.php')
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
        paper_bgcolor: 'rgba(0,0,0,0)', // Make the chart background transparent
        plot_bgcolor: 'rgba(0,0,0,0)', // Make the plot area background transparent
        margin: {
          l: 50,
          r: 50,
          b: 100,
          t: 80
        },
        showlegend: true,
        legend: {
          x: 0.3,
          y: -0.5, // Place the legend at the bottom center
          orientation: 'w', // Lay out legend items horizontally
          bgcolor: 'rgba(0,0,0,0)',
          bordercolor: 'grey',
          borderwidth: 1
        }
      };

      Plotly.newPlot('genderChart', genderData, layout);
    })
    .catch(error => console.error('Error fetching gender data:', error));
});
//-------------------
//Fetch single-cell vs. spatial transcriptomics proportions
//-------------------
onMounted(async () => {
  fetch(config.apiUrl + 'hv_scandstRaio.php')
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
        paper_bgcolor: 'rgba(0,0,0,0)', // Make the chart background transparent
        plot_bgcolor: 'rgba(0,0,0,0)', // Make the plot area background transparent
        margin: {
          l: 50,
          r: 50,
          b: 100,
          t: 80
        },
        showlegend: true,
        legend: {
          x: 0.2,
          y: -0.5, // Place the legend at the bottom center
          bgcolor: 'rgba(0,0,0,0)',
          bordercolor: 'grey',
          borderwidth: 1
        }
      };

      Plotly.newPlot('genderChart2', genderData, layout);
    })
    .catch(error => console.error('Error fetching gender data:', error));
});
//------------------------
//Fetch single-cell and spatial transcriptomics UMAPs
//------------------------

const currentUmapChart = ref(3); // Show chart 0 by default
const umapData = ref({}); // Store data for the 4 charts
const title_x = ["UMAP1", "coordinate_X", "UMAP1", "coordinate_X"];
const title_y = ["UMAP2", "coordinate_Y", "UMAP2", "coordinate_Y"];

const pointSize = [5, 2.5, 5, 2]

// Switch UMAP chart
const switchUmapChart = (index) => {
  currentUmapChart.value = index;
  renderUmapChart(index);
};


// Render UMAP chart
const renderUmapChart = (index) => {
  const data = umapData.value[index];
  if (!data) return;

  const oneUmapData = data.umap;
  const clusterLabels = data.cluster;

  const sortedClusterLabels = [...clusterLabels].sort((a, b) => {
    const partsA = a.match(/\d+/);
    const partsB = b.match(/\d+/);
    if (partsA && partsB) {
      // If both strings contain numbers, sort numerically
      return parseInt(partsA[0], 10) - parseInt(partsB[0], 10);
    } else if (partsA) {
      // If only a contains a number, a comes before b
      return -1;
    } else if (partsB) {
      // If only b contains a number, b comes before a
      return 1;
    } else {
      // If neither string contains a number, sort alphabetically
      return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
    }
  });


  const colors = sortedClusterLabels.reduce((acc, label) => {
    acc[label] = colorMap[label] || '#000'; // Fall back to the default color #000 if no matching color is found
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

  Plotly.newPlot('hv-umap-plot', traces, layout);
};
const umapLoading = ref(true)
// Load data
onMounted(async () => {
  umapLoading.value = true
  try {
    const response = await fetch(config.apiUrl + 'hv_umap.php');
    const arrayBuffer = await response.arrayBuffer();
    const compressed = new Uint8Array(arrayBuffer);
    const decompressed = pako.ungzip(compressed);
    const jsonString = new TextDecoder('utf-8').decode(decompressed);
    const data = JSON.parse(jsonString);

    umapData.value = {
      0: data[0], // Single-cell (mouse)
      1: data[1], // Spatial (mouse)
      2: data[2], // Single-cell (human)
      3: data[3]  // Spatial (human)
    };

    renderUmapChart(3); // Render chart 0 by default
    umapLoading.value = false
  } catch (error) {
    console.error('Error fetching and decompressing UMAP data:', error);
  }
});

//------
onMounted(() => {
  window.addEventListener('resize', resizeMyChart);
});
const resizeMyChart = () => {
  Plotly.Plots.resize('myClusterChart');
  Plotly.Plots.resize('genderChart');
  Plotly.Plots.resize('genderChart2');
  Plotly.Plots.resize('hv-umap-plot');
};

import { onUnmounted } from 'vue';
onUnmounted(() => {
  window.removeEventListener('resize', resizeMyChart);
});
//------------------------
//Navigate to the analysis page
//------------------------
/*
const router = useRouter();
const gotoAnalyzePage = () => {
  router.push('/analyze'); 
};
*/
</script>

<style scoped>
@import 'css/MainStyles.css';

.main {
  overflow-y: hidden;
}

.page-section {
  padding: 0px;
  flex: 1;
  /* Fill the remaining space */
}

.Top-container {
  overflow-y: scroll;
  overflow-x: hidden;
  height: 92vh;
  /* scroll-snap-type: y mandatory;  enable scroll snapping */
  justify-content: center;
  /* Center horizontally */
}

/* Overall scrollbar width */
.Top-container::-webkit-scrollbar {
  width: 10px;
  /* Width */
}

/* Scrollbar track background */
.Top-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  /* Track background color */
  border-radius: 10px;
  /* Track corner radius */
}

/* Scrollbar thumb (draggable part) */
.Top-container::-webkit-scrollbar-thumb {
  background: rgba(093, 116, 162, 0.8);
  /* Thumb color */
  border-radius: 10px;
  /* Thumb corner radius */
}

/* Thumb hover style */
.Top-container::-webkit-scrollbar-thumb:hover {
  background: #555;
  /* Color on hover */
}

.container {
  display: flex;
  justify-content: center;
  /* Center horizontally */
  align-items: center;
  /* Center vertically */
  min-height: 80vh;
  height: auto;
  width: 100%;
  transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}

.container1 {
  width: 80%;
  margin: 0 auto;
  padding: 0 20px;
  width: 100%;

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


.container[data-special="true"] {
  background-color: rgba(84, 164, 255, 0.1);
}

.container .image {
  display: flex;
  flex-direction: column;
  align-items: center;
  /* Center vertically */
  height: 100%;
}

.container .text {
  width: 100%;
  /* Limit text width to 300px */
  /* Or use width instead of max-width for a fixed width */
  word-wrap: break-word;
  /* Wrap text automatically */
}

.container.fade-in {
  opacity: 1;
  transform: translateY(0);
}


.test {
  display: flex;
  justify-content: center;
  /* Center horizontally */
  align-items: flex-start;
  /* Align from the top of the container */
  margin: 0;
  /* No extra spacing in the container */
  position: relative;
  /* Use relative positioning */
}

.test video {
  width: 80%;
  /* Keep image width within the container */
  height: auto;
  /* Preserve the image's aspect ratio */
  position: relative;
  /* Use relative positioning */
  top: 20px;
  /* Image is 20px from the top */
  padding: 30px;
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
  margin-right: 8px;
  /* Spacing between the dot and the text */
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.chart-item.active .chart-dot {
  background-color: rgb(093, 116, 162);
  animation: jump 0.5s forwards;
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

.image-text-item {
  display: flex;
  /* Enable flex layout */
  align-items: center;
  /* Center vertically */
  justify-content: center;
  /* Center horizontally (optional) */
  gap: 15px;
  /* Spacing between image and text */
  text-align: left;
  /* Left-align text */
  padding: 10px;
}

.image-text-item img {
  max-width: 40%;
  height: auto;
  margin: 0;
  /* Clear any default margins */
}

.chart-wrapper {
  padding: 15px;
  height: 100%;
  width: 100%;
}


.goto-button {
  padding: 10px 20px;
  margin-top: 10px;
  background-color: rgb(093, 116, 162);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  /* Show hand cursor on hover */
  outline: none;
  /* Remove outline on focus */
}

.execute-button:hover {
  background-color: rgba(093, 116, 162, 0.8);
  /* Background color on hover */
}


/* ----------------------------------------------------------- */
/* Portrait-orientation responsive styles*/
@media (orientation: portrait) or (max-width: 800px) {
  .test img {
    width: 100%;
    /* Keep image width within the container */
    height: auto;
    /* Preserve the image's aspect ratio */
    position: relative;
    /* Use relative positioning */
    top: 20px;
    /* Image is 20px from the top */
  }

  .container1 {
    max-width: 100vw;
    margin: 0;
    padding: 0;
  }

  .image-text-container p {
    white-space: nowrap;
    /* Prevent text wrapping */
  }

  .image-text-item img {
    max-width: 70%;
    height: auto;
  }

  .image-text-item {
    flex-direction: column;
    /* Switch to vertical layout */
    align-items: center;
    /* Center alignment */
    text-align: center;
    /* Center text */
    gap: 8px;
    /* Adjust spacing */
  }

  .container {
    width: 100%;
    height: auto;
  }

  .Top-container {
    width: 100%;
    flex-direction: column;
    align-items: center;
    /* Center vertically */
    justify-content: center;
    /* Center horizontally */
    gap: 50px;
  }

  .main-con {
    width: 100%;
    gap: 15px;
  }

  .chart-wrapper {
    height: 40vh;
    width: auto;
  }

  .text-content {
    padding: 30px 20px;
  }


}
</style>
