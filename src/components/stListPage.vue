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
                    <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="has-submenu">
                        <a>{{ $t('browse') }}</a>
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
                <div class="dataset-table">
                    <h3>{{ $t('bsc1') }}</h3>
                    <p>{{ $t('bsc2') }}</p>
                    <div class="filters">
                        <label for="species">{{ $t('bsc3') }}</label>
                        <select id="species" v-model="filters.species">
                            <option value="all">All</option>
                        </select>
                        <label for="region">{{ $t('bsc4') }}</label>
                        <select id="region" v-model="filters.region">
                            <option value="all">All</option>
                        </select>
                        <label for="sex">{{ $t('bsc5') }}</label>
                        <select id="sex" v-model="filters.sex">
                            <option value="all">All</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ $t('bsc6.1') }}</th>
                                <th>{{ $t('bsc6.2') }}</th>
                                <th>{{ $t('bsc6.3') }}</th>
                                <th>{{ $t('bsc6.4') }}</th>
                                <th>{{ $t('bsc6.5') }}</th>
                                <th>{{ $t('bsc6.6') }}</th>
                                <th>{{ $t('bsc6.7') }}</th>
                                <th>{{ $t('bsc6.8') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dataset in filteredDatasets" :key="dataset.dataset_id">
                                <td @click="goToDatasetDetails(dataset.dataset_id)" class="clickable">{{ dataset.dataset_id }}</td>
                                <td>{{ dataset.species }}</td>
                                <td>{{ dataset.sex }}</td>
                                <td>{{ dataset.conditions }}</td>
                                <td>{{ dataset.region }}</td>
                                <td>{{ dataset.age }}</td>
                                <td>{{ dataset.cells }}</td>
                                <td>{{ dataset.detail }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';
import config from '@/config';
const showSubMenu = ref(false);//二级菜单
//----------
//语言切换
//----------
const { locale } = useI18n();

// 处理语言切换
const onLanguageChanged = (language) => {
  locale.value = language; // 更新语言
  window.localStorage.setItem('selectedLanguage', language); // 可选：存储语言选择
};

onMounted(async() => {
  const selectedLanguage = window.localStorage.getItem('selectedLanguage') || 'zh1';
  locale.value = selectedLanguage; // 设置语言
});
//----------以上为一个ssmood页面需要的最基础的东西--------------

const datasets = ref([]);
const filters = ref({
  species: 'all',
  region: 'all',
  sex: 'all'
});

const filteredDatasets = computed(() => {
  return datasets.value.filter(dataset => {
    return (
      (filters.value.species === 'all' || dataset.species === filters.value.species) &&
      (filters.value.region === 'all' || dataset.region === filters.value.region) &&
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
  router.push({ name: 'stdDetail', params: { id: datasetId } });
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
