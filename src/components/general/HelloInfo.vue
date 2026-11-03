<template>
  <div></div>
</template>

<script setup>
import { onMounted } from 'vue';
// Print copyright info
const logCopyright = () => {
  const version = "ssMOOD "+process.env.BRANCH;//Read automatically from project settings
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

// Run when the component is mounted
onMounted(() => {
  logCopyright(); // Print copyright info
});
</script>