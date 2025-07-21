<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <header class="bg-white shadow">
      <div class="w-full h-20 px-4 flex items-center justify-between">
        <div class="flex items-center">
          <button @click="toggleSidebar" class="focus:outline-none mr-6">
            <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle" class="h-6 w-6" />
          </button>
          <div class="flex items-center">
            <img src="/img/logo.png" alt="Logo" class="h-16 w-auto" />
            <h1 class="text-xl font-bold text-gray-800 ml-3">Skiddies - La tua Playlist</h1>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <button @click="logout" class="text-red-600 hover:underline">Esci</button>
          <div class="relative" @mouseleave="handleDropdownMouseLeave">
            <img
              :src="userImageUrl"
              alt="User Avatar"
              class="h-10 w-10 rounded-full border-2 border-indigo-400 cursor-pointer object-cover"
              @click="toggleDropdown"
            />
            <div
              v-if="showDropdown"
              class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
              @mousedown.stop
            >
              <button
                @mousedown.stop.prevent="goToProfile"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50"
              >
                Personalizza profilo
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="flex min-h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside
        class="bg-white shadow-md transition-width duration-300 ease-in-out overflow-hidden"
        :style="{ width: isSidebarOpen ? '256px' : '80px' }"
      >
        <nav class="py-6">
          <div class="space-y-2">
            <button @click="goTo('student')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
              <div class="min-w-[80px] flex justify-center items-center">
                <img src="/img/home.png" alt="Home" class="h-5 w-5" />
              </div>
              <span class="whitespace-nowrap font-semibold transition-opacity duration-300"
                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                Dashboard
              </span>
            </button>

            <button @click="goTo('playlist')" class="w-full flex items-center py-2 bg-indigo-100 rounded text-indigo-700">
              <div class="min-w-[80px] flex justify-center items-center">
                <img src="/img/playlist.png" alt="Playlist" class="h-5 w-5" />
              </div>
              <span class="whitespace-nowrap font-semibold transition-opacity duration-300"
                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                Playlist
              </span>
            </button>
          </div>
        </nav>
      </aside>

      <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">La tua Playlist</h1>
        
        <div v-if="loading" class="flex justify-center items-center py-10">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
        </div>
        
        <div v-else-if="playlistVideos.length === 0" class="bg-white p-6 rounded-lg shadow-md text-center">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">La tua playlist è vuota</h2>
          <p class="text-gray-600 mb-4">Aggiungi video alla tua playlist dalla dashboard</p>
          <button @click="goTo('student')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Vai alla dashboard
          </button>
        </div>
        
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="video in playlistVideos" :key="video.id" class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-1 truncate">{{ video.title }}</h3>
            <p class="text-sm text-gray-600 mb-2">
              {{ video.category }} • Tutor: <span class="font-medium">{{ video.tutor_name }}</span>
            </p>
            <video controls :src="`http://localhost/skiddies/backend/${video.file_path}`" class="w-full rounded-lg mb-3 shadow" />
            
            <div class="flex justify-between items-center">
              <span class="text-pink-500 flex items-center gap-1">
                <span>❤️</span> {{ video.likes }}
              </span>
              <button 
                @click="removeFromPlaylist(video.video_id)" 
                class="text-sm px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600"
              >
                Rimuovi
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-50 text-center py-4 text-gray-500 text-sm mt-auto">
      2025 Skiddies | Made By Diana Tichy & Sofia Ricci
    </footer>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const userImageUrl = ref(localStorage.getItem('userImageUrl') || 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80')
const showDropdown = ref(false)
const isSidebarOpen = ref(true)
const playlistVideos = ref([])
const loading = ref(true)

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value
}

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function goToProfile() {
  showDropdown.value = false
  router.push('/personalization')
}

function handleDropdownMouseLeave() {
  setTimeout(() => {
    showDropdown.value = false
  }, 200)
}

function goTo(route) {
  router.push(`/${route}`)
}

function logout() {
  fetch('http://localhost/skiddies/backend/api/logout.php', {
    method: 'POST',
    credentials: 'include'
  }).then(() => {
    router.push('/login')
  })
}

async function fetchPlaylist() {
  try {
    loading.value = true;
    const res = await fetch('http://localhost/skiddies/backend/api/get_playlist.php', {
      credentials: 'include'
    });
    
    // Verifica se la risposta è valida
    if (!res.ok) {
      const text = await res.text();
      console.error('Errore nella risposta API:', text);
      throw new Error(`Errore HTTP: ${res.status}`);
    }
    
    const result = await res.json();
    if (result.success) {
      playlistVideos.value = result.playlist || [];

    } else {
      console.error('Errore nel caricamento della playlist:', result.error);
    }
  } catch (error) {
    console.error('Errore nella richiesta:', error);
  } finally {
    loading.value = false;
  }
}

async function removeFromPlaylist(videoId) {
  console.log('Eliminazione video ID:', videoId); // Debug

  try {
    const res = await fetch('http://localhost/skiddies/backend/api/remove_from_playlist.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ video_id: videoId })
    });

    const text = await res.text();
    try {
      const result = JSON.parse(text);

      if (result.success) {
        playlistVideos.value = playlistVideos.value.filter(video => video.video_id !== videoId);
      } else {
        console.error('Errore nella risposta API:', result);
      }
    } catch (err) {
      console.error('Risposta non JSON:', text);
    }
  } catch (error) {
    console.error('Errore nella richiesta:', error);
  }
}


onMounted(() => {
  fetchPlaylist()
  window.addEventListener('storage', () => {
    userImageUrl.value = localStorage.getItem('userImageUrl') || userImageUrl.value
  })
})
</script>
