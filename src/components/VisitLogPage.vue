<template>
    <div>
        <NavigationBar></NavigationBar>
        <main>
            <section class="page-section">
            <el-header>
              <h2>📊 Visit Dashboard</h2>
            </el-header>

              <el-row :gutter="20" class="stat-row">
                <el-col :span="4" v-for="item in stats" :key="item.label">
                  <el-card shadow="hover">
                    <div>
                      <div style="font-size: 16px; color: #666">{{ item.label }}</div>
                      <div style="font-size: 28px; font-weight: bold">{{ item.value }}</div>
                      <div v-if="item.change !== undefined" style="color: item.change >= 0 ? 'green' : 'red'">
                        {{ item.changeText }}
                      </div>
                    </div>
                  </el-card>
                </el-col>
              </el-row>

              <el-divider content-position="left">🌍 Visitors Map</el-divider>

              <div id="map" style="width: 80%; height: 500px; border-radius: 8px; border: 1px solid #ccc; margin: 0 auto;"></div>

              <el-divider content-position="left">🧑‍💻 Recent Visitors</el-divider>

              <el-table :data="visitors" stripe style="width: 100%">
                <el-table-column prop="country" label="Country"></el-table-column>
                <el-table-column prop="region" label="Region"></el-table-column>
                <el-table-column prop="browser" label="Browser"></el-table-column>
                <el-table-column prop="os" label="OS"></el-table-column>
                <el-table-column prop="time" label="Visit Time"></el-table-column>
              </el-table>


              <BackToTop></BackToTop>
              </section>
            
        </main>
        
    </div>
</template>

<script setup>
//----------以下为一个ssmood页面需要的最基础的东西--------------
//import { useI18n } from 'vue-i18n';
import BackToTop from './general/BackToTop.vue';
//import LanguageSwitcher from './general/LanguageSwitcher.vue';
import NavigationBar from './general/NavigationBar.vue';
import config from '@/config';
//----------以上为一个ssmood页面需要的最基础的东西--------------


import { ref, onMounted } from 'vue'
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'

const stats = ref([])
const visitors = ref([])
const map = ref(null)

const fetchData = async () => {
  const res = await fetch(config.apiUrl+'visit_dashboard.php')
  const data = await res.json()

  stats.value = [
    {
      label: 'Today Pageviews',
      value: data.stats.today,
      change: data.stats.today - data.stats.yesterday,
      changeText: `${data.stats.today - data.stats.yesterday} (${((data.stats.today - data.stats.yesterday) / Math.max(1, data.stats.yesterday) * 100).toFixed(2)}%)`,
    },
    { label: 'Yesterday', value: data.stats.yesterday },
    { label: 'Last 7 days', value: data.stats.last7 },
    { label: 'Previous Period', value: data.stats.prev7 },
    { label: 'Last 30 days', value: data.stats.last30 },
    { label: 'Total Pageviews', value: data.stats.total },
  ]

  visitors.value = data.visitors
  data.mapPoints.forEach(p => {
    L.circleMarker([p.lat, p.lon], { radius: 3, color: 'red' })
      .bindPopup(p.label)
      .addTo(map.value)
  })
}

onMounted(async() => {
  map.value = L.map('map', {
  scrollWheelZoom: false
}).setView([20, 0], 2)
  // 无边界灰白图
  L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://carto.com/">CARTO</a>, &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map.value)

  // 英文免责声明
  const disclaimer = L.control({ position: 'bottomright' })
  disclaimer.onAdd = function () {
    const div = L.DomUtil.create('div', 'map-disclaimer')
    div.innerHTML = 'Disclaimer: Location data is approximate and for visualization only.'
    div.style.background = 'rgba(255, 255, 255, 0.8)'
    div.style.padding = '6px'
    div.style.fontSize = '12px'
    div.style.borderRadius = '4px'
    return div
  }
  disclaimer.addTo(map.value)

 await fetchData()
})
import { onUnmounted } from 'vue'

onUnmounted(() => {
  if (map.value) {
    map.value.remove()
    map.value = null
  }
})
</script>

<style scoped>
.stat-row {
  margin-bottom: 24px;
}
</style>
