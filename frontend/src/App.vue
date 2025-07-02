<script setup lang="ts">
</script>

<template>
  <div>
    <nav class="bg-gray-800 text-white p-4 flex gap-4">
      <router-link to="/" class="hover:underline">Home</router-link>
      <router-link to="/login" class="hover:underline">Login</router-link>
      <router-link to="/register" class="hover:underline">Register</router-link>
      <router-link to="/eeg-upload" class="hover:underline">EEG Upload</router-link>
      <router-link to="/profile" class="hover:underline">Profile</router-link>
      <router-link to="/match" class="hover:underline">Match</router-link>
    </nav>
    <router-view />
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
