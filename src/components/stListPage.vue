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
                    <back-to-top />
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';

export default {
    name: 'BrowseSpatialTranscriptomePage',
    data() {
        return {
            showSubMenu: false,
            datasets: [],
            filters: {
                species: 'all',
                region: 'all',
                sex: 'all'
            }
        };
    },
    components: {
        BackToTop,
        LanguageSwitcher
    },
    computed: {
        filteredDatasets() {
            return this.datasets.filter(dataset => {
                return (
                    (this.filters.species === 'all' || dataset.species === this.filters.species) &&
                    (this.filters.region === 'all' || dataset.region === this.filters.region) &&
                    (this.filters.sex === 'all' || dataset.sex === this.filters.sex)
                );
            });
        }
    },
    mounted() {
        this.fetchDatasets();
    },
    created() {
        this.$i18n.locale = this.$root.$selectedLanguage || 'zh1';
    },
    methods: {
        onLanguageChanged(language) {
            this.$i18n.locale = language;
        },
        fetchDatasets() {
            fetch('http://172.16.165.250/ssmood3/php/bst_getSTDatasetList.php')
                .then(response => response.json())
                .then(data => {
                    this.datasets = data;
                })
                .catch(error => console.error('Error fetching datasets:', error));
        },
        goToDatasetDetails(datasetId) {
        this.$router.push({ name: 'stdDetail', params: { id: datasetId } });
    }
    }
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
