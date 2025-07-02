<template>
  <div class="max-w-md mx-auto p-8">
    <h2 class="text-2xl font-bold mb-4">Your Matches</h2>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>
    <div v-else>
      <div v-if="matches.length === 0" class="bg-white rounded shadow p-4">No matches found.</div>
      <div v-for="match in matches" :key="match.id" class="bg-white rounded shadow p-4 mb-4">
        <p><strong>Name:</strong> {{ match.name }}</p>
        <p><strong>Email:</strong> {{ match.email }}</p>
        <p><strong>Mood:</strong> {{ match.mood || '--' }}</p>
        <p><strong>Personality:</strong></p>
        <ul class="list-disc ml-6">
          <li>Openness: {{ match.personality?.openness ?? '--' }}</li>
          <li>Conscientiousness: {{ match.personality?.conscientiousness ?? '--' }}</li>
          <li>Extraversion: {{ match.personality?.extraversion ?? '--' }}</li>
          <li>Agreeableness: {{ match.personality?.agreeableness ?? '--' }}</li>
          <li>Neuroticism: {{ match.personality?.neuroticism ?? '--' }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const matches = ref<any[]>([]);
const loading = ref(true);
const error = ref('');

const fetchMatches = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await axios.get('http://localhost:8000/api/match');
    matches.value = res.data.matches;
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Failed to fetch matches';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchMatches);
</script> 