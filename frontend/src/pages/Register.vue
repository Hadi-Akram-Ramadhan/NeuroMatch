<template>
  <div class="max-w-md mx-auto p-8">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form @submit.prevent="onRegister">
      <input v-model="name" class="w-full mb-4 border border-gray-300 rounded px-3 py-2" type="text" placeholder="Name" required />
      <input v-model="email" class="w-full mb-4 border border-gray-300 rounded px-3 py-2" type="email" placeholder="Email" required />
      <input v-model="password" class="w-full mb-4 border border-gray-300 rounded px-3 py-2" type="password" placeholder="Password" required />
      <button :disabled="auth.loading" class="w-full bg-blue-600 text-white rounded px-3 py-2 hover:bg-blue-700 transition disabled:opacity-50" type="submit">
        {{ auth.loading ? 'Registering...' : 'Register' }}
      </button>
      <p v-if="auth.error" class="text-red-600 mt-2">{{ auth.error }}</p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const name = ref('');
const email = ref('');
const password = ref('');
const auth = useAuthStore();
const router = useRouter();

const onRegister = async () => {
  await auth.register(name.value, email.value, password.value);
  if (auth.token) {
    router.push('/profile');
  }
};
</script> 