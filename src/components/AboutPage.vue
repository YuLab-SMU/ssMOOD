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

                   <!--  <li>

                        <router-link to="/search">{{ $t('search') }}</router-link>

                    </li>

-->
                    <li>
                        <router-link to="/download">{{ $t('download') }}</router-link>
                    </li>
                    <li class="NowPage">
                        <router-link to="/about">{{ $t('about') }}</router-link>
                    </li>
                </ul>
                    <LanguageSwitcher @languageChanged="onLanguageChanged" />
            </nav>
        </header>
        <main>
            <section class="page-section"></section>
        </main>
        <back-to-top />
    </div>
</template>
<script>
import BackToTop from './general/BackToTop.vue';
import LanguageSwitcher from './general/LanguageSwitcher.vue';

export
default {
    name: 'AboutPage',
    data() {
        return {
            showSubMenu: false,
        };
    },
    components: {
        BackToTop,
        LanguageSwitcher
    },
    mounted() {

    },
    created() {
        this.$i18n.locale = this.$root.$selectedLanguage || 'zh1';
    },
    methods: {
        onLanguageChanged(language) {
            this.$i18n.locale = language;
        },

    },
};
</script>
<style scoped>
@import'css/MainStyles.css';
</style>