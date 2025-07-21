<template>
  <aside
    class="bg-white shadow-md transition-width duration-300 ease-in-out overflow-hidden mt-4"
    :style="{ width: isSidebarOpen ? '256px' : '80px' }"
  >
    <nav class="space-y-2 text-sm">
      <!-- Pulsante Toggle Sidebar -->
      <button @click="$emit('toggle-sidebar')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700 focus:outline-none">
        <div class="min-w-[80px] flex justify-center items-center">
          <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
          <!--qui del testo se si preferisce -->
        </span>
      </button>

      <!-- Dashboard -->
      <button @click="$emit('navigate', 'tutor')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/home.png" alt="Home" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
          Dashboard
        </span>
      </button>

      <!-- Video -->
      <button @click="handleVideoClick" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
        <div class="min-w-[80px] flex justify-center items-center">
          <img src="/img/video.png" alt="Video" class="h-5 w-5" />
        </div>
        <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
          Video
        </span>
      </button>

      <!-- Messaggi -->
      <div class="relative">
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

        <!-- Tooltip Messaggi -->
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

defineProps({
  isSidebarOpen: Boolean
})
defineEmits(['toggle-sidebar', 'navigate'])

const showMessageTooltip = ref(false)

function toggleMessageTooltip() {
  showMessageTooltip.value = true
  setTimeout(() => {
    showMessageTooltip.value = false
  }, 2000)
}

function scrollToVideos() {
  const section = document.getElementById('video-section')
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' })
  }
}

function handleVideoClick() {
  const section = document.getElementById('video-section')
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' })
  }
  emit('navigate', 'video')
}

</script>
