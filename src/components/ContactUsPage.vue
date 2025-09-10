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
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <div class="card-header">
                  <el-avatar size="large" src="/boy.png" />
                </div>
              </template>

              <div class="contact-info">
                <h3>{{ $t('acu01') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu05') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email1}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail1 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email1)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
                </p>
              </div>
            </el-card>
          </el-col>

          <!-- 成员 2 -->
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <el-avatar size="large" src="/boy.png" />
              </template>
              <div class="contact-info">
                <h3>{{ $t('acu02') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu06') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email2}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail2 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email2)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
                </p>
              </div>
            </el-card>
          </el-col>

          <!-- 成员 2-1 -->
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <el-avatar size="large" src="/boy.png" />
              </template>
              <div class="contact-info">
                <h3>{{ $t('acu02-1') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu07') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email5}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail5 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email5)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
                </p>
              </div>
            </el-card>
          </el-col>

          <!-- 成员 2-2 -->
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <el-avatar size="large" src="/girl.png" />
              </template>
              <div class="contact-info">
                <h3>{{ $t('acu02-2') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu08') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email6}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail6 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email6)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
                </p>
              </div>
            </el-card>
          </el-col>

          <!-- 成员 3 -->
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <el-avatar size="large" src="/girl.png" />
              </template>
              <div class="contact-info">
                <h3>{{ $t('acu03') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu06') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email3}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail3 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email3)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
                </p>
              </div>
            </el-card>
          </el-col>

          <!-- 成员 4 -->
          <el-col :xs="24" :sm="12" :md="7">
            <el-card class="contact-card" shadow="hover">
              <template #header>
                <el-avatar size="large" src="/boy.png" />
              </template>
              <div class="contact-info">
                <h3>{{ $t('acu04') }}</h3>
                <p>
                  <el-icon><Location /></el-icon>
                  {{ $t('acu06') }}
                </p>
                <p class="email-row">
                  <a :href="`mailto:${email4}`">
                    <el-icon><Message /></el-icon>
                    {{ safeEmail4 }}
                  </a>
                  <el-button link size="small" @click="copyEmail(email4)">
                    <el-icon><CopyDocument /></el-icon>
                  </el-button>
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
import { Location, Message, CopyDocument } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import BackToTop from './general/BackToTop.vue'
import NavigationBar from './general/NavigationBar.vue'

// 🛡️ 用 JS 拼接邮箱，避免源码直接出现完整地址
const email1 = ['caoxiong', 'smu.edu.cn'].join('@')
const email2 = ['guangchuangyu', 'smu.edu.cn'].join('@')
const email3 = ['treywea', 'smu.edu.cn'].join('@')
const email4 = ['goldmorningsmart', 'outlook.com'].join('@')
const email5 = ['taotaomzk', 'smu.edu.cn'].join('@')
const email6 = ['xiemin', 'genomics.cn'].join('@')

// 显示用（把 @ 替换成全角＠）
const safeEmail1 = email1.replace('@', '＠')
const safeEmail2 = email2.replace('@', '＠')
const safeEmail3 = email3.replace('@', '＠')
const safeEmail4 = email4.replace('@', '＠')
const safeEmail5 = email5.replace('@', '＠')
const safeEmail6 = email6.replace('@', '＠')

// 复制函数
const copyEmail = async (email) => {
  try {
    await navigator.clipboard.writeText(email)
    ElMessage.success('邮箱已复制')
  } catch (err) {
    ElMessage.error('复制失败，请手动复制')
  }
}
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

.email-row {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
