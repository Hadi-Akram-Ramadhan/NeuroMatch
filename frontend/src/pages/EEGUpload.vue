<template>
  <div class="max-w-md mx-auto p-8">
    <h2 class="text-2xl font-bold mb-4">Upload EEG Data</h2>
    <!-- EEG upload form will go here -->
    <form>
      <input class="w-full mb-4 border border-gray-300 rounded px-3 py-2" type="file" accept=".csv,.txt" />
      <button class="w-full bg-blue-600 text-white rounded px-3 py-2 hover:bg-blue-700 transition" type="submit">Upload</button>
    </form>
  </div>
</template>

<script setup lang="ts">
</script> 