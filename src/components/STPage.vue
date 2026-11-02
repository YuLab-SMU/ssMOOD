<template>
  <div>
    <NavigationBar></NavigationBar>
    <main>
      <section class="page-section">
        <div class="main-container">
          <!--##################################################-->
          <!--Details container-->
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
                <h2>{{ $t('scd9') }}</h2>

                <p><span class="bold-black">{{ $t('scd10') }}</span>: {{
                  dataset.cells }}</p>
                <p><span class="bold-black">{{ $t('scd11') }}</span>: {{
                  dataset.clusters }}</p>
              </div>
              <div class="information-right">
                <h2>{{ $t('scd12') }}</h2>

                <p><span class="bold-black">{{ $t('scd13') }}</span>: {{ dataset.information.DatasetSource1.Title }}</p>
                <p><span class="bold-black">{{ $t('scd14') }}</span>: {{ dataset.information.DatasetSource1.Methodology
                  }}</p>
                <p><span class="bold-black">{{ $t('scd15') }}</span>: {{ dataset.information.DatasetSource1.Protocol }}
                </p>
                <p><span class="bold-black">{{ $t('scd16') }}</span>: {{ dataset.information.DatasetSource1.PublicDataID
                  }}</p>
                <p><span class="bold-black">{{ $t('scd18') }}</span>: <a
                    :href="'http://doi.org/' + dataset.information.DatasetSource1.DOI" target="_blank">{{
                      dataset.information.DatasetSource1.DOI }}</a>

                </p>
                <p><span class="bold-black">{{ $t('scd19') }}</span>: {{ dataset.information.DatasetSource1.Statement }}
                </p>
              </div>
            </div>
          </div>

          <!--##################################################-->
          <!--Cell type container-->
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
                  <el-input-number v-model="markerSize1" :min="1" :max="100" :step="1" size="small" controls-position=""
                    @change="updateUmap1" />
                </div>
                <!-- coord_chart container -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- Loading image -->
                  <ChartLoading v-if="coord_chartLoading" />
                  <div id="coord_chart"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: coord_chartLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>
              <div class="information-right">
                <h1>{{ $t('std19') }}</h1>
                <div class="gene-search-con">
                  <el-input v-model="searchQuery" :placeholder="$t('scd24')" @focus="showScroller = true"
                    @blur="handleBlur" class="search-gene-input" clearable size="">
                    <template #append>
                      <el-button @click="searchgene" type="primary">
                        {{ $t('scd21button') }}
                      </el-button>
                    </template>
                  </el-input>
                  <!--Virtual dropdown list-->
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
                <!-- Spatial gene expression plot container -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- Loading image -->
                  <ChartLoading v-if="coord_chartGeneLoading" />

                  <!-- Plotly chart container -->
                  <div id="coord_chart_gene"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: coord_chartGeneLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>
            </div>
            <div class="information-second">
              <!-- 🔔Custom legend; the built-in legend distorts the plot's aspect ratio -->
              <div class="legend-wrapper">
                <el-checkbox v-model="checkAllFlag" :indeterminate="isIndeterminate" @change="toggleAll"
                  class="select-all">
                  {{ $t('scd22-1') }}
                </el-checkbox>
                <el-checkbox-group v-model="visibleLabels" @change="onCheckboxChange" class="legend-group">
                  <el-checkbox v-for="label in global_clusterLabels" :key="label" :value="label" class="checkbox-item">
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
            <!-- Second column -->
            <div class="information-second">
              <div id="expressionHeatmap" style="width: auto; height: 100%;"></div>
            </div>
          </div>
          <!--##################################################-->
          <!--Differential expression analysis container-->
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
                        <el-option label="One Cell type vs Other Cell types" value="cellTypeSpecificGenes" />
                      </el-select>
                    </div>
                    <div class="cell-type">
                      <label>{{ $t('scd28') }}</label>
                      <el-select v-model="cellType" placeholder="" size="default">
                        <el-option v-for="type in cellTypes" :key="type" :label="type" :value="type" />
                      </el-select>
                    </div>
                    <!-- Labels -->
                    <label style="white-space: nowrap; font-weight: 600;">
                      {{ $t('scd29') }}
                    </label>
                    <div class="log2fc" style="display: flex; align-items: center; gap: 12px;">
                      <!-- Slider -->
                      <el-slider v-model="log2fc" :min="0" :max="10" :step="0.1" :format-tooltip="val => val.toFixed(1)"
                        show-tooltip tooltip-class="always-show-tooltip custom-tooltip" style="flex: 1;" />

                      <div style="
                        width: 48px;
                        text-align: right;
                        font-size: 14px;
                        font-weight: bold;
                        color: #666;
                      ">
                        {{ log2fc.toFixed(1) }}
                      </div>
                    </div>

                    <div class="adjusted-pvalue">
                      <label>{{ $t('scd30') }}</label>
                      <!-- Log-scale slider -->
                      <el-slider v-model="pValueSliderIndex" :aria-hidden="null" :min="0" :max="7" :marks="pValueMarks"
                        :step="1" :show-tooltip="false" class="mt-4" />

                    </div>
                    <div class="DEdirection" style="margin-top: 1rem;">
                      <label class="font-semibold text-gray-700 mr-4">{{ $t('scd30-1') }}</label>

                      <el-radio-group v-model="selectedDirection" size="small" class="custom-radio-group">
                        <el-radio-button value="all">All</el-radio-button>
                        <el-radio-button value="up">UP</el-radio-button>
                        <el-radio-button value="down">Down</el-radio-button>
                      </el-radio-group>
                      <el-tooltip :content="$t('scd30-help')" placement="top">
                        <el-icon class="help-icon">
                          <QuestionFilled />
                        </el-icon>
                      </el-tooltip>
                    </div>
                  </div>
                </div>

                <div class="information-right">

                  <div class="de-analysis  modern-ui">
                    <el-input v-model="filterDEGGenes" :placeholder="$t('scd31')" clearable size="default" />
                    <!-- Table -->
                    <div class="table-container">
                      <el-table :data="paginatedData" v-loading="loadingDEG" @sort-change="handleSortChange"
                        style="width: 100%;" :default-sort="{ prop: sortProp, order: sortOrder }">
                        <el-table-column prop="i" :label="$t('scd33')" sortable="custom">
                          <template #default="{ row }">{{ row.i }}</template>
                        </el-table-column>

                        <el-table-column prop="f" :label="$t('scd34')" sortable="custom" min-width="100">
                          <template #default="{ row }">{{ row.fc.toFixed(6) }}</template>
                        </el-table-column>

                        <el-table-column prop="t1" sortable="custom">
                          <template #header>
                            {{ $t('scd35') }}
                            <el-tooltip :content="$t('scd35-help')" placement="top">
                              <el-icon class="help-icon">
                                <QuestionFilled />
                              </el-icon>
                            </el-tooltip>
                          </template>
                          <template #default="{ row }">
                            {{ row.t1.toFixed(3) }}
                          </template>
                        </el-table-column>

                        <el-table-column prop="t2" sortable="custom">
                          <template #header>
                            {{ $t('scd36') }}
                            <el-tooltip :content="$t('scd36-help')" placement="top">
                              <el-icon class="help-icon">
                                <QuestionFilled />
                              </el-icon>
                            </el-tooltip>
                          </template>
                          <template #default="{ row }">
                            {{ row.t2.toFixed(3) }}
                          </template>
                        </el-table-column>

                        <el-table-column prop="a" :label="$t('scd37')" sortable="custom" min-width="100">
                          <template #default="{ row }">{{ row.a.toExponential(3) }}</template>
                        </el-table-column>
                      </el-table>
                      <div class="pagination">
                        <!-- Pagination -->
                        <div class="left-section">
                          <el-pagination layout="prev, pager, next" :current-page="currentPage" :page-size="pageSize"
                            :total="filteredData.length" @current-change="handlePageChange" size="small" background
                            class="page-pagination" />
                        </div>

                        <button @click="download" class="downloadButton">{{ $t('scd32') }}</button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>


              <!--##################################################-->
              <!--KEGG analysis-->
              <el-card class="kegg-card" shadow="never">
                <!-- Collapse panel header -->
                <el-collapse v-model="isenrichmentExpanded1" @change="getKEGG" accordion>
                  <el-collapse-item :title="$t('scd42')" name="1">
                    <el-text type="info">{{ $t('scd42-help') }}</el-text>
                    <div class="search-container" style="margin-bottom: 12px;">
                      <el-input v-model="filterKEGG" :placeholder="$t('scd53')" clearable prefix-icon="el-icon-search"
                        size="default" />
                    </div>

                    <el-table :data="KEGGpaginatedData" v-loading="loadingKEGG" stripe size="default"
                      style="width: 100%" :default-sort="{ prop: sortProp, order: sortOrder }"
                      @sort-change="handleKEGGSortChange">
                      <el-table-column prop="t" :label="$t('scd44')" sortable="custom" min-width="400" />
                      <el-table-column prop="p" :label="$t('scd45')" sortable="custom">
                        <template #default="{ row }">{{ row.p.toExponential(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="o" :label="$t('scd46')" sortable="custom">
                        <template #default="{ row }">{{ row.o.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="c" :label="$t('scd47')" sortable="custom">
                        <template #default="{ row }">{{ row.c.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column :label="$t('scd48')">
                        <template #default="{ row }">
                          <el-button size="small" type="primary" @click="openKeggModal(row.g)">
                            {{ $t('scd49') }}
                          </el-button>
                        </template>
                      </el-table-column>
                    </el-table>

                    <!-- Pagination -->
                    <div class="pagination"
                      style="margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                      <el-pagination background layout="prev, pager, next" :current-page="KEGGcurrentPage"
                        :page-size="pageSize" :total="KEGGfilteredData.length" @current-change="handleKEGGPageChange"
                        size="small" />
                      <el-button size="default" type="primary" @click="KEGGdownload" class="downloadButton">
                        {{ $t('scd32') }}
                      </el-button>
                    </div>
                  </el-collapse-item>
                </el-collapse>

                <!-- Modal -->
                <el-dialog v-model="isKeggModalOpen" width="50%" :title="$t('scd48')">
                  <el-table :data="selectedKeggGene" style="width: 100%" size="default">
                    <el-table-column :label="$t('scd50')">
                      <template #default="{ row }">
                        {{ row }}
                      </template>
                    </el-table-column>

                    <el-table-column :label="$t('scd51')">
                      <template #default="{ row }">
                        <el-button type="text" size="small" @click="openLink(row, 'link1')">
                          UNIPROT🔗
                        </el-button>
                        <el-button type="text" size="small" @click="openLink(row, 'link2')">
                          GENECARDS🔗
                        </el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </el-dialog>
              </el-card>

              <!--##################################################-->
              <!--GO_BP analysis-->
              <el-card class="gobp-card" shadow="never">
                <!-- Collapse panel header -->
                <el-collapse v-model="isenrichmentExpanded2" @change="getGO_BP" accordion>
                  <el-collapse-item :title="$t('scd52')" name="1">
                    <el-text type="info">{{ $t('scd42-help') }}</el-text>
                    <div class="search-container" style="margin-bottom: 12px;">
                      <el-input v-model="filterGOBP" :placeholder="$t('scd53')" clearable prefix-icon="el-icon-search"
                        size="default" />
                    </div>

                    <el-table :data="GOBPpaginatedData" v-loading="loadingGOBP" stripe size="default"
                      style="width: 100%" :default-sort="{ prop: handleGOBPSortChange, order: handleGOBPPageChange }"
                      @sort-change="handleGOBPSortChange">
                      <el-table-column prop="t" :label="$t('scd44')" sortable="custom" min-width="400" />
                      <el-table-column prop="p" :label="$t('scd45')" sortable="custom">
                        <template #default="{ row }">{{ row.p.toExponential(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="o" :label="$t('scd46')" sortable="custom">
                        <template #default="{ row }">{{ row.o.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="c" :label="$t('scd47')" sortable="custom">
                        <template #default="{ row }">{{ row.c.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column :label="$t('scd48')">
                        <template #default="{ row }">
                          <el-button size="small" type="primary" @click="openGOBPModal(row.g)">
                            {{ $t('scd49') }}
                          </el-button>
                        </template>
                      </el-table-column>
                    </el-table>

                    <!-- Pagination -->
                    <div class="pagination"
                      style="margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                      <el-pagination background layout="prev, pager, next" :current-page="GOBPcurrentPage"
                        :page-size="pageSize" :total="GOBPfilteredData.length" @current-change="handleGOBPPageChange"
                        size="small" />
                      <el-button size="default" type="primary" @click="GOBPdownload" class="downloadButton">
                        {{ $t('scd32') }}
                      </el-button>
                    </div>
                  </el-collapse-item>
                </el-collapse>

                <!-- Modal -->
                <el-dialog v-model="isGOBPModalOpen" width="50%" :title="$t('scd48')">
                  <el-table :data="selectedGOBPGene" style="width: 100%" size="default">
                    <el-table-column :label="$t('scd50')">
                      <template #default="{ row }">
                        {{ row }}
                      </template>
                    </el-table-column>

                    <el-table-column :label="$t('scd51')">
                      <template #default="{ row }">
                        <el-button type="text" size="small" @click="openLink(row, 'link1')">
                          UNIPROT🔗
                        </el-button>
                        <el-button type="text" size="small" @click="openLink(row, 'link2')">
                          GENECARDS🔗
                        </el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </el-dialog>
              </el-card>
              <!--##################################################-->
              <!--GO_MF analysis-->
              <el-card class="gomf-card" shadow="never">
                <!-- Collapse panel header -->
                <el-collapse v-model="isenrichmentExpanded3" @change="getGO_MF" accordion>
                  <el-collapse-item :title="$t('scd54')" name="1">
                    <el-text type="info">{{ $t('scd42-help') }}</el-text>
                    <div class="search-container" style="margin-bottom: 12px;">
                      <el-input v-model="filterGOMF" :placeholder="$t('scd53')" clearable prefix-icon="el-icon-search"
                        size="default" />
                    </div>

                    <el-table :data="GOMFpaginatedData" v-loading="loadingGOMF" stripe size="default"
                      style="width: 100%" :default-sort="{ prop: handleGOMFSortChange, order: handleGOMFPageChange }"
                      @sort-change="handleGOMFSortChange">
                      <el-table-column prop="t" :label="$t('scd44')" sortable="custom" min-width="400" />
                      <el-table-column prop="p" :label="$t('scd45')" sortable="custom">
                        <template #default="{ row }">{{ row.p.toExponential(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="o" :label="$t('scd46')" sortable="custom">
                        <template #default="{ row }">{{ row.o.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="c" :label="$t('scd47')" sortable="custom">
                        <template #default="{ row }">{{ row.c.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column :label="$t('scd48')">
                        <template #default="{ row }">
                          <el-button size="small" type="primary" @click="openGOMFModal(row.g)">
                            {{ $t('scd49') }}
                          </el-button>
                        </template>
                      </el-table-column>
                    </el-table>

                    <!-- Pagination -->
                    <div class="pagination"
                      style="margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                      <el-pagination background layout="prev, pager, next" :current-page="GOMFcurrentPage"
                        :page-size="pageSize" :total="GOMFfilteredData.length" @current-change="handleGOMFPageChange"
                        size="small" />
                      <el-button size="default" type="primary" @click="GOMFdownload" class="downloadButton">
                        {{ $t('scd32') }}
                      </el-button>
                    </div>
                  </el-collapse-item>
                </el-collapse>

                <!-- Modal -->
                <el-dialog v-model="isGOMFModalOpen" width="50%" :title="$t('scd48')">
                  <el-table :data="selectedGOMFGene" style="width: 100%" size="default">
                    <el-table-column :label="$t('scd50')">
                      <template #default="{ row }">
                        {{ row }}
                      </template>
                    </el-table-column>

                    <el-table-column :label="$t('scd51')">
                      <template #default="{ row }">
                        <el-button type="text" size="small" @click="openLink(row, 'link1')">
                          UNIPROT🔗
                        </el-button>
                        <el-button type="text" size="small" @click="openLink(row, 'link2')">
                          GENECARDS🔗
                        </el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </el-dialog>
              </el-card>
              <!--##################################################-->
              <!--GO_CC analysis-->
              <el-card class="gocc-card" shadow="never">
                <!-- Collapse panel header -->
                <el-collapse v-model="isenrichmentExpanded4" @change="getGO_CC" accordion>
                  <el-collapse-item :title="$t('scd55')" name="1">
                    <el-text type="info">{{ $t('scd42-help') }}</el-text>
                    <div class="search-container" style="margin-bottom: 12px;">
                      <el-input v-model="filterGOCC" :placeholder="$t('scd53')" clearable prefix-icon="el-icon-search"
                        size="default" />
                    </div>

                    <el-table :data="GOCCpaginatedData" v-loading="loadingGOCC" stripe size="default"
                      style="width: 100%" :default-sort="{ prop: handleGOCCSortChange, order: handleGOCCPageChange }"
                      @sort-change="handleGOCCSortChange">
                      <el-table-column prop="t" :label="$t('scd44')" sortable="custom" min-width="400" />
                      <el-table-column prop="p" :label="$t('scd45')" sortable="custom">
                        <template #default="{ row }">{{ row.p.toExponential(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="o" :label="$t('scd46')" sortable="custom">
                        <template #default="{ row }">{{ row.o.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column prop="c" :label="$t('scd47')" sortable="custom">
                        <template #default="{ row }">{{ row.c.toFixed(3) }}</template>
                      </el-table-column>
                      <el-table-column :label="$t('scd48')">
                        <template #default="{ row }">
                          <el-button size="small" type="primary" @click="openGOCCModal(row.g)">
                            {{ $t('scd49') }}
                          </el-button>
                        </template>
                      </el-table-column>
                    </el-table>

                    <!-- Pagination -->
                    <div class="pagination"
                      style="margin: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                      <el-pagination background layout="prev, pager, next" :current-page="GOCCcurrentPage"
                        :page-size="pageSize" :total="GOCCfilteredData.length" @current-change="handleGOCCPageChange"
                        size="small" />
                      <el-button size="default" type="primary" @click="GOCCdownload" class="downloadButton">
                        {{ $t('scd32') }}
                      </el-button>
                    </div>
                  </el-collapse-item>
                </el-collapse>

                <!-- Modal -->
                <el-dialog v-model="isGOCCModalOpen" width="50%" :title="$t('scd48')">
                  <el-table :data="selectedGOCCGene" style="width: 100%" size="default">
                    <el-table-column :label="$t('scd50')">
                      <template #default="{ row }">
                        {{ row }}
                      </template>
                    </el-table-column>

                    <el-table-column :label="$t('scd51')">
                      <template #default="{ row }">
                        <el-button type="text" size="small" @click="openLink(row, 'link1')">
                          UNIPROT🔗
                        </el-button>
                        <el-button type="text" size="small" @click="openLink(row, 'link2')">
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
    <back-to-top></back-to-top>
  </div>
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import VirtualListItem from './general/VirtualListItem.vue';
import VirtualList from 'vue3-virtual-scroll-list'
import pako from 'pako';
import { ref, onMounted, computed, watch,nextTick, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
//import debounce from 'lodash.debounce';
import { QuestionFilled } from '@element-plus/icons-vue'
//----------Below is the bare minimum needed for an ssmood page--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import ChartLoading from './general/ChartLoading.vue';
import config from '@/config';
//----------Above is the bare minimum needed for an ssmood page--------------
import colorMap from './color_map.js';
const route = useRoute();

//------------------------------------------------------
//Load dataset details
//------------------------------------------------------
const dataset = ref({
  dataset_id: '',
  species: '',
  area: '',
  condition: '',
  sex: '',
  age: '',
  cells: '',
  clusters: '',
  information: {
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
    id: route.params.id // Get route params via route.params
  });

  fetch(`${config.apiUrl}std_getSTDatasetDetail.php?${params}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json(); // Parse directly as JSON instead of text
    })
    .then(dataArray => {
      if (dataArray && dataArray.length > 0 && dataArray[0].information) {
        const parsedData = JSON.parse(dataArray[0].information);
        dataset.value = { ...dataArray[0], information: parsedData }; // Update data through the ref's value
      }

      //console.log(dataset.value); // Log the updated dataset
    })
    .catch(error => {
      console.error('Error fetching dataset details:', error);
    });
});

//------------------------------------------------------
//Load spatial coordinate plot
//------------------------------------------------------
const coordinate_data = ref([]);
const markerSize1 = ref(2);
const global_clusterLabels = ref([])
const visibleLabels = ref([])
const colors = ref({})
const isIndeterminate = ref(false)
const checkAllFlag = ref(true)

const coord_chartLoading = ref(true)

// Flag to prevent zoom-sync loops
const isSyncingZoom = ref(false)
const isSelectZoom = ref(false)
// Prevent duplicate updates on double-click reset
const isDoubleClickReset = ref(false)


// Auto-select visible cell types based on the zoom range
const selectVisibleCellTypes = (xRange, yRange) => {
  if (!xRange || !yRange || coordinate_data.value.length === 0) return;
  isSelectZoom.value = true;
  const [xMin, xMax] = xRange;
  const [yMin, yMax] = yRange;
  
  // Find cell types within the current view range
  const visibleTypes = new Set();
  
  coordinate_data.value.forEach(cell => {
    const x = parseFloat(cell.x);
    const y = parseFloat(cell.y);
    
    if (x >= xMin && x <= xMax && y >= yMin && y <= yMax) {
      visibleTypes.add(cell.c);
    }
  });
  
  // Update visible cell types - use nextTick to ensure the DOM has updated
  if (visibleTypes.size > 0) {
    nextTick(() => {
      visibleLabels.value = Array.from(visibleTypes);
      // Reset the flag after the DOM updates
      setTimeout(() => {
        isSelectZoom.value = false;
      }, 50);
    });
  } else {
    // If no visible types were found, reset the flag immediately
    isSelectZoom.value = false;
  }
};

// Select all / deselect all
const toggleAll = () => {
  if (checkAllFlag.value) {
    visibleLabels.value = [...global_clusterLabels.value]
  } else {
    visibleLabels.value = []
  }
}

// When a single checkbox changes
const onCheckboxChange = () => {
  // Only handle plot updates here; selection state is managed by the watcher
  // Only update the chart when the selection was not triggered by zooming
  if(!isSelectZoom.value) {
    updatePlot()
    updateGenePlot()
  } // Skip if the change was triggered by zooming
  
}

// Track changes to visibleLabels and update the select-all and indeterminate states
watch(visibleLabels, (val) => {
  const total = global_clusterLabels.value.length
  const checkedCount = val.length

  checkAllFlag.value = checkedCount === total
  isIndeterminate.value = checkedCount > 0 && checkedCount < total
  
  // Only update the chart when not triggered by zooming or a double-click reset
  if (!isSelectZoom.value && !isDoubleClickReset.value) {
    updatePlot()
    updateGenePlot()
  }
}, { flush: 'post' })

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
      yaxis: { title: 'UMAP2', scaleanchor: "x" },
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
    yaxis: { title: 'coords_Y', scaleanchor: "x" },
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)',
    showlegend: false,
  };

  Plotly.react('coord_chart', traces, layout)
}



onMounted(() => {
  coord_chartLoading.value = true;
  const params = new URLSearchParams({
    id: route.params.id
  });

  fetch(`${config.apiUrl}std_getCoordinate.php?${params}`)
    .then(response => response.arrayBuffer())
    .then(arrayBuffer => {
      const compressed = new Uint8Array(arrayBuffer);
      const decompressed = pako.ungzip(compressed); // Decompress with pako
      const jsonString = new TextDecoder('utf-8').decode(decompressed);
      const data = JSON.parse(jsonString); // Parse the JSON string

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
        acc[label] = colorMap[label] || 'rgb(128,128,128)'; // Fall back to the default color #000 if no matching color is found
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
        yaxis: { title: 'coords_Y', scaleanchor: "x" },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
      };

      Plotly.newPlot('coord_chart', traces, layout);
      
      // Listen for zoom events to sync the left plot's zoom to the right plot
      document.getElementById('coord_chart').on('plotly_relayout', function(eventdata) {
        if (eventdata && eventdata['xaxis.range[0]'] !== undefined && !isSyncingZoom.value) {
          isSyncingZoom.value = true;

          // Get the zoom range and select visible cell types
          const xRange = [eventdata['xaxis.range[0]'], eventdata['xaxis.range[1]']];
          const yRange = [eventdata['yaxis.range[0]'], eventdata['yaxis.range[1]']];
          selectVisibleCellTypes(xRange, yRange);

          // Sync the zoom range to the right plot
          if (isSearchgene.value === true) {
            const rightChart = document.getElementById('coord_chart_gene');
            if (rightChart) {
              Plotly.relayout('coord_chart_gene', {
                'xaxis.range[0]': eventdata['xaxis.range[0]'],
                'xaxis.range[1]': eventdata['xaxis.range[1]'],
                'yaxis.range[0]': eventdata['yaxis.range[0]'],
                'yaxis.range[1]': eventdata['yaxis.range[1]']
              });
            }
          }
          
          setTimeout(() => {
            isSyncingZoom.value = false;
          }, 100);
        }
      });
      
      // Listen for double-click reset events
      document.getElementById('coord_chart').on('plotly_doubleclick', function() {
        // Prevent repeated triggering
        if (isDoubleClickReset.value) return;
        isDoubleClickReset.value = true;
        
        // Reset the view directly without updating the checkboxes
        Plotly.relayout('coord_chart', {
          'xaxis.autorange': true,
          'yaxis.autorange': true
        });
        
        // Reset checkbox state
        visibleLabels.value = [...global_clusterLabels.value];
        
        // Reset the right plot's view
        if (isSearchgene.value === true) {
          const rightChart = document.getElementById('coord_chart_gene');
          if (rightChart) {
            Plotly.relayout('coord_chart_gene', {
              'xaxis.autorange': true,
              'yaxis.autorange': true
            });
          }
        }
        
        // Reset the debounce flag after a delay
        setTimeout(() => {
          isDoubleClickReset.value = false;
        }, 300);
      });
      
      coord_chartLoading.value = false;
    })
    .catch(error => console.error('Error fetching UMAP data:', error));
});


// Method to update the UMAP chart
const updateUmap1 = () => {
  Plotly.restyle('coord_chart', 'marker.size', [markerSize1.value]);
  // // Also update the right plot's point size
  // if (isSearchgene.value === true) {
  //   markerSize2.value = markerSize1.value;
  //   Plotly.restyle('coord_chart_gene', 'marker.size', [markerSize2.value]);
  // }
};

//------------------------------------------------------
//Cell type table
//------------------------------------------------------
onMounted(async () => {
  const params = new URLSearchParams({
    id: route.params.id
  });
  // Plot the distribution across datasets
  fetch(config.apiUrl + `std_getNumberOfCluster.php?${params}`)
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
        title: 'Cell Counts per Cluster',
        xaxis: {
          title: '',
          tickangle: 45, // Rotate labels 45 degrees
          tickmode: 'linear', // Keep labels evenly spaced
          tickfont: { size: 7 } // Adjust font size
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
    .catch(error => console.error('Error fetching data from scd_getNumberOfCluster.php:', error));
});

//------------------------------------------------------
//Gene search box
//------------------------------------------------------


const genes = ref([]);
//const filteredGenes = ref([]);
const searchQuery = ref('');
const showScroller = ref(false);
const markerSize2 = ref(2); // Default point size


const filteredGenes = computed(() => {
  let data = genes.value || [];
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    //console.log(query)
    data = data.filter(gene => (gene.content || '').toLowerCase().includes(query));
  }
  return data;
});


//Load genes
onMounted(async () => {
  const params = new URLSearchParams({
    id: route.params.id
  });
  try {
    const response = await fetch(config.apiUrl + `general_getgene.php?${params}`);
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
      id: index, // Use the array index as the id
      content: gene
    }));

  } catch (error) {
    console.error('Failed to load genes:', error);
  }
});


//-------------------------------------------------------------
//Handler after an option is selected
//⚠️The vue3-virtual-scroll-list component's built-in handling is buggy, so a global event bus is used here
//-------------------------------------------------------------
import { inject } from 'vue';
const eventBus = inject('eventBus')
onMounted(() => {
  eventBus.on('select-item', handleSelectItem);
});


const handleSelectItem = (item) => {
  searchQuery.value = item;
  showScroller.value = false;
};

// Handle blur (focus lost)
const handleBlur = () => {
  setTimeout(() => {
    if (!document.activeElement.closest('.scroller')) { // Scroll container class name 'scroller'
      showScroller.value = false;
    }
  }, 100);
};


//-------------------------------------------------------------
//Colors
//-------------------------------------------------------------
const maxNc = ref(0)

const getColor = (value) => {
  if (value === 0) return 'rgba(222, 222, 222,0.7)';
  const t = Math.min(value / maxNc.value, 1);
  const h = 0;                    // Red
  const s = 60 + 40 * t;          // 60% → 100%
  const l = 80 - 35 * t;          // 80% → 45%
  return `hsl(${h}, ${s}%, ${l}%)`;
};
const isSearchgene = ref(false);
const mergedGeneArray = ref([]);
const coord_chartGeneLoading = ref(false);

const searchgene = async () => {
  if (coord_chartLoading.value === false) {
    coord_chartGeneLoading.value = true;
    // Request parameters
    const params = new URLSearchParams({
      id: route.params.id,
      gene: searchQuery.value
    });
    let jsonData = [];
    try {
      try {
        const response = await fetch(config.apiUrl + `std_getGeneExpression_bin.php?${params}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const compressed = new Uint8Array(await response.arrayBuffer());
        const decompressed = pako.ungzip(compressed); // Decompress with pako

        const data = new TextDecoder('utf-8').decode(decompressed);

        jsonData = JSON.parse(data);
      } catch (error) {
        console.error('Error fetching or parsing data:', error);
        // On any error, jsonData stays as the default empty array
      }

      isSearchgene.value = true;
      // Merge data
      const ncMap = jsonData.reduce((acc, item) => {
        acc[item.i] = parseFloat(item.nc) || 0;
        return acc;
      }, {});

      // Merge arrays
      const mergedArray = coordinate_data.value.map(item => {
        item.nc = ncMap[item.i] || 0;
        return item;
      });

      mergedGeneArray.value = mergedArray;


      // Cell type info
      const categories = [...new Set(mergedArray.map(item => item.c))];

      categories.sort();

      // Safely compute the maximum
      maxNc.value = jsonData.reduce(
        (max, item) => (item.nc > max ? item.nc : max),
        -Infinity
      );
      //-----------Build heatmap data------------------------

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

          // Normalize
          const normLogNC = (logNC - minLogNC) / (maxLogNC - minLogNC);
          const expressionIndex = Math.floor(normLogNC * (numBins - 1));

          // Bounds guard
          const safeIndex = Math.min(Math.max(expressionIndex, 0), numBins - 1);
          if (
            safeIndex >= 0 && safeIndex < heatmapData.length &&
            categoryIndex >= 0 && categoryIndex < categories.length
          ) {
            heatmapData[safeIndex][categoryIndex]++;
          }
        }
      });

      //---------------------------------------
      // Create traces by cell type
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
          text: categoryPoints.map(point => `${point.i}<br>${point.nc}`), // Show cell_id and nc info
        };
      });
      const genelayout = {
        showlegend: false,
        xaxis: {
        },
        yaxis: {
          scaleanchor: "x"
        },
        paper_bgcolor: 'rgba(0,0,0,0)', // Optional: make the background transparent
        plot_bgcolor: 'rgba(0,0,0,0)',
      };
      Plotly.newPlot('coord_chart_gene', traces, genelayout);
      
      // Listen for zoom events to sync the right plot's zoom to the left plot
      document.getElementById('coord_chart_gene').on('plotly_relayout', function(eventdata) {
        if (eventdata && eventdata['xaxis.range[0]'] !== undefined && !isSyncingZoom.value) {
          isSyncingZoom.value = true;


          // Get the zoom range and select visible cell types
          const xRange = [eventdata['xaxis.range[0]'], eventdata['xaxis.range[1]']];
          const yRange = [eventdata['yaxis.range[0]'], eventdata['yaxis.range[1]']];
          selectVisibleCellTypes(xRange, yRange);

          // Sync the zoom range to the left plot
          Plotly.relayout('coord_chart', {
            'xaxis.range[0]': eventdata['xaxis.range[0]'],
            'xaxis.range[1]': eventdata['xaxis.range[1]'],
            'yaxis.range[0]': eventdata['yaxis.range[0]'],
            'yaxis.range[1]': eventdata['yaxis.range[1]']
          });
          setTimeout(() => {
            isSyncingZoom.value = false;
          }, 100);
        }
      });
      
      // Listen for double-click reset events
      document.getElementById('coord_chart_gene').on('plotly_doubleclick', function() {
        // Prevent repeated triggering
        if (isDoubleClickReset.value) return;
        isDoubleClickReset.value = true;
        
        // Reset the view directly without updating the checkboxes
        Plotly.relayout('coord_chart_gene', {
          'xaxis.autorange': true,
          'yaxis.autorange': true
        });
        
        // Reset checkbox state
        visibleLabels.value = [...global_clusterLabels.value];
        
        // Reset the left plot's view
        const leftChart = document.getElementById('coord_chart');
        if (leftChart) {
          Plotly.relayout('coord_chart', {
            'xaxis.autorange': true,
            'yaxis.autorange': true
          });
        }
        
        // Reset the debounce flag after a delay
        setTimeout(() => {
          isDoubleClickReset.value = false;
        }, 300);
      });
      coord_chartLoading.value = false;


      //-----------Draw heatmap------------------------
      //Heatmap of cell counts per cell type across expression-level bins
      const layout = {
        autosize: true,
        title: 'Heatmap of Cell Counts Across Expression Levels and Clusters',
        xaxis: {
          title: '',
          showgrid: false,
          tickangle: 45, // Rotate labels 45 degrees
          tickmode: 'linear', // Keep labels evenly spaced
          tickfont: { size: 6 }, // Adjust font size
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
            return Math.pow(10, logVal).toFixed(2)  // Show raw values
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
        zmin: 0,  // Set the heatmap color scale minimum
        zmax: Math.max(...heatmapData.flat()),
      };

      Plotly.newPlot('expressionHeatmap', [trace], layout);
      coord_chartGeneLoading.value = false;
      
      // Reset the left plot's view to its original size
      const leftChart = document.getElementById('coord_chart');
      if (leftChart) {
        Plotly.relayout('coord_chart', {
          'xaxis.autorange': true,
          'yaxis.autorange': true
        });
      }
      
      // Reset checkboxes to all selected
      visibleLabels.value = [...global_clusterLabels.value];
    } catch (error) {
      console.error('Failed to load genes:', error);
    }
  }


};

