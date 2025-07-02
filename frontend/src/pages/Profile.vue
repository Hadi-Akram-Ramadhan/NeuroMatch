<template>
  <div class="max-w-md mx-auto p-8">
    <h2 class="text-2xl font-bold mb-4">Your Profile</h2>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>
    <div v-else class="bg-white rounded shadow p-4">
      <p><strong>Mood:</strong> <span>{{ profile?.mood || '--' }}</span></p>
      <p><strong>Personality:</strong></p>
      <ul class="list-disc ml-6">
        <li>Openness: {{ profile?.personality?.openness ?? '--' }}</li>
        <li>Conscientiousness: {{ profile?.personality?.conscientiousness ?? '--' }}</li>
        <li>Extraversion: {{ profile?.personality?.extraversion ?? '--' }}</li>
        <li>Agreeableness: {{ profile?.personality?.agreeableness ?? '--' }}</li>
        <li>Neuroticism: {{ profile?.personality?.neuroticism ?? '--' }}</li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const profile = ref<any>(null);
const loading = ref(true);
const error = ref('');

const fetchProfile = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await axios.get('http://localhost:8000/api/eeg/profile');
    profile.value = res.data;
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Failed to fetch profile';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchProfile);
</script> 