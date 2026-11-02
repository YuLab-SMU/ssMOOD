<template>
  <div>
    <NavigationBar></NavigationBar>
    <!-- Main content -->
    <main class="container">
      <section class="page-section">
        <h1>{{ $t('ContactUs') }}</h1>

        <!-- Card grid -->
        <el-row :gutter="24" justify="center">
          <!-- Member 1 -->
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

          <!-- Member 2 -->
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

          <!-- Member 2-1 -->
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

          <!-- Member 2-2 -->
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

          <!-- Member 3 -->
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

          <!-- Member 4 -->
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
// Import Element Plus icons
import { Location, Message, CopyDocument } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import BackToTop from './general/BackToTop.vue'
import NavigationBar from './general/NavigationBar.vue'

// 🛡️ Build the email address in JS so the full address never appears in the source
const email1 = ['caoxiong', 'smu.edu.cn'].join('@')
const email2 = ['guangchuangyu', 'smu.edu.cn'].join('@')
const email3 = ['treywea', 'smu.edu.cn'].join('@')
const email4 = ['yt22520318', 'i.smu.edu.cn'].join('@')
const email5 = ['taotaomzk', 'smu.edu.cn'].join('@')
const email6 = ['xiemin', 'genomics.cn'].join('@')

// For display (replace @ with a full-width at sign)
const safeEmail1 = email1.replace('@', '＠')
const safeEmail2 = email2.replace('@', '＠')
const safeEmail3 = email3.replace('@', '＠')
const safeEmail4 = email4.replace('@', '＠')
const safeEmail5 = email5.replace('@', '＠')
const safeEmail6 = email6.replace('@', '＠')

// Copy function
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
/* Uniform card width & vertical centering */
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