// Update the point size of UMAP plot 2
const updateUmap2 = () => {
  if (isSearchgene.value === true) {
    Plotly.restyle('coord_chart_gene', 'marker.size', [markerSize2.value]);
    // Also update the left plot's point size
    markerSize1.value = markerSize2.value;
    Plotly.restyle('coord_chart', 'marker.size', [markerSize1.value]);
  }
};


//###################################//
//Differential expression analysis
//###################################//

const group = ref('cellTypeSpecificGenes');
const cellTypes = ref([]);
const cellType = ref('');
const log2fc = ref(0);

const pageSize = ref(10);
//const pvalue = ref(5);
const selectedDirection = ref('all');

const DEGdata = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(10);

const filterDEGGenes = ref('');

const pValueSliderIndex = ref(5)  // Default 0.05
// Define the slider values and labels
const logPValues = [1e-6, 1e-5, 1e-4, 1e-3, 0.01, 0.05, 0.1, 0.5]
const pValueMarks = {
  0: '10⁻⁶',
  1: '10⁻⁵',
  2: '10⁻⁴',
  3: '10⁻³',
  4: '0.01',
  5: '0.05',
  6: '0.1',
  7: '0.5',
}

const formattedPValue = computed(() => {
  const val = logPValues[pValueSliderIndex.value]
  return val < 0.001 ? `10^${Math.log10(val).toFixed(0)}` : val.toFixed(3)
})

