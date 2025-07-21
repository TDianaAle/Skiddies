<template>
  <aside
    class="bg-white shadow-md transition-width duration-300 ease-in-out overflow-hidden"
    :style="{ width: isSidebarOpen ? '256px' : '80px' }"
  >
    <nav class="space-y-2 text-sm">
      <button @click="$emit('toggle-sidebar')" class="focus:outline-none mr-6">
        <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle" class="h-6 w-6" />
      </button>

      <button @click="$emit('navigate', 'student')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/home.png" alt="Home" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
          Dashboard
        </span>
      </button>

      <button @click="$emit('navigate', 'playlist')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/playlist.png" alt="Playlist" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap transition-opacity duration-300">
          Playlist
        </span>
      </button>

      <button @click="$emit('navigate', 'messages')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/message.png" alt="Messaggi" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap transition-opacity duration-300">
          Messaggi
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