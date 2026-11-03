<template>
  <div class="navigation-container">
    <div class="image-container">
      <img src="@/assets/Mouse-CSDS.png" alt="Navigation Image" class="navigation-image" />
      <!-- Button 1 -->
      <button class="navigation-button button-1" @click="goToPage('study2.conditions.0D')">
        0Day(Control)
      </button>
      <!-- Button 2 -->
      <button class="navigation-button button-2" @click="goToPage('study2.conditions.3D')">
        3Day
      </button>
      <!-- Button 3  7D -->
      <button class="navigation-button button-3" @click="goToPage('study2.conditions.7D')">
        7Day
      </button>
    <!-- Button 4 -->
      <button class="navigation-button button-4" @click="goToPage('study2.conditions.S')">
        Sus
      </button>
          <!-- Button 5 -->
      <button class="navigation-button button-5" @click="goToPage('study2.conditions.R')">
        Res
      </button>
      
        <!-- Button 6 -->
      <button class="navigation-button button-6" @click="goToPage('study2.area.I')">
        I
      </button>
        <!-- Button 7 -->
      <button class="navigation-button button-7" @click="goToPage('study2.area.II')">
        II
      </button>
        <!-- Button 8 -->
      <button class="navigation-button button-8" @click="goToPage('study2.area.III')">
        III
      </button>
        <!-- Button 9 -->
      <button class="navigation-button button-9" @click="goToPage('study2.area.IV')">
        IV
      </button>
        <!-- Button 10 -->
      <button class="navigation-button button-10" @click="goToPage('study2.area.V')">
        V
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPage = (study) => {
  router.push({ name: 'stStudy', params: { study: study } });
};
</script>

<style scoped>
.navigation-container {
  position: relative;
  width: 400px; /* Adjust to the image size */
  height: auto; /* Adjust to the image size */
}
.image-container {
  position: relative;
  width: 100%;
  height: 100%;
}
.navigation-image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensure the image covers the entire container */
}
.navigation-button {
  position: absolute;
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
/* Hover effect */
.navigation-button:hover {
  background-color: rgba(0, 0, 0, 0.6); /* Darken background on hover */
  transform: scale(1.1); /* Enlarge button */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Add shadow */
}
.button-1 {
  top: 65px;
  left: 40px;
}
.button-2 {
  top: 13px;
  left: 115px;
}
.button-3 {
  top: 65px;
  left: 160px;
}
.button-4 {
  top: 20px;
  left: 280px;
}
.button-5 {
  top: 65px;
  left: 280px;
}
.button-6 {
  bottom: 90px;
  right: 220px;
}
.button-7 {
  bottom: 90px;
  right: 123px;
}
.button-8 {
  bottom: 90px;
  right: 23px;
}
.button-9 {
  bottom: 35px;
  right: 170px;
}
.button-10 {
  bottom: 35px;
  right: 60px;
}

/* ----------------------------------------------------------- */
/* Mobile responsive */
@media (max-width: 800px) {
  .navigation-button {
    display: none;
  }
}
</style>