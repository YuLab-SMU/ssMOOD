<template>
    <div>
        <!-- Header -->
        <header>
            <nav>
                <div class="logo">
                    <img src="@/assets/logo.png" alt="Logo">
                </div>
                <ul>
                    <li>
                        <router-link to="/">{{ $t('home') }}</router-link>
                    </li>
                    <li @mouseover="showSubMenu = true" @mouseleave="showSubMenu = false" class="has-submenu"> <a>{{ $t('browse') }}</a>

                        <!-- 二级菜单 -->
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
              <!--       <li>
                        <router-link to="/search">{{ $t('search') }}</router-link>
                    </li>
-->
                    <li class="NowPage">
                        <router-link to="/download">{{ $t('download') }}</router-link>
                    </li>
                    <li>
                        <router-link to="/about">{{ $t('about') }}</router-link>
                    </li>
                </ul>
                    <LanguageSwitcher @languageChanged="onLanguageChanged" />
            </nav>
        </header>
        <!-- Main content -->
        <main class="container">
            <section class="page-section">
                <div class="form-group">
                     <h1>下载数据</h1>

                    <p>下载数据库为CSV文件。</p>
                    <form @submit.prevent="exportToCSV">
                        <button type="submit" class="btn btn-primary btn-block">下载</button>
                    </form>
                </div>
            </section>
        </main>
        <back-to-top />
    </div>
</template>
<script>
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';


export
default {
    name: 'DownloadPage',
    data() {
        return {
            showSubMenu: false,
        };
    },
    components: {
        BackToTop,
        LanguageSwitcher
    },
    created() {
        this.$i18n.locale = this.$root.$selectedLanguage || 'zh1';
    },


    methods: {
        onLanguageChanged(language) {
            this.$i18n.locale = language;
        },
        exportToCSV() {
            // 发起下载请求
            fetch('../php/export_csv.php', {
                method: 'POST',
            })
                .then(response => {
                if (response.ok) {
                    // 处理文件下载逻辑
                    const url = window.URL.createObjectURL(new Blob([response.blob()]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'data.csv');
                    document.body.appendChild(link);
                    link.click();
                    link.parentNode.removeChild(link);
                    window.URL.revokeObjectURL(url);
                } else {
                    console.error('Failed to download the file');
                }
            })
                .
            catch (error => console.error('Error:', error));
        },
    },
};
</script>
<style scoped>
@import'css/MainStyles.css';
 main {
    margin-top: 50px;
    padding: 20px;
}
h1 {
    color: rgb(93, 116, 162);
    margin-bottom: 40px;
    text-align: center;
}
.form-group label {
    font-weight: bold;
}
.page-section {
    background-color: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.btn-primary {
    background-color: rgb(93, 116, 162);
    border-color: rgb(93, 116, 162);
}
.btn-primary:hover {
    background-color: rgb(196, 216, 242);
    border-color: rgb(196, 216, 242);
}
</style>