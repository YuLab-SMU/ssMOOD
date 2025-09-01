<template>
  <div></div>
</template>

<script setup>
import { onMounted } from 'vue';
// 打印版权信息
const logCopyright = () => {
  const version = "ssMOOD "+process.env.BRANCH;//从项目设置自动获取
  console.log(
    `%c${version}`,
    'background:#333333; color:white; font-size:30px; padding:5px; border-radius:5px;'
  );
  console.log(
    `%c${"Branch:"+process.env.BRANCH}`,
    'color:rgb(93, 116, 162); font-size:12px;'
  );
  console.log(
    `%c${"Hash:"+process.env.COMMITHASH}`,
    'color:rgb(93, 116, 162); font-size:12px;'
  );
  console.log(
    `%c${"Designed by Jinchen Li"}`,
    'color:rgb(93, 116, 162); font-size:12px;'
  );
};

// 在组件挂载时执行
onMounted(() => {
  logCopyright(); // 打印版权信息
});
</script>