<template>
    <div>
        <NavigationBar></NavigationBar>

        <!-- Main content -->
        <main class="container">
            <section class="page-section">
                <h1>{{ $t('dp01') }}</h1>

                <!-- GitHub 源码卡片 -->
                <el-card class="download-card" shadow="hover">
                    <template #header>
                        <div class="card-header">
                            <el-icon size="24" color="#409EFF">
                                <Link />
                            </el-icon>
                            <span class="card-title">{{ $t('dp02') }}</span>
                        </div>
                    </template>
                    <div class="card-content">
                        <p class="card-description">
                            {{ $t('dp03') }}
                        </p>
                        <el-button
                                type="primary"
                                size="large"
                                @click="openGitHub"
                                class="download-btn"
                        >
                            <el-icon><Download /></el-icon>
                            {{ $t('dp04') }}
                        </el-button>
                    </div>
                </el-card>

                <!-- 生物数据卡片 -->
                <el-card class="download-card" shadow="hover">
                    <template #header>
                        <div class="card-header">
                            <el-icon size="24" color="#67C23A">
                                <Document />
                            </el-icon>
                            <span class="card-title">{{ $t('dp05') }}</span>
                        </div>
                    </template>
                    <div class="card-content">
                        <p class="card-description">
                            {{ $t('dp06') }}
                        </p>
                        <el-button
                                type="success"
                                size="large"
                                @click="openBioData"
                                class="download-btn"
                        >
                            <el-icon><Download /></el-icon>
                            {{ $t('dp07') }}
                        </el-button>
                    </div>
                </el-card>
            </section>
        </main>

        <BackToTop></BackToTop>
    </div>
</template>

<script setup>
import { Link, Download, Document } from '@element-plus/icons-vue'
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';

// 打开GitHub仓库
const openGitHub = () => {
    window.open('https://github.com/goldmorningsmart/ssMOOD/', '_blank')
}

// 打开生物数据下载页面
const openBioData = () => {
    window.open('https://ngdc.cncb.ac.cn/', '_blank')
}
</script>

<style scoped>
@import'css/MainStyles.css';

main {
    margin-top: 5vh;
    padding: 20px;
}

h1 {
    color: rgb(93, 116, 162);
    margin-bottom: 40px;
    text-align: center;
}

.download-card {
    max-width: 600px;
    margin: 30px auto;
    border-radius: 12px;
}

.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: bold;
}

.card-title {
    margin: 0;
    color: #303133;
}

.card-content {
    text-align: center;
    padding: 20px 0;
}

.card-description {
    color: #606266;
    margin-bottom: 30px;
    line-height: 1.8;
    font-size: 16px;
}

.download-btn {
    font-size: 16px;
    padding: 12px 30px;
}

.page-section {
    background-color: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .download-card {
        margin: 20px 15px;
    }
    
    h1 {
        font-size: 24px;
    }
}
</style>