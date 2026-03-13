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

                <p><span class="bold-black">{{ $t('scd3') }}</span>: {{ dataset.study_id }}</p>
                <p><span class="bold-black">{{ $t('scd4') }}</span>: {{ dataset.species }}</p>
                <p><span class="bold-black">{{ $t('scd5') }}</span>: {{ dataset.area }}</p>
                <p><span class="bold-black">{{ $t('scd6') }}</span>: {{ dataset.conditions }}</p>
                <p><span class="bold-black">{{ $t('scd7') }}</span>: {{ dataset.sex }}</p>
                <p><span class="bold-black">{{ $t('scd8') }}</span>: {{ dataset.age }}</p>
                <h2>{{ $t('scd9') }}</h2>

                <p><span class="bold-black">{{ $t('scd10') }}</span>: {{
                  dataset.cells }}</p>
                <p><span class="bold-black">{{ $t('scd11') }}</span>: {{
                  dataset.clusters }}</p>


                <h1>{{ $t('scd2-1') }}</h1>
                <p>
                  <span v-for="(ds, index) in datasetList" :key="ds" class="dataset-link" @click="goToDataset(ds)">
                    {{ ds }}<span v-if="index < datasetList.length - 1">, </span>
                  </span>
                </p>


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
                <h2 v-if="dataset.information.DatasetSource2.Title != ''">{{ $t('scd12-1') }}</h2>

                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd13')
                }}</span>: {{ dataset.information.DatasetSource2.Title }}</p>
                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd14')
                }}</span>: {{ dataset.information.DatasetSource2.Methodology
                    }}</p>
                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd15')
                }}</span>: {{ dataset.information.DatasetSource2.Protocol }}
                </p>
                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd16')
                }}</span>: {{ dataset.information.DatasetSource2.PublicDataID
                    }}</p>
                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd18')
                }}</span>: <a :href="'http://doi.org/' + dataset.information.DatasetSource2.DOI" target="_blank">{{
                      dataset.information.DatasetSource2.DOI }}</a>

                </p>
                <p v-if="dataset.information.DatasetSource2.Title != ''"><span class="bold-black">{{ $t('scd19')
                }}</span>: {{ dataset.information.DatasetSource2.Statement }}
                </p>


                <h2 v-if="dataset.information.DatasetSource3.Title != ''">{{ $t('scd12-2') }}</h2>

                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd13')
                }}</span>: {{ dataset.information.DatasetSource3.Title }}</p>
                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd14')
                }}</span>: {{ dataset.information.DatasetSource3.Methodology
                    }}</p>
                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd15')
                }}</span>: {{ dataset.information.DatasetSource3.Protocol }}
                </p>
                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd16')
                }}</span>: {{ dataset.information.DatasetSource3.PublicDataID
                    }}</p>
                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd18')
                }}</span>: <a :href="'http://doi.org/' + dataset.information.DatasetSource3.DOI" target="_blank">{{
                      dataset.information.DatasetSource3.DOI }}</a>

                </p>
                <p v-if="dataset.information.DatasetSource3.Title != ''"><span class="bold-black">{{ $t('scd19')
                }}</span>: {{ dataset.information.DatasetSource3.Statement }}
                </p>
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
            <div class="information-content">

              <div class="information-left">

                <h1>{{ $t('scd21') }}</h1>

                <div class="marker-size-control">
                  <span class="label">{{ $t('scd22') }}:</span>
                  <el-input-number v-model="markerSize1" :min="1" :max="100" :step="1" size="small" controls-position=""
                    @change="updateUmap1" />
                </div>

                <!-- UMAP图的容器 -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- 加载图 -->
                  <img v-if="umapLoading" src="/loading.gif" alt="Loading"
                    style="position: absolute;inset: 0;margin: auto;width: 80%;height: 80%;object-fit: contain;z-index: 1;" />

                  <!-- Plotly 图表容器 -->
                  <div id="umap-plot"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: umapLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>

              <div class="information-right">
                <h1>{{ $t('scd23') }}</h1>
                <div class="gene-search-con">
                  <el-input v-model="searchQuery" :placeholder="$t('scd24')" @focus="showScroller = true"
                    @blur="handleBlur" class="search-gene-input" clearable size="default">
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


                <!-- UMAP基因表达量图的容器 -->
                <div style="position: relative; width: 100%; aspect-ratio: 1 / 1;">
                  <!-- 加载图 -->
                  <img v-if="umapGeneLoading" src="/loading.gif" alt="Loading"
                    style="position: absolute;inset: 0;margin: auto;width: 80%;height: 80%;object-fit: contain;z-index: 1;" />

                  <!-- Plotly 图表容器 -->
                  <div id="umap-chart-gene"
                    :style="{ width: '100%', aspectRatio: '1 / 1', visibility: umapGeneLoading ? 'hidden' : 'visible' }">
                  </div>
                </div>
              </div>

            </div>
            <div class="information-second">
              <!-- 🔔自定义图例，官方图例会影响图的比例 -->
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
              <div id="expressionHeatmap" style="width: auto; height: auto;"></div>

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
                        <el-option label="One Cell type vs Other Cell types" value="cellTypeSpecificGenes" />
                      </el-select>
                    </div>
                    <div class="cell-type">
                      <label>{{ $t('scd28') }}</label>
                      <el-select v-model="cellType" placeholder="" size="default">
                        <el-option v-for="type in cellTypes" :key="type" :label="type" :value="type" />
                      </el-select>
                    </div>
                    <!-- 标签 -->
                    <label style="white-space: nowrap; font-weight: 600;">
                      {{ $t('scd29') }}
                    </label>
                    <div class="log2fc" style="display: flex; align-items: center; gap: 12px;">
                      <!-- 滑动条 -->
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
                      <!-- 对数刻度滑动条 -->
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
                    <!-- 表格 -->
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
                        <!-- 翻页 -->
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
              <!--KEGG分析-->
              <el-card class="kegg-card" shadow="never">
                <!-- 折叠面板头 -->
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

                    <!-- 分页 -->
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

                <!-- 模态框 -->
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
              <!--GO_BP分析-->
              <el-card class="gobp-card" shadow="never">
                <!-- 折叠面板头 -->
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

                    <!-- 分页 -->
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

                <!-- 模态框 -->
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
              <!--GO_MF分析-->
              <el-card class="gomf-card" shadow="never">
                <!-- 折叠面板头 -->
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

                    <!-- 分页 -->
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

                <!-- 模态框 -->
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
              <!--GO_CC分析-->
              <el-card class="gocc-card" shadow="never">
                <!-- 折叠面板头 -->
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

                    <!-- 分页 -->
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

                <!-- 模态框 -->
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
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
//import VueVirtualScrollGrid from 'vue-virtual-scroll-grid';
//import debounce from 'lodash.debounce';
//----------以下为一个ssmood页面需要的最基础的东西--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------
import colorMap from './color_map.js';
import _merge from "lodash/merge";
import { QuestionFilled } from '@element-plus/icons-vue'
const route = useRoute();
const router = useRouter();

