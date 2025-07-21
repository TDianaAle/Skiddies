<template>
  <aside
    class="bg-white shadow-md transition-width duration-300 ease-in-out overflow-hidden relative"
    :style="{ width: isSidebarOpen ? '256px' : '80px' }"
  >
    <!-- Toggle Button -->
    <button
      @click="$emit('toggle-sidebar')"
      class="absolute right-0 top-4 transform translate-x-1/2 bg-white p-1 rounded-full shadow focus:outline-none"
    >
      <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle" class="h-6 w-6" />
    </button>

    <!-- Navigation -->
    <nav class="space-y-2 text-sm mt-12">
      <button @click="$emit('navigate', 'tutor')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/home.png" alt="Home" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
          Dashboard
        </span>
      </button>
    </nav>
  </aside>
</template>

<script setup>
defineProps({
  isSidebarOpen: Boolean
})

defineEmits(['toggle-sidebar', 'navigate'])
</script>
