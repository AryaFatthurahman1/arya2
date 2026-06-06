<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div v-for="(stat, index) in stats" :key="index" class="card p-6 hover:scale-105 transition-transform duration-300">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-sm font-medium text-gray-500">{{ stat.title }}</h3>
          <p class="text-3xl font-bold gradient-text mt-2">{{ stat.value }}</p>
        </div>
        <div :class="`p-3 rounded-xl ${stat.colorClass}`">
          <i :class="`fa-solid ${stat.icon} text-xl ${stat.textClass}`"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Array,
    required: true,
  },
});
</script>