//###################################//
//加载数据集详细信息
//###################################//
const dataset = ref({
  study_id: '',
  species: '',
  area: '',
  condition: '',
  sex: '',
  age: '',
  clusters: '',
  cells: '',
  datasets: '',
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
    DatasetSource2: {
      Title: "",
      Methodology: "",
      Protocol: "",
      PublicDataID: "",
      Pubmed: null,
      DOI: "",
      Statement: ""
    },
    DatasetSource3: {
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
// 点击跳转函数
function goToDataset(id) {
  router.push(`/browse/SingleCell/${id}`);
}
const datasetList = ref([])
onMounted(() => {
  console.log(route.params.study);
  const params = new URLSearchParams({
    id: route.params.study
  });

  fetch(config.apiUrl + `study_getDatasetDetail.php?${params}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data && data.length > 0 && data[0].information) {
        const raw = { ...dataset.value, ...data[0] };

        // 处理 information 字段，如果是字符串先解析
        if (data[0].information) {
          try {
            raw.information = JSON.parse(data[0].information);
          } catch (e) {
            raw.information = {};
          }
        }
        dataset.value = _merge({}, dataset.value, raw);
        datasetList.value = dataset.value.datasets.split(',');
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
const global_clusterLabels = ref([])
const visibleLabels = ref([])
const colors = ref({})
const isIndeterminate = ref(false)
const checkAllFlag = ref(true)

const umapLoading = ref(true)

// 防止缩放同步循环的标志位
const isSyncingZoom = ref(false)
const isSelectZoom = ref(false)

// 全选/全不选
const toggleAll = () => {
  if (checkAllFlag.value) {
    visibleLabels.value = [...global_clusterLabels.value]
  } else {
    visibleLabels.value = []
  }
}

// 根据缩放范围自动选择可见的细胞类型
const selectVisibleCellTypes = (xRange, yRange) => {
  if (!xRange || !yRange || umapData.value.length === 0) return;
  isSelectZoom.value = true;
  const [xMin, xMax] = xRange;
  const [yMin, yMax] = yRange;

  // 找出在当前视图范围内的细胞类型
  const visibleTypes = new Set();

  umapData.value.forEach(cell => {
    const x = parseFloat(cell.u1);
    const y = parseFloat(cell.u2);

    if (x >= xMin && x <= xMax && y >= yMin && y <= yMax) {
      visibleTypes.add(cell.c);
    }
  });

  // 更新可见的细胞类型 - 使用 nextTick 确保 DOM 更新完成
  if (visibleTypes.size > 0) {
    nextTick(() => {
      visibleLabels.value = Array.from(visibleTypes);
      // 在 DOM 更新后重置标志位
      setTimeout(() => {
        isSelectZoom.value = false;
      }, 50);
    });
  } else {
    // 如果没有找到可见类型，立即重置标志位
    isSelectZoom.value = false;
  }
};

// 单个复选框变动时
const onCheckboxChange = () => {
  // 这里只处理图更新，选中状态由 watch 管
  // 只有在非缩放选择的情况下才更新图表
  if (!isSelectZoom.value) {
    updatePlot()
    updateGenePlot()
  }
}

// 自动追踪 visibleLabels 的变化，更新全选与半选中状态
watch(visibleLabels, (val) => {
  const total = global_clusterLabels.value.length
  const checkedCount = val.length

  checkAllFlag.value = checkedCount === total
  isIndeterminate.value = checkedCount > 0 && checkedCount < total

  // 只有在非缩放选择的情况下才更新图表
  if (!isSelectZoom.value) {
    updatePlot()
    updateGenePlot()
  }
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
        x: categoryPoints.map(point => point.u1),
        y: categoryPoints.map(point => point.u2),
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

    Plotly.react('umap-chart-gene', traces, genelayout);
  }
}

const updatePlot = () => {
  const umap1 = umapData.value.map(d => parseFloat(d.u1));
  const umap2 = umapData.value.map(d => parseFloat(d.u2));
  const cellIds = umapData.value.map(d => d.i);
  const clusterLabelsData = umapData.value.map(d => d.c);

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
    responsive: true,
    showlegend: false,
    xaxis: { title: 'UMAP1' },
    yaxis: { title: 'UMAP2' },
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)'
  }

  Plotly.react('umap-plot', traces, layout)
}

onMounted(() => {
  umapLoading.value = true
  const params = new URLSearchParams({
    id: route.params.study
  });
  fetch(config.apiUrl + `ssc_getumapdata.php?${params}`)
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
      global_clusterLabels.value = [...clusterLabels];
      visibleLabels.value = [...clusterLabels];
      //console.log(clusterLabels);
      const umap1 = umapData.value.map(d => parseFloat(d.u1));
      const umap2 = umapData.value.map(d => parseFloat(d.u2));
      const cellIds = umapData.value.map(d => d.i);
      const clusterLabelsData = umapData.value.map(d => d.c);


      colors.value = clusterLabels.reduce((acc, label) => {
        acc[label] = colorMap[label] || 'rgb(128,128,128)'; // 如果没有找到对应的颜色，则使用默认颜色 #000
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
            size: markerSize1.value,
            color: colors.value[label]
          }
        };
      });


      const layout = {
        title: '',
        responsive: true,
        showlegend: false,
        autosize: true, // 自动适配容器大小
        xaxis: {
          title: 'UMAP1',
        },
        yaxis: {
          title: 'UMAP2',
        },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',

      };


      Plotly.newPlot('umap-plot', traces, layout);

      // 添加缩放事件监听，让左边图的缩放同步到右边图
      document.getElementById('umap-plot').on('plotly_relayout', function(eventdata) {
        if (eventdata && eventdata['xaxis.range[0]'] !== undefined && !isSyncingZoom.value) {
          isSyncingZoom.value = true;

          // 获取缩放范围并选择可见的细胞类型
          const xRange = [eventdata['xaxis.range[0]'], eventdata['xaxis.range[1]']];
          const yRange = [eventdata['yaxis.range[0]'], eventdata['yaxis.range[1]']];
          selectVisibleCellTypes(xRange, yRange);

          // 同步缩放范围到右边图
          if (isSearchgene.value === true) {
            Plotly.relayout('umap-chart-gene', {
              'xaxis.range[0]': eventdata['xaxis.range[0]'],
              'xaxis.range[1]': eventdata['xaxis.range[1]'],
              'yaxis.range[0]': eventdata['yaxis.range[0]'],
              'yaxis.range[1]': eventdata['yaxis.range[1]']
            });
          }

          setTimeout(() => {
            isSyncingZoom.value = false;
          }, 100);
        }
      });


      // 添加双击重置事件监听，让左边图的双击同步到右边图
      document.getElementById('umap-plot').on('plotly_doubleclick', function() {
        // 重置为全选状态
        visibleLabels.value = [...global_clusterLabels.value];

        // 重置右边图的视图
        if (isSearchgene.value === true) {
          Plotly.relayout('umap-chart-gene', {
            'xaxis.autorange': true,
            'yaxis.autorange': true
          });
        }
      });

      umapLoading.value = false;

    })
    .catch(error => console.error('Error fetching UMAP data:', error));
});


//更新点大小
const markerSize1 = ref(3); // 默认点大小


// 更新 UMAP 图1 的点大小
const updateUmap1 = () => {
  Plotly.restyle('umap-plot', 'marker.size', [markerSize1.value]);
};

//###################################//
//分类表
//###################################//
onMounted(async () => {
  const params = new URLSearchParams({
    id: route.params.study
  });
  // 绘制各数据集分布
  fetch(config.apiUrl + `ssc_getNumberOfCluster.php?${params}`)
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
        title: 'Cell Counts per Cluster',
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



//###################################//
//基因搜索框
//###################################//


const genes = ref([]);
const searchQuery = ref('');
const showScroller = ref(false);
const markerSize2 = ref(3); // 默认点大小


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
    id: route.params.study
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
  if (value === 0) return 'rgba(222, 222, 222, 0.7)';
  const t = Math.min(value / maxNc.value, 1);
  const h = 0;                    // 红色
  const s = 60 + 40 * t;          // 60% → 100%
  const l = 80 - 35 * t;          // 80% → 45%
  return `hsl(${h}, ${s}%, ${l}%)`;
};

//-------------------------------------------------------------
//获取基因表达量
//-------------------------------------------------------------
const isSearchgene = ref(false);
const mergedGeneArray = ref([]);

const umapGeneLoading = ref(false)

const searchgene = async () => {
  if (umapLoading.value === false) {
    umapGeneLoading.value = true

    // 请求参数
    const params = new URLSearchParams({
      id: route.params.study,
      gene: searchQuery.value
    });
    let expressionData = [];
    try {
      try {
        const response = await fetch(config.apiUrl + `ssc_getGeneExpression_bin.php?${params}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const arrayBuffer = await response.arrayBuffer();
        const dataView = new DataView(arrayBuffer);
        let offset = 0; // 当前解析位置


        // 解析每个数据块
        while (offset < arrayBuffer.byteLength) {
          // 读取数据块长度（4字节无符号整数）
          const length = dataView.getUint32(offset, false);
          offset += 4; // 移动到数据块内容

          // 读取数据块内容
          const binData = new Uint8Array(arrayBuffer, offset, length);
          offset += length; // 移动到下一个数据块

          // 解压数据块
          const decompressed = pako.ungzip(binData);

          // 解码为字符串
          const text = new TextDecoder('utf-8').decode(decompressed);


          // 将解析后的数据添加到数组
          const jsonData = JSON.parse(text);
          for (const key in jsonData) {
            expressionData[key] = jsonData[key];
          }
        }
      } catch (error) {
        console.error('Error fetching or parsing data:', error);
        // 如果发生任何错误，jsonData保持为默认的空数组
      }
      //console.log(expressionData);
      isSearchgene.value = true;
      // 合并数据
      const ncMap = expressionData.reduce((acc, item) => {
        acc[item.i] = parseFloat(item.nc) || 0;
        return acc;
      }, {});

      // 合并数组
      const mergedArray = umapData.value.map(item => {
        item.nc = ncMap[item.i] || 0;
        return item;
      });
      mergedGeneArray.value = mergedArray;
      //console.log(mergedGeneArray.value);
      // 分类信息
      const categories = [...new Set(mergedArray.map(item => item.c))];
      categories.sort();

      // 安全计算最大值
      maxNc.value = mergedArray.reduce(
        (max, item) => (item.nc > max ? item.nc : max),
        -Infinity
      );
      //-----------创建热图信息------------------------
      /*
      const numCategories = categories.length;
  
      const heatmapData = Array.from({ length: 11 }, () =>
        Array.from({ length: numCategories }, () => 0)
      );
      */
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
          const expressionIndex = Math.floor(normLogNC * (numBins - 1));  // ✅ 修复这里

          // 边界保护
          const safeIndex = Math.min(Math.max(expressionIndex, 0), numBins - 1);

          if (
            safeIndex >= 0 && safeIndex < heatmapData.length &&
            categoryIndex >= 0 && categoryIndex < categories.length
          ) {
            heatmapData[safeIndex][categoryIndex]++;
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
      const genelayout = {
        showlegend: false,
        autosize: true, // 自动适配容器大小
        xaxis: {
          title: 'UMAP1',
        },
        yaxis: {
          title: 'UMAP2',
        },
      };
      Plotly.newPlot('umap-chart-gene', traces, genelayout);

      // 重置左边图为初始视图,并重置勾选框为全选状态
      visibleLabels.value = [...global_clusterLabels.value];
      Plotly.relayout('umap-plot', {
        'xaxis.autorange': true,
        'yaxis.autorange': true
      });

      // 添加缩放事件监听，让右边图的缩放同步到左边图
      document.getElementById('umap-chart-gene').on('plotly_relayout', function(eventdata) {
        if (eventdata && eventdata['xaxis.range[0]'] !== undefined && !isSyncingZoom.value) {
          isSyncingZoom.value = true;

          // 获取缩放范围并选择可见的细胞类型
          const xRange = [eventdata['xaxis.range[0]'], eventdata['xaxis.range[1]']];
          const yRange = [eventdata['yaxis.range[0]'], eventdata['yaxis.range[1]']];
          selectVisibleCellTypes(xRange, yRange);

          // 同步缩放范围到左边图
          Plotly.relayout('umap-plot', {
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

      // 添加双击重置事件监听，让右边图的双击同步到左边图
      document.getElementById('umap-chart-gene').on('plotly_doubleclick', function() {
        // 重置为全选状态
        visibleLabels.value = [...global_clusterLabels.value];

        // 重置左边图的视图
        Plotly.relayout('umap-plot', {
          'xaxis.autorange': true,
          'yaxis.autorange': true
        });
      });

      umapGeneLoading.value = false;

      //-----------绘制热图------------------------
      //各类细胞在不同表达量区间的细胞数量热图
      const layout = {
        autosize: true,
        title: 'Heatmap of Cell Counts Across Expression Levels and Clusters',
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

    } catch (error) {
      console.error('Failed to load genes:', error);
    }
  }


};

// 更新 UMAP 图2 的点大小
const updateUmap2 = () => {
  if (isSearchgene.value === true) {
    Plotly.restyle('umap-chart-gene', 'marker.size', [markerSize2.value]);
  }
};


//###################################//
//差异表达分析
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

const pValueSliderIndex = ref(5)  // 默认 0.05
// 定义 slider 的值及 label
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
//基因富集分析部分的变量
const KEGGdata = ref([]);
const isenrichmentExpanded1 = ref("");
const isenrichmentExpanded2 = ref("");
const isenrichmentExpanded3 = ref("");
const isenrichmentExpanded4 = ref("");

//----------------------------------


//------------------------------------------------------//
//默认加载第一个细胞类型的差异数据
//------------------------------------------------------//
onMounted(() => {
  const params = new URLSearchParams({
    id: route.params.study,
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
//检测用户更换细胞类型
//------------------------------------------------------//
watch(cellType, async (newcellType) => {
  loadingDEG.value = true;
  //获取差异数据
  const params = new URLSearchParams({
    id: route.params.study,
    cluster: newcellType
  });
  fetch(config.apiUrl + `general_getDEG_ByCluster.php?${params}`)
    .then((response) => response.arrayBuffer())
    .then((arrayBuffer) => {
      //console.log(data);
      const compressed = new Uint8Array(arrayBuffer);
      const decompressed = pako.ungzip(compressed); // 使用 pako 解压
      const jsonString = new TextDecoder('utf-8').decode(decompressed);
      DEGdata.value = JSON.parse(jsonString);
      currentPage.value = 1;//回到第一页
      loadingDEG.value = false;
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
    const logFoldChange = parseFloat(item.fc);
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
      lowerCaseItemI.includes(lowerCaseFilter) &&
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
  link.download = "ssMOOD-" + route.params.study + "-deg.csv"; // 指定下载的文件名
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

//##########################################################################################//
//KEGG分析
//##########################################################################################//


//const isenrichmentExpanded1 = ref(false);定义在差异部分
//const KEGGdata = ref([]);定义在差异部分

const KEGGcurrentPage = ref(1);
const KEGGitemsPerPage = ref(10);

const filterKEGG = ref('');

const KeggGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});

const loadingKEGG = ref(true);

//------------------------------------------------------//
//向服务器请求kegg数据
//------------------------------------------------------//
const getKEGG = (activeNames) => {
  // activeNames 是当前展开面板的 name 数组
  const isOpen = activeNames.includes('1')

  if (isOpen) {
    // 1. 展开：开始加载
    loadingKEGG.value = true
    KEGGdata.value = []          // 先清空旧数据（防止闪旧值）

    const genesJson = JSON.stringify(KeggGenes.value)
    const params = new FormData()
    params.append('genes', genesJson)
    if (dataset.value.species === "mouse") { params.append('gene_sets', 'KEGG_2019_Mouse.gmt') }
    else { params.append('gene_sets', 'KEGG_2019_Human.gmt') }

    params.append('id', route.params.study)

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
    // 2. 折叠：仅清空
    KEGGdata.value = []
    loadingKEGG.value = false
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
//console.log(KEGGfilteredData.value);


const KEGGpaginatedData = computed(() => {
  // 分页应用于筛选后的数据集
  const start = (KEGGcurrentPage.value - 1) * KEGGitemsPerPage.value;
  const end = start + KEGGitemsPerPage.value;
  return KEGGsortedData.value.slice(start, end);
});


function handleKEGGPageChange(page) {
  KEGGcurrentPage.value = page;
}

const KEGGsortProp = ref('')
const KEGGsortOrder = ref('')

// 排序计算属性
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
      // 如果当前字段相等，继续比较下一个字段
    }
    return 0
  })
})

// 辅助函数：通过字符串路径访问嵌套对象属性
function getNestedValue(obj, path) {
  return path.split('.').reduce((acc, key) => acc?.[key], obj)
}


// 监听排序变化
function handleKEGGSortChange({ prop, order }) {
  KEGGcurrentPage.value = 1;
  KEGGsortProp.value = prop
  KEGGsortOrder.value = order
}
//------------------------------------------------------//
//保存kegg数据为CSV
//------------------------------------------------------//
const KEGGheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
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
  link.download = "ssMOOD-" + route.params.study + "-kegg.csv"; // 指定下载的文件名
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


//###############################################################################################//
// GO_BP 分析
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
// 向服务器请求 GO BP 数据
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

  params.append('id', route.params.study);

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
// 搜索过滤
//------------------------------------------------------//
const GOBPfilteredData = computed(() => {
  //GOBPcurrentPage.value = 1;
  const keyword = filterGOBP.value.toLowerCase();
  return GOBPdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// 排序处理
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
// 分页
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
// 下载 CSV
//------------------------------------------------------//
const GOBPheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOBPdownload = () => {
  const dataForTable = GOBPfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOBPheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.study + "-goBP.csv";
  link.click();
};

//------------------------------------------------------//
// 模态窗 - 显示基因列表
//------------------------------------------------------//
const isGOBPModalOpen = ref(false);
const selectedGOBPGene = ref([]);

const openGOBPModal = (gene) => {
  selectedGOBPGene.value = gene.split(';');
  isGOBPModalOpen.value = true;
};



//###############################################################################################//
// GO_MF 分析
//###############################################################################################//

const GOMFcurrentPage = ref(1);
const filterGOMF = ref('');
const loadingGOMF = ref(false);
const GOMFdata = ref([]);

/*无需重复定义
const GOGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});
*/
//------------------------------------------------------//
// 向服务器请求 GO MF 数据
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

  params.append('id', route.params.study);

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
// 搜索过滤
//------------------------------------------------------//
const GOMFfilteredData = computed(() => {
  //GOMFcurrentPage.value = 1;
  const keyword = filterGOMF.value.toLowerCase();
  return GOMFdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// 排序处理
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
// 分页
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
// 下载 CSV
//------------------------------------------------------//
const GOMFheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOMFdownload = () => {
  const dataForTable = GOMFfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOMFheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.study + "-goMF.csv";
  link.click();
};

//------------------------------------------------------//
// 模态窗 - 显示基因列表
//------------------------------------------------------//
const isGOMFModalOpen = ref(false);
const selectedGOMFGene = ref([]);

const openGOMFModal = (gene) => {
  selectedGOMFGene.value = gene.split(';');
  isGOMFModalOpen.value = true;
};

//###############################################################################################//
// GO_CC 分析
//###############################################################################################//

const GOCCcurrentPage = ref(1);
const filterGOCC = ref('');
const loadingGOCC = ref(false);
const GOCCdata = ref([]);

/*无需重复定义，基因列表共用
const GOGenes = computed(() => {
  return filteredData.value.map(item => item.i);
});
*/
//------------------------------------------------------//
// 向服务器请求 GO CC 数据
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

  params.append('id', route.params.study);

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
// 搜索过滤
//------------------------------------------------------//
const GOCCfilteredData = computed(() => {
  //GOCCcurrentPage.value = 1;
  const keyword = filterGOCC.value.toLowerCase();
  return GOCCdata.value.filter(item => item.t.toLowerCase().includes(keyword));
});

//------------------------------------------------------//
// 排序处理
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
// 分页
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
// 下载 CSV
//------------------------------------------------------//
const GOCCheaders = ['Term', 'Adjusted p-value', 'Odds Ratio', 'Combined Score', 'Genes'];
const GOCCdownload = () => {
  const dataForTable = GOCCfilteredData.value.map(item => Object.values(item));
  const csvContent = [GOCCheaders.join(",")].concat(dataForTable.map(e => e.join(","))).join("\n");

  const link = document.createElement("a");
  link.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent);
  link.target = "_blank";
  link.download = "ssMOOD-" + route.params.study + "-goCC.csv";
  link.click();
};

//------------------------------------------------------//
// 模态窗 - 显示基因列表
//------------------------------------------------------//
const isGOCCModalOpen = ref(false);
const selectedGOCCGene = ref([]);

const openGOCCModal = (gene) => {
  selectedGOCCGene.value = gene.split(';');
  isGOCCModalOpen.value = true;
};





//-------所有富集分析共用------------//
const openLink = (gene, linkType) => {
  let url;
  if (linkType === 'link1') {
    url = `https://www.uniprot.org/uniprotkb/?query=${gene}`;
  } else if (linkType === 'link2') {
    url = `https://www.genecards.org/cgi-bin/carddisp.pl?gene=${gene}`;
  }
  window.open(url, '_blank');
};


//------检测页面的宽度变化------------//
onMounted(() => {
  window.addEventListener('resize', resizeMyChart);
});
const resizeMyChart = () => {
  Plotly.Plots.resize('myClusterChart');
  Plotly.Plots.resize('umap-plot');
  if (isSearchgene.value === true) {
    Plotly.Plots.resize('umap-chart-gene');
    Plotly.Plots.resize('expressionHeatmap');
  }

};

import { onUnmounted } from 'vue';
onUnmounted(() => {
  window.removeEventListener('resize', resizeMyChart);
  genes.value = [];
  umapData.value = [];
  mergedGeneArray.value = [];
  KEGGdata.value = [];
  GOBPdata.value = [];
  GOMFdata.value = [];
  GOCCdata.value = [];
});

</script>

<style scoped>
@import 'css/MainStyles.css';
@import 'css/SCDStyles.css';

/* ----------------------------------------------------------- */
.scroller-wrapper {
  height: 400px;
}
</style>