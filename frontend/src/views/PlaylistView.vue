<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <HeaderStudent :userImageUrl="userImageUrl" :showDropdown="showDropdown" @logout="logout"
      @toggle-dropdown="toggleDropdown" @leave-dropdown="handleDropdownMouseLeave" @go-profile="goToProfile"
      @image-error="handleImageError" />

    <div class="flex min-h-screen bg-gray-100">
      <!-- Sidebar -->
      <SidebarStudent :isSidebarOpen="isSidebarOpen" @toggle-sidebar="toggleSidebar" @navigate="goTo" />


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
          <div v-for="video in playlistVideos" :key="video.id"
            class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-1 truncate">{{ video.title }}</h3>
            <p class="text-sm text-gray-600 mb-2">
              {{ video.category }} • Tutor: <span class="font-medium">{{ video.tutor_name }}</span>
            </p>
            <video controls :src="`http://localhost/skiddies/backend/${video.file_path}`"
              class="w-full rounded-lg mb-3 shadow" />

            <div class="flex justify-between items-center">
              <span class="text-pink-500 flex items-center gap-1">
                <span>❤️</span> {{ video.likes }}
              </span>
              <button @click="removeFromPlaylist(video.video_id)"
                class="text-sm px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600">
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
import HeaderStudent from './HeaderStudentView.vue'
import SidebarStudent from './SidebarStudentView.vue';

const router = useRouter()
const userImageUrl = ref('/img/user.png') // Inizia sempre con l'immagine di default
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
  router.push('/studentPersonalization')
}

function handleDropdownMouseLeave() {
  setTimeout(() => {
    showDropdown.value = false
  }, 200)
}

function handleImageError() {
  userImageUrl.value = '/img/user.png'
  localStorage.setItem('userImageUrl', '/img/user.png')
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

async function fetchUserProfile() {
  try {
    const response = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
      method: 'GET',
      credentials: 'include'
    });

    if (!response.ok) {
      console.error('Errore nel caricamento del profilo utente');
      return;
    }

    const data = await response.json();

    if (data.success && data.image && data.image !== '') {
      const imageUrl = `http://localhost/skiddies/backend/uploads/profile_images/${data.image}`;
      const img = new Image();
      img.onload = () => {
        userImageUrl.value = imageUrl;
        localStorage.setItem('userImageUrl', imageUrl);
      };
      img.onerror = () => {
        console.warn('Immagine profilo non trovata:', data.image);
        userImageUrl.value = '/img/user.png';
        localStorage.setItem('userImageUrl', '/img/user.png');
      };
      img.src = imageUrl;
    } else {
      userImageUrl.value = '/img/user.png';
      localStorage.setItem('userImageUrl', '/img/user.png');
    }
  } catch (error) {
    console.error('Errore nel caricamento profilo utente:', error);
    userImageUrl.value = '/img/user.png';
    localStorage.setItem('userImageUrl', '/img/user.png');
  }
}

onMounted(() => {
  fetchPlaylist()
  fetchUserProfile() // Carica il profilo utente inclusa l'immagine
})
</script>
