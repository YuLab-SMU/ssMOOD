<template>
  <div>
    <VueTerminal
      ref="terminal"
      name="ssMOOD"
      context="ssMOOD"
      title="ssMOODTerminal"
      :theme="'light'"
      :height="'100%'" 
      @clear="clearOutput"
      @execCmd="onExecCmd"
    >
    </VueTerminal>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import VueTerminal from 'vue-web-terminal';

// Define reactive variables
const terminal = ref(null);

// 处理命令执行
const onExecCmd = async (key, command, success, failed) => {
  if(command === "ls"){
    success({
        type: 'normal',
        class: 'success',
        content: "获取文件列表成功"
        });
  }
  else if(command === "rm"){
    failed('找不到这个文件');

  }
  else{
      failed('未知命令');
  }
};


// Clear terminal output
const clearOutput = () => {
  terminal.value?.clearOutput();
};
</script>

<style scoped>
/* Custom terminal styling (if needed) */
</style>