const loadingDEG = ref(true);
//----------------------------------
//Variables for the gene enrichment analysis section
const KEGGdata = ref([]);
const isenrichmentExpanded1 = ref("");
const isenrichmentExpanded2 = ref("");
const isenrichmentExpanded3 = ref("");
const isenrichmentExpanded4 = ref("");

//----------------------------------


//------------------------------------------------------//
//Load DEG data for the first cell type by default
//------------------------------------------------------//
onMounted(() => {
  const params = new URLSearchParams({
    id: route.params.id,
  });
  fetch(config.apiUrl + `general_DEG_CellType.php?${params}`)
    .then((response) => response.json())
    .then((data) => {
      cellTypes.value = data;
      cellType.value = cellTypes.value[0];
      loadingDEG.value = false;
    })
    .catch((error) => {
      console.error("Failed to load DEGs:", error);
    });
});

//------------------------------------------------------//
//Detect when the user changes the cell type
//------------------------------------------------------//
watch(cellType, async (newcellType) => {
  loadingDEG.value = true;
  //Fetch DEG data
  const params = new URLSearchParams({
    id: route.params.id,
    cluster: newcellType
  });
  fetch(config.apiUrl + `general_getDEG_ByCluster.php?${params}`)
    .then((response) => response.arrayBuffer())
    .then((arrayBuffer) => {
      const compressed = new Uint8Array(arrayBuffer);
      const decompressed = pako.ungzip(compressed); // Decompress with pako
      const jsonString = new TextDecoder('utf-8').decode(decompressed);
      DEGdata.value = JSON.parse(jsonString);
      currentPage.value = 1;//Go back to the first page
      loadingDEG.value = false;
    })
    .catch((error) => {
      console.error("Failed to load DEGs:", error);
    });
});



