<template>
  <!-- Chart loading placeholder: overlays the chart container (replaces /loading.gif) -->
  <div class="chart-loading" role="status" aria-live="polite">
    <div class="chart-loading-dots" aria-hidden="true">
      <span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="chart-loading-text">{{ text }}</div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  text: { type: String, default: 'Loading data…' }
})
</script>

<style scoped>
.chart-loading {
  position: absolute;
  inset: 0;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 14px;
  border: 1px dashed rgba(93, 116, 162, 0.35);
  border-radius: 12px;
  background:
    radial-gradient(circle at 1px 1px, rgba(93, 116, 162, 0.12) 1px, transparent 0) 0 0 / 22px 22px,
    linear-gradient(180deg, #f7f9fc 0%, #eef2f8 100%);
}
.chart-loading-dots {
  display: flex;
  gap: 9px;
}
.chart-loading-dots span {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  animation: chartLoadingPulse 1.2s ease-in-out infinite;
}
/* Dot colors follow the cell-type palette */
.chart-loading-dots span:nth-child(1) { background: rgb(93, 116, 162); animation-delay: 0s; }
.chart-loading-dots span:nth-child(2) { background: #7ea8d8; animation-delay: .12s; }
.chart-loading-dots span:nth-child(3) { background: #5aab8a; animation-delay: .24s; }
.chart-loading-dots span:nth-child(4) { background: #e8825a; animation-delay: .36s; }
.chart-loading-dots span:nth-child(5) { background: #a06ec2; animation-delay: .48s; }
.chart-loading-text {
  font-size: 14px;
  letter-spacing: .04em;
  color: rgb(93, 116, 162);
}
@keyframes chartLoadingPulse {
  0%, 80%, 100% { transform: scale(.55); opacity: .35; }
  40%           { transform: scale(1);   opacity: 1; }
}
@media (prefers-reduced-motion: reduce) {
  .chart-loading-dots span { animation: none; opacity: .8; transform: none; }
}
</style>
