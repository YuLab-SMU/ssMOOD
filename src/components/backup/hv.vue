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
                        
                        <!-- Submenu -->
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
                                <button @click="showModal = true" class="execute-button">{{ $t('hv16') }}</button>
                                <WebRWindow v-model="showModal" :code1="code" :data1="jsondata" :title="$t('hv17')"/>
                        </div>
                        <div class="image">
                            <canvas id="plot-canvas" width="400px" height="400px"></canvas>
                        </div>
                </div>
                <back-to-top v-if="!showModal" />
                </div>
            </div>
            </section>
        </main>
    </div>
    
   
</template>

<script>
import Plotly from 'plotly.js-dist-min';

import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import pako from 'pako';

const WebRWindow = () => import('./general/WebRWindows.vue');

export default {
  name: 'HomeView',
  components: {
    BackToTop,
    LanguageSwitcher,
    WebRWindow
  },
  data() {
    return {
      cellNum: 0,
      geneNum: 0,
      clusterNum: 0,
      spatialNum: 0,
      showSubMenu: false,
      showModal: false,
      jsondata:"",
      code:``
    };
  },
      watch: {
        showModal(newVal) {
            if (newVal) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }
    },

async mounted() {
    this.loadData();
    this.initCharts();
    
  },
  
    created() {
        this.$i18n.locale = this.$root.$selectedLanguage || 'zh1';
      },
  methods: {
     
    onLanguageChanged(language) {
      this.$i18n.locale = language;
    },

    async loadData() {
      try {
        const response = await fetch('../php/hv_dbInfo.php'); // Example API
        const data = await response.json();
        this.cellNum = data.cellNum;
        this.geneNum = data.geneNum;
        this.clusterNum = data.clusterNum;
        this.spatialNum = data.spatialNum;
      } catch (error) {
        console.error('Error loading data:', error);
      }
    },
    initCharts() {
      // Draw the first chart
    fetch('../php/hv_chart1.php')
      .then(response => response.json())
      .then(dataFromPhp => {
        // Extract labels and cell counts
        const labels = Object.keys(dataFromPhp);
        const cellCounts = Object.values(dataFromPhp);
    
        // Build bar chart data
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
    
        // Build the layout
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
    
        // Draw the chart
        Plotly.newPlot('myClusterChart', [trace], layout);
      })
      .catch(error => console.error('Error fetching data from chart1.php:', error));
            
        // Draw the second chart
        fetch('../php/hv_gender_data.php')
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
                paper_bgcolor: 'rgba(0,0,0,0)', // Make the chart background transparent
                plot_bgcolor: 'rgba(0,0,0,0)', // Make the plot area background transparent
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
            .
        catch (error => console.error('Error fetching gender data:', error));

        // Draw UMAP chart
        fetch('../php/hv_umap.php')
          .then(response => response.arrayBuffer()) // Get ArrayBuffer response
          .then(arrayBuffer => {
            const compressed = new Uint8Array(arrayBuffer);
            const decompressed = pako.ungzip(compressed); // Decompress with pako
            const jsonString = new TextDecoder('utf-8').decode(decompressed);
            const data = JSON.parse(jsonString); // Parse JSON string
        
            const umapData = data.umap;
            const clusterLabels = data.cluster;
            
            const labelMap = new Map();
            clusterLabels.forEach((label, index) => {
                labelMap.set(label, index);
            });
        
            // Sort clusterLabels
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
  
  
      // Draw DEG chart
      fetch('../php/hv_get_diff_exp_data.php')
        .then(response => response.json())
        .then(data => {
          const geneIds = data.map(d => d.gene_id);
          const pValues = data.map(d => -Math.log10(d.p_value));
          const foldChanges = data.map(d => d.fold_change);

          const trace = {
            x: foldChanges,
            y: pValues,
            mode: 'markers',
            type: 'scatter',
            //text: geneIds,
            marker: {
              color: foldChanges.map(fc => fc > 1 ? 'rgba(142, 45, 48 ,0.8)' : 'rgba(93, 116, 162 ,0.8)'),
              size: 4
            },
            hoverinfo: 'text',
            text: foldChanges.map((fc, i) => `Gene ID: ${geneIds[i]}\nFold Change: ${fc}\n-Log10(p-value): ${pValues[i]}`)
          };

          const layout = {
            title: '差异表达分析',
            xaxis: {
              title: 'Fold Change'
            },
            yaxis: {
              title: '-log10(p-value)'
            },
              paper_bgcolor: 'rgba(0,0,0,0)', // Make the chart background transparent
              plot_bgcolor: 'rgba(0,0,0,0)'   // Make the plot area background transparent
          };

          Plotly.newPlot('volcanoChart', [trace], layout);
        })
        .catch(error => console.error('Error fetching data:', error));
        
        
        fetch('../php/hv_get_diff_exp_data.php')
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
  })
  .then(data => {
    this.jsondata = JSON.stringify(data);
    // Use jsondata here
  })
  .catch(error => {
    console.error('There has been a problem with your fetch operation:', error);
  });
    },
    
    
    
    },
};