//------------------------------------------------------
//Pagination for differential expression analysis
//------------------------------------------------------
const filteredData = computed(() => {
  return DEGdata.value.filter(item => {
    currentPage.value = 1;
    const logFoldChange = parseFloat(item.fc);
    const adjustedPvalue = parseFloat(item.a);
    // Lowercase both filterDEGGenes.value and item.i, then check for a match
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
      lowerCaseItemI.includes(lowerCaseFilter) &&
      directionFilter;
  });
});
function handlePageChange(page) {
  currentPage.value = page
  // You can re-slice the data here to render the current page
}
//------------------------------------------------------
//Data changed; enrichment data must be reloaded, so collapse the gene enrichment panel
//------------------------------------------------------
watch(filteredData, () => {
  KEGGdata.value = [];
  isenrichmentExpanded1.value = "";
  GOBPdata.value = [];
  isenrichmentExpanded2.value = "";
  GOMFdata.value = [];
  isenrichmentExpanded3.value = "";
  GOCCdata.value = [];
  isenrichmentExpanded4.value = "";
});

/*
const totalPages = computed(() => {
  // Total pages are computed from the filtered dataset
  return Math.ceil(filteredData.value.length / itemsPerPage.value);
});
*/
const paginatedData = computed(() => {
  // Apply pagination to the filtered dataset
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
const headers = ['gene name', 'Adjusted p-value', 'Log fold-change', 'Pct.1', 'Pct.2'];
const download = () => {
  // Build a 2D array where each element is a table row
  const dataForTable = filteredData.value.map((item) => {
    return Object.values(item);
  });

  // Build the CSV string
  const csvContent = [headers.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  // Create a download link
  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.id + "-deg.csv"; // Set the download filename
  link.click();
};

//------------------------------------------------------
//Sort DEG data
//------------------------------------------------------

const sortProp = ref('') // Current sort field
const sortOrder = ref('') // asc / desc

// Sorting computed property
const sortedData = computed(() => {
  if (!sortProp.value || !sortOrder.value) {
    return filteredData.value
  }
  // Sorting logic
  return [...filteredData.value].sort((a, b) => {
    const prop = sortProp.value
    let res = 0
    if (prop === 'i') {
      // String sort
      res = a.i.localeCompare(b.i)
    } else {
      // Numeric sort
      res = a[prop] - b[prop]
    }
    return sortOrder.value === 'ascending' ? res : -res
  })
})

// Watch for sort changes
function handleSortChange({ prop, order }) {
  currentPage.value = 1;
  sortProp.value = prop
  sortOrder.value = order
}

//##########################################################################################//
//KEGG analysis
//##########################################################################################//


//const isenrichmentExpanded1 = ref(false); defined in the DEG section
//const KEGGdata = ref([]); defined in the DEG section

const KEGGcurrentPage = ref(1);
const KEGGitemsPerPage = ref(10);

const filterKEGG = ref('');

const KeggGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});

const loadingKEGG = ref(true);

//------------------------------------------------------//
//Request KEGG data from the server
//------------------------------------------------------//
const getKEGG = (activeNames) => {
  // activeNames is the array of names of the currently expanded panels
  const isOpen = activeNames.includes('1')

  if (isOpen) {
    // 1. Expanded: start loading
    loadingKEGG.value = true
    KEGGdata.value = []          // Clear old data first (avoid flashing stale values)

    const genesJson = JSON.stringify(KeggGenes.value)
    const params = new FormData()
    params.append('genes', genesJson)
    if (dataset.species === "mouse") { params.append('gene_sets', 'KEGG_2019_Mouse.gmt') }
    else { params.append('gene_sets', 'KEGG_2019_Human.gmt') }

    params.append('id', route.params.id)

    fetch(config.apiUrl + 'enrichment.php', {
      method: 'POST',
      body: params
    })
      .then(res => {
        if (!res.ok) throw new Error('Network error')
        return res.json()
      })
      .then(data => {
        KEGGdata.value = data
      })
      .catch(err => {
        console.error('Failed to load KEGG:', err)
      })
      .finally(() => {
        loadingKEGG.value = false
      })
  } else {
    // 2. Collapsed: just clear
    KEGGdata.value = []
    loadingKEGG.value = false
  }
}




//------------------------------------------------------//
//Filter pathways by name
//------------------------------------------------------//
const KEGGfilteredData = computed(() => {
  return KEGGdata.value.filter(item => {
    KEGGcurrentPage.value = 1;
    // Lowercase both filterDEGGenes.value and item.i, then check for a match
    const lowerCaseFilter = filterKEGG.value.toLowerCase();
    const lowerCaseItemI = item.t.toLowerCase();


    return lowerCaseItemI.includes(lowerCaseFilter);
  });
});
//console.log(KEGGfilteredData.value);


const KEGGpaginatedData = computed(() => {
  // Apply pagination to the filtered dataset
  const start = (KEGGcurrentPage.value - 1) * KEGGitemsPerPage.value;
  const end = start + KEGGitemsPerPage.value;
  return KEGGsortedData.value.slice(start, end);
});


function handleKEGGPageChange(page) {
  KEGGcurrentPage.value = page;
}

const KEGGsortProp = ref('')
const KEGGsortOrder = ref('')

// Sorting computed property
const KEGGsortedData = computed(() => {
  if (!KEGGsortProp.value || !KEGGsortOrder.value) {
    return KEGGfilteredData.value
  }

  const sortPaths = KEGGsortProp.value.split(',').map(s => s.trim())

  return [...KEGGfilteredData.value].sort((a, b) => {
    for (const path of sortPaths) {
      const valA = getNestedValue(a, path)
      const valB = getNestedValue(b, path)

      let res = 0
      if (typeof valA === 'string' && typeof valB === 'string') {
        res = valA.localeCompare(valB)
      } else {
        res = (valA ?? 0) - (valB ?? 0)
      }

      if (res !== 0) {
        return KEGGsortOrder.value === 'ascending' ? res : -res
      }
      // If the current field is equal, compare the next field
    }
    return 0
  })
})

// Helper: access a nested object property via a string path
function getNestedValue(obj, path) {
  return path.split('.').reduce((acc, key) => acc?.[key], obj)
}


// Watch for sort changes
function handleKEGGSortChange({ prop, order }) {
  KEGGcurrentPage.value = 1;
  KEGGsortProp.value = prop
  KEGGsortOrder.value = order
}
//------------------------------------------------------//
//Save KEGG data as CSV
//------------------------------------------------------//
const KEGGheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const KEGGdownload = () => {
  // Build a 2D array where each element is a table row
  const dataForTable = KEGGfilteredData.value.map((item) => {
    return Object.values(item);
  });

  // Build the CSV string
  const csvContent = [KEGGheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  // Create a download link
  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.id + "-kegg.csv"; // Set the download filename
  link.click();
};


//------------------------------------------------------//
//Gene list modal
//------------------------------------------------------//

// Modal state
const isKeggModalOpen = ref(false);
const selectedKeggGene = ref([]);

// Open the modal
const openKeggModal = (gene) => {
  selectedKeggGene.value = gene.split(';');
  isKeggModalOpen.value = true;
};

/*
// Close the modal
const closeKeggModal = () => {
  isKeggModalOpen.value = false;
};
*/


//###############################################################################################//
// GO_BP analysis
//###############################################################################################//

const GOBPcurrentPage = ref(1);
const GOitemsPerPage = ref(10);
const filterGOBP = ref('');
const loadingGOBP = ref(false);
const GOBPdata = ref([]);

const GOGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});

