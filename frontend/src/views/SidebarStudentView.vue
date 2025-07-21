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
      <!-- Bottone Messaggi -->
        <div class="relative">
          <!-- Bottone Messaggi -->
          <button @click="toggleMessageTooltip"
                  class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700 relative">
            <div class="min-w-[80px] flex justify-center items-center">
              <img src="/img/message.png" alt="Messaggi" class="h-5 w-5" />
            </div>
            <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'"
                  class="whitespace-nowrap transition-opacity duration-300">
              Messaggi
            </span>
          </button>

          <!-- Tooltip -->
          <div v-if="showMessageTooltip"
              class="absolute top-0 right-0 mt-[-20px] translate-y-[-100%] bg-white border border-gray-300 rounded-lg shadow px-4 py-2 text-sm text-gray-700 flex items-center space-x-2">
            <span>📭 Non ci sono ancora messaggi!</span>
          </div>
        </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from 'vue'

// Props ed eventi
defineProps({
  isSidebarOpen: Boolean
})
defineEmits(['toggle-sidebar', 'navigate'])

// Stato reattivo
const showMessageTooltip = ref(false)

// Metodo per mostrare tooltip temporaneo
function toggleMessageTooltip() {
  showMessageTooltip.value = true

  // Nasconde dopo 2 secondi
  setTimeout(() => {
    showMessageTooltip.value = false
  }, 2000)
}
</script>