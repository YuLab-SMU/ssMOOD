<template>
  <div class="navigation-container">
    <img src="@/assets/Human-MDD.png" alt="Navigation Image" class="navigation-image" />

    <!-- BA9 -->
    <div class="nav-overlay ba9">
      <button class="nav-btn" @click="goToPage('study3.BA9')">BA9</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study3.BA9.Control')">16Control</button>
      <button class="nav-btn" @click="goToPage('study3.BA9.MDD')">17MDD</button>
    </div>

    <!-- BA46 -->
    <div class="nav-overlay ba46">
      <button class="nav-btn" @click="goToPage('study3.BA46')">BA46</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study3.BA46.Control')">2Control</button>
      <button class="nav-btn" @click="goToPage('study3.BA46.MDD')">2MDD</button>
    </div>

    <!-- BA12 -->
    <div class="nav-overlay ba12">
      <button class="nav-btn" @click="goToPage('study3.BA12')">BA12</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study3.BA12.Control')">2Control</button>
      <button class="nav-btn" @click="goToPage('study3.BA12.MDD')">2MDD</button>
    </div>

    <!-- BA11 -->
    <div class="nav-overlay ba11">
      <button class="nav-btn" @click="goToPage('study3.BA11')">BA11</button>
      <span class="nav-label">:</span>
      <button class="nav-btn" @click="goToPage('study3.BA11.Control')">22Control</button>
      <button class="nav-btn" @click="goToPage('study3.BA11.MDD')">3MDD</button>
    </div>
    <button class="navigation-button button-11" @click="goToPage('study3.all')">
      All
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToPage = (study) => {
  router.push({ name: 'scStudy', params: { study: study } });
};
</script>

<style scoped>
.navigation-container {
  position: relative;
  display: inline-block;
  /* Size the container to the image */
}

.navigation-image {
  display: block;
  width: 100%;
  /* Or use a fixed width/height */
  height: auto;
}

.nav-overlay {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.65);
  /* Semi-transparent backdrop so the image doesn't interfere with the text */
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
  /* Prevent wrapping */
}

/* Fine-tune left / top based on the brain region's actual position in the image */
.ba9 {
  top: 10%;
  left: 35%;
}

.ba46 {
  top: 30%;
  left: 35%;
}

.ba12 {
  top: 50%;
  left: 35%;
}

.ba11 {
  top: 70%;
  left: 35%;
}


.nav-btn {
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.4);
  /* Semi-transparent background */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

/* Hover effect */
.nav-btn:hover {
  background-color: rgba(0, 0, 0, 0.6);
  /* Darken background on hover */
  transform: scale(1.1);
  /* Enlarge button */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  /* Add shadow */
}
.button-11{
  bottom: 90px;
  left: 45px;
}
.navigation-button {
  position: absolute;
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.4);
  /* Semi-transparent background */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

/* Hover effect */
.navigation-button:hover {
  background-color: rgba(0, 0, 0, 0.6);
  /* Darken background on hover */
  transform: scale(1.1);
  /* Enlarge button */
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  /* Add shadow */
}
</style>