//------------------------------------------------------//
// Request GO BP data from the server
//------------------------------------------------------//
const getGO_BP = (activeNames) => {
  const isOpen = activeNames.includes('1');
  if (!isOpen) {
    GOBPdata.value = [];
    loadingGOBP.value = false;
    return;
  }

  loadingGOBP.value = true;
  GOBPdata.value = [];

  const genesJson = JSON.stringify(GOGenes.value);
  const params = new FormData();
  params.append('genes', genesJson);
  params.append('gene_sets', 'GO_BP_2018.gmt');

  params.append('id', route.params.id);

  fetch(config.apiUrl + 'enrichment.php', {
    method: 'POST',
    body: params
  })
    .then(res => {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(data => {
      GOBPdata.value = data;
    })
    .catch(err => {
      console.error('Failed to load GO_BP:', err);
    })
    .finally(() => {
      loadingGOBP.value = false;
    });
};

//------------------------------------------------------//
// Search filtering
//------------------------------------------------------//
const GOBPfilteredData = computed(() => {
  //GOBPcurrentPage.value = 1;
  const keyword = filterGOBP.value.toLowerCase();
  return GOBPdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// Sorting
//------------------------------------------------------//
const GOBPsortProp = ref('');
const GOBPsortOrder = ref('');

const GOBPsortedData = computed(() => {
  if (!GOBPsortProp.value || !GOBPsortOrder.value) return GOBPfilteredData.value;

  const sortPaths = GOBPsortProp.value.split(',').map(s => s.trim());
  return [...GOBPfilteredData.value].sort((a, b) => {
    for (const path of sortPaths) {
      const valA = getNestedValue(a, path);
      const valB = getNestedValue(b, path);
      let res = 0;
      if (typeof valA === 'string' && typeof valB === 'string') {
        res = valA.localeCompare(valB);
      } else {
        res = (valA ?? 0) - (valB ?? 0);
      }
      if (res !== 0) {
        return GOBPsortOrder.value === 'ascending' ? res : -res;
      }
    }
    return 0;
  });
});

function handleGOBPSortChange({ prop, order }) {
  GOBPcurrentPage.value = 1;
  GOBPsortProp.value = prop;
  GOBPsortOrder.value = order;
}

//------------------------------------------------------//
// Pagination
//------------------------------------------------------//
const GOBPpaginatedData = computed(() => {
  const start = (GOBPcurrentPage.value - 1) * GOitemsPerPage.value;
  const end = start + GOitemsPerPage.value;
  return GOBPsortedData.value.slice(start, end);
});

function handleGOBPPageChange(page) {
  GOBPcurrentPage.value = page;
}

//------------------------------------------------------//
// Download CSV
//------------------------------------------------------//
const GOBPheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOBPdownload = () => {
  const dataForTable = GOBPfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOBPheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.id + "-goBP.csv";
  link.click();
};

//------------------------------------------------------//
// Modal - show gene list
//------------------------------------------------------//
const isGOBPModalOpen = ref(false);
const selectedGOBPGene = ref([]);

const openGOBPModal = (gene) => {
  selectedGOBPGene.value = gene.split(';');
  isGOBPModalOpen.value = true;
};



//###############################################################################################//
// GO_MF analysis
//###############################################################################################//

const GOMFcurrentPage = ref(1);
const filterGOMF = ref('');
const loadingGOMF = ref(false);
const GOMFdata = ref([]);

/*No need to redefine
const GOGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});
*/
//------------------------------------------------------//
// Request GO MF data from the server
//------------------------------------------------------//
const getGO_MF = (activeNames) => {
  const isOpen = activeNames.includes('1');
  if (!isOpen) {
    GOMFdata.value = [];
    loadingGOMF.value = false;
    return;
  }

  loadingGOMF.value = true;
  GOMFdata.value = [];

  const genesJson = JSON.stringify(GOGenes.value);
  const params = new FormData();
  params.append('genes', genesJson);
  params.append('gene_sets', 'GO_MF_2018.gmt');

  params.append('id', route.params.id);

  fetch(config.apiUrl + 'enrichment.php', {
    method: 'POST',
    body: params
  })
    .then(res => {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(data => {
      GOMFdata.value = data;
    })
    .catch(err => {
      console.error('Failed to load GO_MF:', err);
    })
    .finally(() => {
      loadingGOMF.value = false;
    });
};

//------------------------------------------------------//
// Search filtering
//------------------------------------------------------//
const GOMFfilteredData = computed(() => {
  //GOMFcurrentPage.value = 1;
  const keyword = filterGOMF.value.toLowerCase();
  return GOMFdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// Sorting
//------------------------------------------------------//
const GOMFsortProp = ref('');
const GOMFsortOrder = ref('');

const GOMFsortedData = computed(() => {
  if (!GOMFsortProp.value || !GOMFsortOrder.value) return GOMFfilteredData.value;

  const sortPaths = GOMFsortProp.value.split(',').map(s => s.trim());
  return [...GOMFfilteredData.value].sort((a, b) => {
    for (const path of sortPaths) {
      const valA = getNestedValue(a, path);
      const valB = getNestedValue(b, path);
      let res = 0;
      if (typeof valA === 'string' && typeof valB === 'string') {
        res = valA.localeCompare(valB);
      } else {
        res = (valA ?? 0) - (valB ?? 0);
      }
      if (res !== 0) {
        return GOMFsortOrder.value === 'ascending' ? res : -res;
      }
    }
    return 0;
  });
});

function handleGOMFSortChange({ prop, order }) {
  GOMFcurrentPage.value = 1;
  GOMFsortProp.value = prop;
  GOMFsortOrder.value = order;
}

//------------------------------------------------------//
// Pagination
//------------------------------------------------------//
const GOMFpaginatedData = computed(() => {
  const start = (GOMFcurrentPage.value - 1) * GOitemsPerPage.value;
  const end = start + GOitemsPerPage.value;
  return GOMFsortedData.value.slice(start, end);
});

function handleGOMFPageChange(page) {
  GOMFcurrentPage.value = page;
}

//------------------------------------------------------//
// Download CSV
//------------------------------------------------------//
const GOMFheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOMFdownload = () => {
  const dataForTable = GOMFfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOMFheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.id + "-goMF.csv";
  link.click();
};

//------------------------------------------------------//
// Modal - show gene list
//------------------------------------------------------//
const isGOMFModalOpen = ref(false);
const selectedGOMFGene = ref([]);

const openGOMFModal = (gene) => {
  selectedGOMFGene.value = gene.split(';');
  isGOMFModalOpen.value = true;
};

//###############################################################################################//
// GO_CC analysis
//###############################################################################################//

const GOCCcurrentPage = ref(1);
const filterGOCC = ref('');
const loadingGOCC = ref(false);
const GOCCdata = ref([]);

/*No need to redefine; the gene list is shared
const GOGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});
*/
//------------------------------------------------------//
// Request GO CC data from the server
//------------------------------------------------------//
const getGO_CC = (activeNames) => {
  const isOpen = activeNames.includes('1');
  if (!isOpen) {
    GOCCdata.value = [];
    loadingGOCC.value = false;
    return;
  }

  loadingGOCC.value = true;
  GOCCdata.value = [];

  const genesJson = JSON.stringify(GOGenes.value);
  const params = new FormData();
  params.append('genes', genesJson);
  params.append('gene_sets', 'GO_CC_2018.gmt');

  params.append('id', route.params.id);

  fetch(config.apiUrl + 'enrichment.php', {
    method: 'POST',
    body: params
  })
    .then(res => {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(data => {
      GOCCdata.value = data;
    })
    .catch(err => {
      console.error('Failed to load GO_CC:', err);
    })
    .finally(() => {
      loadingGOCC.value = false;
    });
};

//------------------------------------------------------//
// Search filtering
//------------------------------------------------------//
const GOCCfilteredData = computed(() => {
  //GOCCcurrentPage.value = 1;
  const keyword = filterGOCC.value.toLowerCase();
  return GOCCdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// Sorting
//------------------------------------------------------//
const GOCCsortProp = ref('');
const GOCCsortOrder = ref('');

const GOCCsortedData = computed(() => {
  if (!GOCCsortProp.value || !GOCCsortOrder.value) return GOCCfilteredData.value;

  const sortPaths = GOCCsortProp.value.split(',').map(s => s.trim());
  return [...GOCCfilteredData.value].sort((a, b) => {
    for (const path of sortPaths) {
      const valA = getNestedValue(a, path);
      const valB = getNestedValue(b, path);
      let res = 0;
      if (typeof valA === 'string' && typeof valB === 'string') {
        res = valA.localeCompare(valB);
      } else {
        res = (valA ?? 0) - (valB ?? 0);
      }
      if (res !== 0) {
        return GOCCsortOrder.value === 'ascending' ? res : -res;
      }
    }
    return 0;
  });
});

function handleGOCCSortChange({ prop, order }) {
  GOCCcurrentPage.value = 1;
  GOCCsortProp.value = prop;
  GOCCsortOrder.value = order;
}

//------------------------------------------------------//
// Pagination
//------------------------------------------------------//
const GOCCpaginatedData = computed(() => {
  const start = (GOCCcurrentPage.value - 1) * GOitemsPerPage.value;
  const end = start + GOitemsPerPage.value;
  return GOCCsortedData.value.slice(start, end);
});

function handleGOCCPageChange(page) {
  GOCCcurrentPage.value = page;
}

//------------------------------------------------------//
// Download CSV
//------------------------------------------------------//
const GOCCheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOCCdownload = () => {
  const dataForTable = GOCCfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOCCheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.id + "-goCC.csv";
  link.click();
};

//------------------------------------------------------//
// Modal - show gene list
//------------------------------------------------------//
const isGOCCModalOpen = ref(false);
const selectedGOCCGene = ref([]);

const openGOCCModal = (gene) => {
  selectedGOCCGene.value = gene.split(';');
  isGOCCModalOpen.value = true;
};





//-------Shared by all enrichment analyses------------//
const openLink = (gene, linkType) => {
  let url;
  if (linkType === 'link1') {
    url = `https://www.uniprot.org/uniprotkb/?query=${gene}`;
  } else if (linkType === 'link2') {
    url = `https://www.genecards.org/cgi-bin/carddisp.pl?gene=${gene}`;
  }
  window.open(url, '_blank');
};


//------Detect page width changes------------//
onMounted(() => {
  window.addEventListener('resize', resizeMyChart);
});
const resizeMyChart = () => {
  Plotly.Plots.resize('myClusterChart');
  Plotly.Plots.resize('coord_chart');
  if (isSearchgene.value === true) {
    Plotly.Plots.resize('coord_chart_gene');
    Plotly.Plots.resize('expressionHeatmap');
  }

};

onUnmounted(() => {
  // Clean up the event bus
  if (eventBus) {
    eventBus.off('select-item', handleSelectItem);
  }
  
  // Remove the window resize listener
  window.removeEventListener('resize', resizeMyChart);
  
  // Remove Plotly event listeners
  const leftChart = document.getElementById('coord_chart');
  const rightChart = document.getElementById('coord_chart_gene');
  
  if (leftChart) {
    leftChart.removeAllListeners('plotly_relayout');
    leftChart.removeAllListeners('plotly_doubleclick');
  }
  
  if (rightChart) {
    rightChart.removeAllListeners('plotly_relayout');
    rightChart.removeAllListeners('plotly_doubleclick');
  }
  
  // Clear data
  genes.value = [];
  coordinate_data.value = [];
  mergedGeneArray.value = [];
  KEGGdata.value = [];
  GOBPdata.value = [];
  GOMFdata.value = [];
  GOCCdata.value = [];
  
  // Reset flags
  isSelectZoom.value = false;
  isDoubleClickReset.value = false;
  isSyncingZoom.value = false;
  
  // Remove the ResizeObserver error handler
  window.removeEventListener('error', function(event) {
    if (event.error && event.error.message && event.error.message.includes('ResizeObserver')) {
      event.preventDefault();
      console.warn('ResizeObserver error suppressed:', event.error.message);
      return false;
    }
  });
});
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

.st-coord-content {
  display: flex;
  flex-direction: column;
  /* Stack child elements vertically */
  flex: 1;
  gap: 30px;
}

/* ----------------------------------------------------------- */
</style>