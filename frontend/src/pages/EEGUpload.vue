<template>
  <div class="max-w-md mx-auto p-8">
    <h2 class="text-2xl font-bold mb-4">Upload EEG Data</h2>
    <form @submit.prevent="onUpload">
      <input ref="fileInput" @change="onFileChange" class="w-full mb-4 border border-gray-300 rounded px-3 py-2" type="file" accept=".csv,.txt" />
      <button :disabled="loading" class="w-full bg-blue-600 text-white rounded px-3 py-2 hover:bg-blue-700 transition disabled:opacity-50" type="submit">
        {{ loading ? 'Uploading...' : 'Upload' }}
      </button>
      <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
      <p v-if="success" class="text-green-600 mt-2">Upload successful!</p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const fileInput = ref<HTMLInputElement | null>(null);
const file = ref<File | null>(null);
const loading = ref(false);
const error = ref('');
const success = ref(false);

const onFileChange = () => {
  if (fileInput.value && fileInput.value.files?.length) {
    file.value = fileInput.value.files[0];
  }
};

const onUpload = async () => {
  error.value = '';
  success.value = false;
  if (!file.value) {
    error.value = 'Please select a file.';
    return;
  }
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('eeg', file.value);
    await axios.post('http://localhost:8000/api/eeg/upload', formData);
    success.value = true;
    file.value = null;
    if (fileInput.value) fileInput.value.value = '';
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Upload failed';
  } finally {
    loading.value = false;
  }
};
</script> 