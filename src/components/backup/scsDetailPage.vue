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
<!--Details container-->
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
<!--Cell type container-->
  <div class="information-container">
    <div class="title-bar">
      <h1>{{ $t('scd20') }}</h1>
    </div>
    <div class="sc-cluster-content">
      <!-- Left side: UMAP plot and marker size controls -->
      <div class="information-first">
        <h1>{{ $t('scd21') }}</h1>
        <div class="marker-size-controller">
          <div class="label">{{ $t('scd22') }}:</div>
          <!-- Decrease button -->
          <v-btn class="custom-plus" icon @click="decreaseSize1">
            -
          </v-btn>
          <!-- Show current size -->
          <span class="size-value">{{ markerSize1 }}</span>
          <!-- Increase button -->
          <v-btn class="custom-plus" icon @click="increaseSize1">
            +
          </v-btn>
        </div>
        <div id="umap-plot"></div>
      </div>
      <!-- Right side: gene search and gene list -->
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
            <!-- Decrease button -->
            <v-btn class="custom-plus" icon @click="decreaseSize2">
              -
            </v-btn>
            <!-- Show current size -->
            <span class="size-value">{{ markerSize2 }}</span>
            <!-- Increase button -->
            <v-btn class="custom-plus" icon @click="increaseSize2">
              +
            </v-btn>
          </div>
        </div>

        <!-- Scrollable gene list -->
        <recycle-scroller
          v-if="showScroller"
          class="scroller"
          ref="scroller"
          :items="filteredGenes"
          :item-size="6"
          :buffer="2000"
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
<!--Differential expression analysis container-->
<div class="information-container">
    <div class="title-bar">
         <h1>{{ $t('scd25') }}</h1>

    </div>
    <div class="information-content">
        <div class="information-left">
            <div class="de-analysis  modern-ui">
                <div class="note">
                    <p>Note: The MAST package were used to calculate DEGs on normalized gene expression data (parameter: log2FC  0.25, adj.p-value 0.05).</p>
                </div>
                <div class="group">
                    <label>Group:</label>
                    <select v-model="group">
                        <option value="cellTypeSpecificGenes">Cell type specific genes</option>
                    </select>
                </div>
                <div class="cell-type">
                    <label>Cell type of interest:</label>
                    <select v-model="cellType">
                        <option value="inhibitoryNeurons">Inhibitory neurons</option>
                    </select>
                </div>
                <div class="log2fc">
                    <label>log2 fold-change cutoff:</label>
                    <input type="range" min="0.1" max="2" step="0.1" v-model="log2fc" /> <span>{{ log2fc }}</span>

                </div>
                <div class="adjusted-pvalue">
                    <label>Adjusted p-value cutoff:</label>
                    <input type="range" min="0.00001" max="0.1" step="0.00001" v-model="pvalue" /> <span>{{ pvalue }}</span>

                </div>
                <div class="de-direction">
                    <label>DE direction:</label>
                    <div class="de-direction-child">
                        <input type="radio" id="all" value="all" v-model="direction" />
                        <label for="all">All</label>
                        <input type="radio" id="up" value="up" v-model="direction" />
                        <label for="up">UP</label>
                        <input type="radio" id="down" value="down" v-model="direction" />
                        <label for="down">Down</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="information-right">
            
            <div class="de-analysis  modern-ui">
                <div class="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Gene name</th>
                      <th>Log fold-change</th>
                      <th>Pct.1</th>
                      <th>Pct.2</th>
                      <th>Adjusted p-value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in paginatedData" :key="item.i">
                      <td>{{ item.i }}</td>
                      <td>{{ item.f }}</td>
                      <td>{{ item.Pct_1 }}</td>
                      <td>{{ item.Pct_2 }}</td>
                      <td>{{ item.p }}</td>
                    </tr>
                  </tbody>
                </table>
                <div class="pagination">
                  <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
                  <span>Page {{ currentPage }} of {{ totalPages }}</span>
                  <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
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
        <back-to-top />
    </div>
</template>

<script>
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import Plotly from 'plotly.js-dist-min';
import { RecycleScroller } from 'vue3-virtual-scroller';
import pako from 'pako';

