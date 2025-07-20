<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <!-- Header -->
    <header class="flex justify-between items-center mb-6 bg-white p-4 shadow">
      <div class="flex items-center space-x-4">
        <img
          :src="profileImageUrl"
          alt="Profilo"
          class="w-12 h-12 rounded-full cursor-pointer"
          @click="sidebarOpen = true"
        />
        <h1 class="text-xl font-bold text-gray-800">Benvenuto, {{ profileName }}</h1>
      </div>
      <button @click="logout" class="text-red-600 hover:underline">Esci</button>
    </header>

    <!-- Sidebar -->
    <aside
      v-if="sidebarOpen"
      class="fixed top-0 left-0 w-64 h-full bg-white shadow z-50 p-6"
      @mouseleave="sidebarOpen = false"
    >
      <div class="flex flex-col items-center">
        <img :src="profileImageUrl" alt="Profilo" class="w-24 h-24 rounded-full object-cover mb-4" />
        <h2 class="text-lg font-semibold mb-2">{{ profileName }}</h2>

        <input type="file" @change="uploadProfileImage" class="mb-4 text-sm" />
        <input
          v-model="profileName"
          @blur="updateProfileName"
          class="border p-1 w-full text-sm rounded mb-4"
          placeholder="Modifica nome"
        />

        <button @click="logout" class="mt-6 text-red-600 hover:underline text-sm">Esci</button>
      </div>
    </aside>

    <main>
      <!-- Upload Video -->
      <section class="mb-10 bg-white p-6 rounded shadow max-w-md">
        <h2 class="text-xl font-semibold mb-4">Carica un nuovo video</h2>
        <form @submit.prevent="uploadVideo" class="space-y-4">
          <input
            v-model="newVideo.title"
            type="text"
            placeholder="Titolo video"
            required
            class="w-full p-2 border rounded"
          />
          <select v-model="newVideo.category" required class="w-full p-2 border rounded">
            <option disabled value="">Seleziona categoria</option>
            <option value="matematica">Matematica</option>
            <option value="informatica">Informatica</option>
            <option value="economia">Economia</option>
          </select>
          <input type="file" accept="video/*" @change="handleFileChange" required />
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Carica
          </button>
        </form>
      </section>

      <!-- Grid video -->
      <section>
        <h2 class="text-xl font-semibold mb-4">Tutti i tuoi video</h2>
        <div v-if="videos.length === 0" class="text-gray-500">Nessun video caricato</div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="video in videos"
            :key="video.id"
            class="bg-white border rounded-lg shadow-sm hover:shadow-md transition cursor-pointer max-w-xs mx-auto"
            @click="toggleVideo(video.id)"
          >
            <video
              :src="`http://localhost/skiddies/backend/${video.file_path}`"
              class="w-full h-28 object-cover rounded-t"
              muted
              preload="metadata"
              playsinline
            ></video>
            <div class="p-4">
              <h3 class="text-blue-700 font-semibold truncate flex items-center gap-2">
                {{ video.title }}
                <span class="text-red-500" title="Like">❤️ {{ video.likes }}</span>
              </h3>
              <p class="text-sm text-gray-500">Categoria: {{ video.category }}</p>

              <!-- Commenti -->
              <div v-if="commentsMap[video.id] && commentsMap[video.id].length > 0" class="mt-3 border-t pt-2">
                <h4 class="font-semibold text-gray-700 mb-1">Commenti:</h4>
                <ul>
                  <li
                    v-for="comment in commentsMap[video.id]"
                    :key="comment.id"
                    class="text-sm text-gray-600 mb-1"
                  >
                    {{ comment.comment }} <span class="text-xs text-gray-400">({{ comment.date }})</span>
                  </li>
                </ul>
              </div>
              <div v-else class="mt-3 text-sm text-gray-400">Nessun commento</div>
            </div>
          </div>
        </div>

        <!-- Dettaglio video espanso -->
        <div
          v-if="expandedVideo"
          class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50 p-4"
          @click.self="toggleVideo(null)"
        >
          <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 relative">
            <button
              @click="toggleVideo(null)"
              class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-xl font-bold"
            >
              ✖
            </button>

            <h3 class="text-xl font-bold text-blue-700 mb-2">{{ getExpandedVideo?.title }}</h3>
            <p class="text-sm text-gray-600 mb-2">Categoria: {{ getExpandedVideo?.category }}</p>

            <video
              v-if="getExpandedVideo"
              :key="getExpandedVideo.id"
              :src="`http://localhost/skiddies/backend/${getExpandedVideo.file_path}`"
              controls
              playsinline
              class="w-full h-[50vh] object-contain rounded mb-4"
            ></video>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const sidebarOpen = ref(false)
