<template>
    <div>
        <NavigationBar></NavigationBar>
        <main>
            <section class="page-section">
                <div class="dataset-table">
                    <h3>{{ $t('bst1') }}</h3>
                    <p>{{ $t('bst2') }}</p>
                    <!-- 筛选逻辑 -->                   
                    <el-card class="filter-box" shadow="never">
                    <el-row :gutter="20" align="middle" class="filter-row">
                        <!-- 物种 -->
                        <el-col :span="1" class="filter-label">
                        {{ $t('bsc3') }}
                        </el-col>
                        <el-col :span="3">
                        <el-select
                            v-model="filters.species"
                            size="default"
                            class="filter-select"
                            placeholder="Select Species"
                        >
                            <el-option label="All"  value="all"  />
                            <el-option label="Mouse" value="mouse" />
                            <el-option label="Human" value="human" />
                        </el-select>
                        </el-col>

                        <!-- 区域 -->
                        <el-col :span="1" class="filter-label">
                        {{ $t('bsc4') }}
                        </el-col>
                        <el-col :span="3">
                        <el-select
                            v-model="filters.area"
                            size="default"
                            class="filter-select"
                            placeholder="Select Area"
                        >
                            <el-option label="All"  value="all" />
                            <!-- Mouse / All 可见 -->
                            <el-option v-if="filters.species === 'mouse' || filters.species === 'all'" label="DRN"  value="DRN" />
                            <el-option v-if="filters.species === 'mouse' || filters.species === 'all'" label="HIP"  value="HIP" />
                            <el-option v-if="filters.species === 'mouse' || filters.species === 'all'" label="Stri" value="Stri" />
                            <el-option v-if="filters.species === 'mouse' || filters.species === 'all'" label="VTA"  value="VTA" />
                            <el-option v-if="filters.species === 'mouse' || filters.species === 'all'" label="mPFC" value="mPFC" />
                            <!-- Human / All 可见 -->
                            <el-option v-if="filters.species === 'human' || filters.species === 'all'" label="BA12" value="BA12" />
                            <el-option v-if="filters.species === 'human' || filters.species === 'all'" label="BA46" value="BA46" />
                        </el-select>
                        </el-col>

                        <!-- 性别 -->
                        <el-col :span="1" class="filter-label">
                        {{ $t('bsc5') }}
                        </el-col>
                        <el-col :span="3">
                        <el-select
                            v-model="filters.sex"
                            size="default"
                            class="filter-select"
                            placeholder="Select Sex"
                        >
                            <el-option label="All"     value="all" />
                            <el-option label="Male"    value="male" />
                            <el-option label="Female"  value="female" />
                        </el-select>
                        </el-col>
                    </el-row>
                    </el-card>

                    <!-- 数据集列表 -->  

                    <el-table
                    :data="filteredDatasets"
                    stripe
                    style="width: 100%;"
                    >
                    <!-- 数据集编号（可点击跳转） -->
                    <el-table-column
                        prop="dataset_id"
                        :label="$t('bsc6.1')"
                        width="180"
                    >
                        <template #default="{ row }">
                        <span class="clickable" @click="goToDatasetDetails(row.dataset_id)">
                            {{ row.dataset_id }}
                        </span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="species"    :label="$t('bsc6.2')" />
                    <el-table-column prop="sex"        :label="$t('bsc6.3')" />
                    <el-table-column prop="conditions" :label="$t('bsc6.4')" />
                    <el-table-column prop="area"       :label="$t('bsc6.5')" />
                    <el-table-column prop="age"        :label="$t('bsc6.6')" />
                    <el-table-column prop="cells"      :label="$t('bsc6.7')" />
                    <el-table-column prop="way"        :label="$t('bsc6.8')" />
                    </el-table>
                    <BackToTop />
                </div>
            </section>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';

//----------以下为一个ssmood页面需要的最基础的东西--------------
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------

const datasets = ref([]);
const filters = ref({
  species: 'all',
  area: 'all',
  sex: 'all'
});

const filteredDatasets = computed(() => {
  return datasets.value.filter(dataset => {
    return (
      (filters.value.species === 'all' || dataset.species === filters.value.species) &&
      (filters.value.area === 'all' || dataset.area === filters.value.area) &&
      (filters.value.sex === 'all' || dataset.sex === filters.value.sex)
    );
  });
});

const router = useRouter();

const fetchDatasets = async () => {
  try {
    const response = await fetch(config.apiUrl + 'bst_getSTDatasetList.php');
    const data = await response.json();
    datasets.value = data;
  } catch (error) {
    console.error('Error fetching datasets:', error);
  }
};

onMounted(fetchDatasets);

const goToDatasetDetails = (datasetId) => {
  router.push({ name: 'STPage', params: { id: datasetId } });
};
</script>

<style scoped>
@import 'css/MainStyles.css';

.has-submenu {
    background: rgba(255, 255, 255, 0.3);
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filters label {
    font-weight: bold;
    margin-right: 10px;
    color: #333;
}

.filters select {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
    transition: border-color 0.3s;
}

.filters select:focus {
    border-color: #4CAF50;
    outline: none;
}

.filters select:hover {
    border-color: #45a049;
}


table {
    width: 100%;
    border-collapse: collapse;
    background-color: #f9f9f9;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}

tr:hover {
    background-color: #f1f1f1;
}

button {
    background-color: rgb(093, 116, 162);
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background-color: rgba(093, 116, 162, 0.8);
}


.clickable {
    cursor: pointer;
    text-decoration: underline;
    color: rgb(093, 116, 162); /* 你可以根据需要更改颜色 */
}

.clickable:hover {
    text-decoration: none;
    color: rgba(093, 116, 162, 0.8); /* 悬浮时的颜色 */
}

</style>