export default {
  name: 'ScDatasetDetailsPage',
  data() {
    return {
      showSubMenu: false,
      datasetId: this.$route.params.study,
    markerSize1: 4, // Default point size
    markerSize2: 4, // Default point size
      genes: [],
      
      filteredGenes: [],
      umapData: [],
      searchQuery: '',
      showScroller: false,
      dataset: {
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
      },
      //------------------------------------------------------
      //Differential expression analysis
      group: 'cellTypeSpecificGenes',
      cellType: 'inhibitoryNeurons',
      log2fc: 0.5,
      pvalue: 0.05,
      direction: 'all',
        DEGdata: [],
        currentPage: 1,
    itemsPerPage: 10,
      //------------------------------------------------------
      
    };
  },
  components: {
    BackToTop,
    LanguageSwitcher,
    RecycleScroller,
  },
    computed: {
      totalPages() {
        // Total pages are computed from the filtered dataset
        return Math.ceil(this.filteredData.length / this.itemsPerPage);
      },
      paginatedData() {
        // Apply pagination to the filtered dataset
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredData.slice(start, end);
      },
      filteredData() {
        // Filtering logic unchanged
        return this.DEGdata.filter(item => {
          const logFoldChange = parseFloat(item.f);
          const adjustedPvalue = parseFloat(item.p);
          return Math.abs(logFoldChange) >= this.log2fc && adjustedPvalue <= this.pvalue;
        });
      },
    },
  mounted() {
    this.fetchDatasetDetails();
     console.log('Dataset ID:', this.datasetId);
     this.initCharts();
     this.loadGenes();
     this.initDEG();
  },
    watch: {
    searchQuery(newQuery) {
      this.filterGenes(newQuery);
    }
  },
  methods: {
     //------------------------------------------------------
    fetchDatasetDetails() {
        const params = new URLSearchParams({
          id: this.$route.params.id
        });
    
        fetch(`../php/scd_getSCDatasetDetail.php?${params}`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            const dataArray = JSON.parse(data);
    
            if (dataArray && dataArray.length > 0 && dataArray[0].information) {
              this.$set(this, 'dataset', dataArray[0]);
              this.$set(this.dataset, 'information', JSON.parse(dataArray[0].information));
            }
    
            //console.log(this.dataset);
          })
          .catch(error => {
            console.error('Error fetching dataset details:', error);
          });
      },
      //------------------------------------------------------
    onLanguageChanged(language) {
      this.$i18n.locale = language;
    },
    //------------------------------------------------------
    
    initCharts() {
        const params = new URLSearchParams({
          studyid: this.$route.params.study
        });
         fetch(`../php/scs_getumapdata.php?${params}`)
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => {
            
        const compressed = new Uint8Array(arrayBuffer);
        const decompressed = pako.ungzip(compressed); // Decompress with pako
        const jsonString = new TextDecoder('utf-8').decode(decompressed);
        const data = JSON.parse(jsonString); // Parse JSON string
        
        //console.log(data);
          this.umapData = data.umap_data;
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
          const umap1 = this.umapData.map(d => parseFloat(d.u1));
          const umap2 = this.umapData.map(d => parseFloat(d.u2));
          const cellIds = this.umapData.map(d => d.i);
          const clusterLabelsData = this.umapData.map(d => d.c);


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
                size:  this.markerSize1,
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
            orientation: 'v', // Stack legend items vertically
            xanchor: 'left', // Anchor legend x to the left
            yanchor: 'bottom', // Anchor legend y to the bottom
            x: 1, // Horizontal legend position; 1 means far right
            y: 0, // Vertical legend position; 0.5 means middle
            font: {
              size: 12 // Legend text size
            },
            itemwidth: 30, // Width of each legend item
            itemheight: 20 // Height of each legend item
          },

        };

    
          Plotly.newPlot('umap-plot', traces, layout);
          
        })
        .catch(error => console.error('Error fetching UMAP data:', error));
        
        //Preload gene UMAP plot
        /*
        const initialTraces = this.umapData.slice(0, 100).map((point) => ({
          x: [point.u1],
          y: [point.u2],
          mode: 'markers',
          type: 'scattergl',
          name: "",
          marker: {
            color: 'rgba(128, 128, 128, 0.2)',
            size: this.markerSize2,
          },
          text: this.getTooltipText(point), // Optional: show cell_id info
        }));
        
        const layout = {
          title: '',
          xaxis: {
            title: 'UMAP1',
          },
          yaxis: {
            title: 'UMAP2',
          },
          showlegend: false,
        };
        
        Plotly.newPlot('umap-chart-gene', initialTraces, layout);
        */
        },
    //------------------------------------------------------
        updateUmap1() {
            Plotly.restyle('umap-plot', 'marker.size', [this.markerSize1]);
        },
        decreaseSize1() {
            if (this.markerSize1 > 1) {
                this.markerSize1 -= 1;
                this.updateUmap1();
            }
        },
        increaseSize1() {
            if (this.markerSize1 < 10) {
                this.markerSize1 += 1;
                this.updateUmap1();
            }
        },
        updateUmap2() {
            Plotly.restyle('umap-chart-gene', 'marker.size', [this.markerSize2]);
        },
        decreaseSize2() {
            if (this.markerSize2 > 1) {
                this.markerSize2 -= 1;
                this.updateUmap2();
            }
        },
        increaseSize2() {
            if (this.markerSize2 < 10) {
                this.markerSize2 += 1;
                this.updateUmap2();
            }
        },
    //------------------------------------------------------
    
    async loadGenes() {
        const params = new URLSearchParams({
            id: this.$route.params.id
        });
        try {
            const response = await fetch(`../php/scd_getgene.php?${params}`);
            if (!response.ok) {
                throw new Error(`HTTP error!status:${response.status}`);
            }
            const data = await response.json();
            if (!data || !Array.isArray(data.genes)) {
                throw new Error('Invalid data structure received');
            }
            this.genes = data.genes;
            //console.log(this.genes);
            this.filteredGenes = [...this.genes];
        } catch (error) {
            console.error('Failed to load genes:', error);
            // Handle the error here, e.g. show an error message or set an error state
        }
    },
    filterGenes(query) {
      this.filteredGenes = this.genes.filter(gene =>
        gene.toLowerCase().includes(query.toLowerCase())
      );
    },
    selectItem(item) {
      this.searchQuery = item;
      this.showScroller = false;
    },
    handleBlur() {
      setTimeout(() => {
        if (!this.$refs.scroller.$el.contains(document.activeElement)) {
          this.showScroller = false;
        }
      }, 100);
    },
    //------------------------------------------------------
    /*
async searchgene() {
    //Clear previous points
    const chartElement = document.getElementById('umap-chart-gene');
    const traceCount = chartElement.data.length;
    Plotly.deleteTraces('umap-chart-gene', Array.from(Array(traceCount).keys()));
    //---------------
  const params = new URLSearchParams({
    id: this.$route.params.id,
    gene: this.searchQuery
  });
  try {
    const response = await fetch(`../php/scd_geneExpression.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    
    // Merge data
    const ncMap = data.data.reduce((acc, item) => {
      acc[item.i] = parseFloat(item.nc) || 0;
      return acc;
    }, {});
    
    // Merge arrays
    const mergedArray = this.umapData.map(item => {
      item.nc = ncMap[item.i] || 0;
      return item;
    });
    // Render in batches

    let batchSize = 2000; // Batch size for the first render
    let currentIndex = 0;
    
    const renderBatch = () => {
      const batch = mergedArray.slice(currentIndex, currentIndex + batchSize).map((point) => ({
        x: [point.u1],
        y: [point.u2],
        mode: 'markers',
        type: 'scattergl',
        name: "",
        marker: {
          color: this.getColor(point.nc),
          size: this.markerSize2,
        },
        text: `${point.i}<br>${point.nc}`, // Show cell_id info
      }));
    
      Plotly.addTraces('umap-chart-gene', batch);
    
      currentIndex += batchSize;
      if (currentIndex < mergedArray.length) {
        // Increase batchSize to speed up rendering of later batches
        batchSize = 20000; // Size of later batches
        setTimeout(renderBatch, 0); // Defer rendering the next batch
      }
    };
    
    renderBatch();

  } catch (error) {
    console.error('Failed to load genes:', error);
  }
},
*/