const profileImageUrl = ref('http://localhost/skiddies/backend/uploads/profile_images/default.jpg')
const profileName = ref('Tutor')
const videos = ref([])
const commentsMap = ref({}) //per poter visualizzare i commenti

const newVideo = ref({ title: '', category: '' })
const videoFile = ref(null)
const expandedVideo = ref(null)

//aggiunto fetchComments


//nuova funzione per poter visualizzare i commenti sotto ogni video
const fetchComments = async (videoId) => {
  try {
    const res = await fetch(`http://localhost/skiddies/backend/api/get_comments.php?video_id=${videoId}`, {
      credentials: 'include'
    })
    if (!res.ok) throw new Error('Errore caricamento commenti')
    const data = await res.json()
    if (data.success) {
      commentsMap.value[videoId] = data.comments
    } else {
      commentsMap.value[videoId] = []
    }
  } catch (error) {
    console.error('fetchComments error:', error)
    commentsMap.value[videoId] = []
  }
}
const toggleVideo = (id) => {
  expandedVideo.value = id

  if (id && !commentsMap.value[id]) {
    fetchComments(id)
  }
}


const getExpandedVideo = computed(() =>
  videos.value.find((v) => v.id === expandedVideo.value)
)

const logout = () => {
  fetch('http://localhost/skiddies/backend/api/logout.php', {
    method: 'POST',
    credentials: 'include'
  }).then(() => {
    router.push('/login')
  })
}

const loadProfile = async () => {
  try {
    const res = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
      credentials: 'include'
    })
    const data = await res.json()
    if (data.success) {
      profileName.value = data.name
      profileImageUrl.value = `http://localhost/skiddies/backend/${data.image_path}`
    }
  } catch (err) {
    console.error('Errore caricamento profilo:', err)
  }
}

const uploadProfileImage = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('image', file)

  try {
    const res = await fetch('http://localhost/skiddies/backend/api/upload_profile_image.php', {
      method: 'POST',
      credentials: 'include',
      body: formData
    })
    const result = await res.json()
    if (result.success) {
      profileImageUrl.value = `http://localhost/skiddies/backend/${result.imageUrl}`
    } else {
      alert('Errore upload immagine')
    }
  } catch (err) {
    console.error('Errore:', err)
  }
}

const updateProfileName = async () => {
  try {
    const res = await fetch('http://localhost/skiddies/backend/api/update_profile_name.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name: profileName.value })
    })
    const result = await res.json()
    if (!result.success) alert('Errore aggiornamento nome')
  } catch (err) {
    console.error('Errore update nome:', err)
  }
}

const fetchMyVideos = async () => {
  try {
    const res = await fetch('http://localhost/skiddies/backend/api/tutor_videos.php', {
      credentials: 'include'
    })
    const text = await res.text()  // prendi la risposta raw come testo
    console.log('Risposta raw tutor_videos.php:', text)  // stampa cosa arriva

    // prova a fare il parse JSON solo dopo il log
    const result = JSON.parse(text)

    if (result.success) {
        videos.value = result.videos
        result.videos.forEach(video => fetchComments(video.id)) // qui chiama fetchComments per ogni video
      
    } else {
      console.error('Errore dal backend:', result.error)
    }
  } catch (err) {
    console.error('Errore fetch video:', err)
  }
}

const handleFileChange = (e) => {
  videoFile.value = e.target.files[0]
}

const uploadVideo = async () => {
  if (!newVideo.value.title || !newVideo.value.category) {
    alert('Inserisci titolo e categoria')
    return
  }
  if (!videoFile.value) {
    alert('Seleziona un file video')
    return
  }

  const formData = new FormData()
  formData.append('title', newVideo.value.title)
  formData.append('category', newVideo.value.category)
  formData.append('video', videoFile.value)

  try {
    const res = await fetch('http://localhost/skiddies/backend/api/upload_video.php', {
      method: 'POST',
      credentials: 'include',
      body: formData
    })
    const result = await res.json()
    if (result.success) {
      alert('Video caricato con successo!')
      newVideo.value = { title: '', category: '' }
      videoFile.value = null
      await fetchMyVideos()
    } else {
      alert('Errore caricamento video: ' + result.error)
    }
  } catch (err) {
    console.error('Errore upload:', err)
  }
}

onMounted(() => {
  loadProfile()
  fetchMyVideos()
})
</script>