/*
# The data variable is preloaded
data$p_value <- as.numeric(as.character(data$p_value))
data$fold_change <- as.numeric(as.character(data$fold_change))
# Compute negative log p-values
data$negLogP <- -log10(data$p_value)
# Create a color vector based on fold_change values
colors <- ifelse(data$fold_change > 1, 'red', ifelse(data$fold_change < -1, 'blue', 'black'))
# Draw the volcano plot
plot(data$fold_change, data$negLogP, xlab = "Fold Change", ylab = "-log10(p-value)",
     main = "Volcano Plot", pch = 19, col = colors)
# Add significance threshold lines
abline(h = -log10(0.05), col = "blue", lty = 2) # p-value threshold line
abline(v = c(-1, 1), col = "blue", lty = 2) # fold-change threshold lines

async executeCode() {
        const canvas = document.getElementById('plot-canvas');
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        console.log('R code executed:');
        const response = await fetch('../php/get_diff_exp_data.php');
        const data = await response.json();
        let jsondata = JSON.stringify(data);
        
        await this.newwebR.evalRVoid(`
            webr::canvas()
            webr::install("jsonlite")
            library(jsonlite)
            jsondata <- '${jsondata}'
            data <- fromJSON(jsondata)
            ${this.code}
            dev.off()
        `);
        const msgs = await this.newwebR.flush();
        msgs.forEach(msg => {
        if (msg.type === 'canvas' && msg.data.event === 'canvasImage')
        {
            const canvas = document.getElementById('plot-canvas');
            //canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            canvas.getContext('2d').drawImage(msg.data.image, 0, 0, canvas.width, canvas.height);
        } 
        else {
            console.log(msg);
            if (msg.type === 'stdout')
            {
            this.terminalOutput += msg.data+"\n";
            }
            else
            {
            this.terminalOutput += msg+"\n";
            }
        }
        });
    }
*/
</script>

<style scoped>
@import 'css/MainStyles.css';

.test {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: flex-start; /* Align to top */
    /*height: 100vh;  make the container viewport-high for vertical centering */
}

.test img {
    width: 100%; /* Keep image width within the container */
    height: auto; /* Preserve the image's aspect ratio */
}
.image-text-container {
    display: flex;
    flex-wrap: wrap; /* Wrap on smaller screens */
    justify-content: space-between; /* Lay out images and text horizontally */
}

.image-text-item {
    display: flex;
    align-items: center; /* Vertically center images and text */
    margin: 10px;
    text-align: left; /* Left-align text */
    max-width: calc(25% - 20px); /* Ensure items lay out properly on small screens; compute each item's max width */
}

.image-text-item img {
    max-width: 100px; /* Max width of images */
    height: auto; /* Preserve the image's aspect ratio */
    margin-right: 10px; /* Spacing between image and text */
}

.image-text-item p {
    margin: 0; /* Zero margin on text to avoid extra spacing */
}
.container {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 80vh; /* Make the outer container full-screen height */
}
.container[data-special="true"] {
    background-color: rgba(84, 164, 255, 0.1);
}
.container .image {
    margin-right: 20px;
    /* Spacing between image and text */
}
.container .text {
    max-width: 400px;
    /* Limit text width to 300px */
    /* Or use width instead of max-width for a fixed width */
    word-wrap: break-word;
    /* Wrap text automatically */
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
  cursor: pointer; /* Show hand cursor on hover */
  outline: none; /* Remove outline on focus */
}

.execute-button:hover {
  background-color: rgba(093, 116, 162, 0.8); /* Background color on hover */
}

 /* ----------------------------------------------------------- */
  /* Portrait-orientation responsive styles*/
 @media (orientation: portrait) or (max-width: 800px) {
    .image-text-container {
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Distribute space evenly between images and text */
        height: auto;
        width: auto;
        gap: 5px;
    }
    
    .image-text-container p {
        white-space: nowrap; /* Prevent text wrapping */
    }
    .container .text {
            max-width: 800px; /* Limit text width to 300px */
            /* Or use width instead of max-width for a fixed width */
        }
    .container {
        flex-direction: column;
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        height: auto; /* Make the outer container full-screen height */
        width: 100%;
    }
    .Top-container{
        width: 100%;
        flex-direction: column;
        display: flex;
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
        gap: 50px;
    }
    .main-con{
        width: 100%;
        gap: 15px;
    }
    #umap-plot {
        width: 100%; /* Full screen width */
        height: auto; /* Auto height */
    }
    #volcanoChart {
        width: 100%; /* Full screen width */
        height: auto; /* Auto height */
    }
}

</style>