async searchgene() {

  // Request parameters
  const params = new URLSearchParams({
    studyid: this.$route.params.study,
    gene: this.searchQuery
  });

  try {
    const response = await fetch(`../php/scs_geneExpression.php?${params}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();

    // Merge data
    const ncMap = data.data.reduce((acc, item) => {
      acc[item.i] = parseFloat(item.nc) || 0;
      return acc;
    }, {});

    // Merge arrays
    const mergedArray = this.umapData.map(item => {
      item.nc = ncMap[item.i] || 0;
      return item;
    });

    // Create traces by cell type
    const categories = [...new Set(mergedArray.map(item => item.c))];
    const traces = categories.map(category => {
      const categoryPoints = mergedArray.filter(point => point.c === category);
      
       const colors = categoryPoints.map(point => this.getColor(point.nc));
      return {
        x: categoryPoints.map(point => point.u1),
        y: categoryPoints.map(point => point.u2),
        mode: 'markers',
        type: 'scattergl',
        name: category,
        marker: {
          color: colors, 
          size: this.markerSize2,
        },
        text: categoryPoints.map(point => `${point.i}<br>${point.nc}`), // Show cell_id and nc info
      };
    });

    // Draw the chart
    const halfViewportWidth = window.innerWidth * 0.48;
    Plotly.newPlot('umap-chart-gene', traces, {
                    showlegend: false,
                    width: halfViewportWidth,
                    height: halfViewportWidth,
    });
  } catch (error) {
    console.error('Failed to load genes:', error);
  }
},

    //------------------------------------------------------
    getColor(value) {
      if (value > 2) {
        return 'rgb(142, 45, 48)';
      } else if (value > 0) {
        return 'rgb(93, 116, 162)';
      } else {
        return 'rgba(128, 128, 128, 0.2)'; 
      }
    },
    getTooltipText(value) {
  return `Cell ID: ${value.i}<br>Normalized Count: ${value.nc}`;
    },
     //------------------------------------------------------
     //Differential expression analysis
     //------------------------------------------------------
    initDEG() {
      const params = new URLSearchParams({
        id: this.$route.params.id,
      });
      fetch(`../php/scd_getDEG.php?${params}`)
        .then((response) => response.json())
        .then((data) => {
        console.log(data);
          this.DEGdata = data.data; 
        })
        .catch((error) => {
          console.error("Failed to load DEGs:", error);
        });
    },
     prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
     
     //------------------------------------------------------
     
  },
};
</script>

<style scoped>
@import 'css/MainStyles.css';
@import 'css/SCDStyles.css';


 /* ----------------------------------------------------------- */
 /* DEG right-panel table, scoped to avoid affecting global styles */
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

 /* ----------------------------------------------------------- */
</style>