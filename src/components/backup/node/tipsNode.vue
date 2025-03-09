<template>
  <div class="tips-node">
    <label for="tipsInput">Tips:</label>
    <textarea
      id="tipsInput"
      class="tips-textarea nodrag"
      v-model="tipsValue"
      @input="onTipsChange"
      placeholder="Enter your tips here..."
    ></textarea>
  </div>
</template>

<script setup>
import { ref, defineProps,onMounted} from 'vue';

// 定义接收的属性
const props = defineProps({
  id: String,
  data: Object
});

// 定义发出的事件
//const emit = defineEmits(['update:tips']);

// 响应式引用，用于存储输入框的内容
const tipsValue = ref('');

// 处理文本框内容变化的方法
function onTipsChange(event) {
  tipsValue.value = event.target.value;
 // eslint-disable-next-line
  props.data.tips = tipsValue.value;
}
onMounted(() => {
  
  if(props.data.tips!==null){
      tipsValue.value = props.data.tips;
  }
});

</script>

<style scoped>
.tips-node {
  display: flex;
  flex-direction: column;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  height: 200px;
}

.tips-textarea {
  width: 80%;
  height: 80%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-top: 5px;
  resize: vertical; /* 允许垂直调整大小 */
}
</style>