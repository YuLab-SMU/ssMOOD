<template>
    <div>
        <NavigationBar></NavigationBar>
        <!-- Main content -->
        <main class="container">
            <section class="page-section">
                <div class="form-group">
                     <h1>下载数据</h1>

                    <p>下载数据库为CSV文件。</p>
                    <form @submit.prevent="exportToCSV">
                        <button type="submit" class="btn btn-primary btn-block">下载</button>
                    </form>
                </div>
            </section>
        </main>
        <BackToTop></BackToTop>
    </div>
</template>
<script setup>
import BackToTop from './general/BackToTop.vue';
import NavigationBar from './general/NavigationBar.vue';



</script>
<style scoped>
@import'css/MainStyles.css';
 main {
    margin-top: 50px;
    padding: 20px;
}
h1 {
    color: rgb(93, 116, 162);
    margin-bottom: 40px;
    text-align: center;
}
.form-group label {
    font-weight: bold;
}
.page-section {
    background-color: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.btn-primary {
    background-color: rgb(93, 116, 162);
    border-color: rgb(93, 116, 162);
}
.btn-primary:hover {
    background-color: rgb(196, 216, 242);
    border-color: rgb(196, 216, 242);
}
</style>