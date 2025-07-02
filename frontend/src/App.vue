<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();

const onLogout = () => {
  auth.logout();
  router.push('/login');
};
</script>

<template>
  <div>
    <nav class="bg-gray-800 text-white p-4 flex gap-4">
      <router-link to="/" class="hover:underline">Home</router-link>
      <router-link to="/login" class="hover:underline" v-if="!auth.token">Login</router-link>
      <router-link to="/register" class="hover:underline" v-if="!auth.token">Register</router-link>
      <router-link to="/eeg-upload" class="hover:underline" v-if="auth.token">EEG Upload</router-link>
      <router-link to="/profile" class="hover:underline" v-if="auth.token">Profile</router-link>
      <router-link to="/match" class="hover:underline" v-if="auth.token">Match</router-link>
      <button v-if="auth.token" @click="onLogout" class="ml-auto bg-red-600 hover:bg-red-700 px-3 py-1 rounded">Logout</button>
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
