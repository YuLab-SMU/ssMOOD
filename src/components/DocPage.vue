<template>
  <div>
    <NavigationBar />
    <main class="container">
      <section class="page-section">
        <div v-if="loading">加载中...</div>
        <div v-else v-html="content" class="md"></div>
      </section>
    </main>
    <BackToTop />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import BackToTop from "./general/BackToTop.vue";
import NavigationBar from "./general/NavigationBar.vue";

import { marked } from "marked";
import { markedHighlight } from "marked-highlight";
import hljs from "highlight.js";
import "highlight.js/styles/atom-one-light.css";  // 浅色主题


marked.use(markedHighlight({
  langPrefix: 'hljs language-',
  highlight(code, lang) {
    try {
      if (lang && hljs.getLanguage(lang)) {
        return hljs.highlight(code, { language: lang }).value;
      }
      return hljs.highlightAuto(code).value;
    } catch {
      return code; // 出错时原样返回，避免空内容
    }
  }
}));

const route = useRoute();
const content = ref("");
const loading = ref(true);

const loadMarkdown = async (name) => {
  try {
    loading.value = true;
    const res = await fetch(`/doc/${name}.md`);
    if (!res.ok) throw new Error("文档不存在");
    const text = await res.text();
    content.value = marked.parse(text); // 用 parse 明确一些
  } catch (err) {
    content.value = `<p style="color:red;">文档加载失败: ${err.message}</p>`;
  } finally {
    loading.value = false;
  }
};

onMounted(() => loadMarkdown(route.params.name));
watch(() => route.params.name, n => n && loadMarkdown(n));
</script>


<style scoped>
@import "css/MainStyles.css";

/* 让 markdown 更美观 */
.prose {
  max-width: 90%;
  line-height: 1.8;
}
.md{
    width: 70%;
    margin: 0 auto;
}
/* 代码块样式优化 */
pre code {
  border-radius: 0.5rem;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  display: block;
  overflow-x: auto;
}
</style>
