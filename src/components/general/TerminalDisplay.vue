<template>
  <div class="terminal">
    <div class="terminal-header">
      <span>Terminal</span>
    </div>
    <pre class="terminal-content" v-html="formattedOutput"></pre>
  </div>
</template>

<script>
export default {
  name: 'TerminalDisplay',
  props: {
    output: {
      type: String,
      required: true
    }
  },
  computed: {
    formattedOutput() {
      return this.highlight(this.escapeHtml(this.output));
    }
  },
  methods: {
    escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, (m) => map[m]);
    },
    highlight(text) {
      // 这里可以添加一些简单的文本高亮逻辑
      // 例如，将所有的错误信息高亮显示为红色
      return text.replace(/ERROR/g, '<span style="color: red;">ERROR</span>');
    }
  }
};
</script>

<style scoped>
.terminal {
  font-family: 'Courier New', Courier, monospace;
  background-color: #000;
  color: #fff;
  padding: 10px;
  border-radius: 5px;
  overflow: auto;
  max-height: 400px;
  width: 800px;
}

.terminal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 5px 10px;
  background-color: #333;
  color: #fff;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

.terminal-content {
  margin: 0;
  padding: 0;
  white-space: pre-wrap; /* 保持格式，同时允许换行 */
  color: #fff;
}
</style>