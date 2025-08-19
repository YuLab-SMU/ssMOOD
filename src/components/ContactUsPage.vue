<template>
    <div>
        <NavigationBar></NavigationBar>
        <!-- Main content -->
        <main class="container">
            <section class="page-section">
                <h1>{{ $t('ContactUs') }}</h1>

                <!-- 卡片栅格 -->
                <el-row :gutter="24" justify="center">
                    <!-- 成员 1 -->
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-card class="contact-card" shadow="hover">
                            <template #header>
                                <div class="card-header">
                                    <el-avatar size="large"
                                        src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png" />
                                </div>
                            </template>

                            <div class="contact-info">
                                <h3>{{ $t('acu01') }}</h3>
                                <p>
                                    <el-icon>
                                        <Location />
                                    </el-icon>
                                    {{ $t('acu05') }}
                                </p>
                                <p>
                                    <a :href="`mailto:caoxiong@smu.edu.cn`">
                                        <el-icon>
                                            <Message />
                                        </el-icon>
                                        caoxiong@smu.edu.cn
                                    </a>
                                </p>
                            </div>
                        </el-card>
                    </el-col>

                    <!-- 成员 2 -->
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-card class="contact-card" shadow="hover">
                            <template #header>
                                <el-avatar size="large"
                                    src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png" />
                            </template>
                            <div class="contact-info">
                                <h3>{{ $t('acu02') }}</h3>
                                <p>
                                    <el-icon>
                                        <Location />
                                    </el-icon>
                                    {{ $t('acu06') }}
                                </p>
                                <p>
                                    <a :href="`mailto:guangchuangyu@smu.edu.cn`">
                                        <el-icon>
                                            <Message />
                                        </el-icon>
                                        guangchuangyu@smu.edu.cn
                                    </a>
                                </p>
                            </div>
                        </el-card>
                    </el-col>

                    <!-- 成员 3 -->
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-card class="contact-card" shadow="hover">
                            <template #header>
                                <el-avatar size="large"
                                    src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png" />
                            </template>
                            <div class="contact-info">
                                <h3>{{ $t('acu03') }}</h3>
                                <p>
                                    <el-icon>
                                        <Location />
                                    </el-icon>
                                    {{ $t('acu06') }}
                                </p>
                                <p>
                                    <a :href="`mailto:treywea@smu.edu.cn`">
                                        <el-icon>
                                            <Message />
                                        </el-icon>
                                        treywea@smu.edu.cn
                                    </a>
                                </p>
                            </div>
                        </el-card>
                    </el-col>

                    <!-- 成员 4 -->
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-card class="contact-card" shadow="hover">
                            <template #header>
                                <h3>网站开发</h3>
                            </template>
                            <div class="contact-info">


                                <h3>{{ $t('acu04') }}</h3>
                                <p>
                                    <el-icon>
                                        <Location />
                                    </el-icon>
                                    {{ $t('acu06') }}
                                </p>
                                <p>
                                    <a :href="`mailto:goldmorningsmart@outlook.com`">
                                        <el-icon>
                                            <Message />
                                        </el-icon>
                                        goldmorningsmart@outlook.com
                                    </a>
                                </p>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
            </section>
        </main>
        <BackToTop></BackToTop>
    </div>
</template>

<script setup>
// 引入 Element Plus 图标
import { Location, Message } from '@element-plus/icons-vue'
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';
</script>

<style scoped>
/* 卡片统一宽度 & 垂直居中 */
.contact-card {
    text-align: center;
    margin-bottom: 20px;
}

.card-header {
    display: flex;
    justify-content: center;
}

.contact-info h3 {
    margin: 10px 0;
    color: #5d74a2;
}

.contact-info p {
    margin: 6px 0;
    font-size: 14px;
    color: #666;
}

.contact-info a {
    color: #409eff;
    text-decoration: none;
}

.contact-info .el-icon {
    vertical-align: -2px;
    margin-right: 4px;
}
</style>