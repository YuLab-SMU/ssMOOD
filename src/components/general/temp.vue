
              <el-card class="kegg-card" shadow="never">
                <!-- 折叠面板头 -->
                <el-collapse v-model="isenrichmentExpanded1" @change="getKEGG" accordion>
                  <el-collapse-item :title="$t('scd42')" name="1">
                    <div class="search-container" style="margin-bottom: 12px;">
                      <el-input v-model="filterKEGG" :placeholder="$t('scd31')" clearable prefix-icon="el-icon-search"
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
                          <el-button size="mini" type="primary" @click="openKeggModal(row.g)">
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
                        <el-button type="text" size="small" @click="openLink(row.name, 'link1')">
                          UNIPROT🔗
                        </el-button>
                        <el-button type="text" size="small" @click="openLink(row.name, 'link2')">
                          GENECARDS🔗
                        </el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </el-dialog>
              </el-card>




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
    // 2. 折叠：仅清空
    KEGGdata.value = []
    loadingKEGG.value = false
  }
}


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

  const sortPaths = KEGGsortProp.value.split(',').map(s => s.trim()) // 支持多个排序字段（如 'go_a.go_b, go_c'）

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
  link.download = "ssMOOD-" + route.params.id + "-kegg.csv"; // 指定下载的文件名
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

