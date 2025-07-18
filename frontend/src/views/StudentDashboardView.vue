<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-4">
            <img src="/img/logo.png" alt="Logo" class="h-12 w-auto" />
            <h1 class="text-xl font-bold text-gray-800">Skiddies - Studente</h1>
            </div>
            <button @click="logout" class="text-red-600 hover:underline">Esci</button>
        </div>
        </header>

        <!-- Main -->
        <main class="py-10 px-6 max-w-7xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Benvenuto nella tua area studente!</h2>

        <!-- Box Funzionalità -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2">I tuoi corsi</h3>
            <p class="text-gray-600">Visualizza i corsi a cui sei iscritto.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Messaggi</h3>
            <p class="text-gray-600">Controlla messaggi e comunicazioni.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Profilo</h3>
            <p class="text-gray-600">Aggiorna le tue informazioni personali.</p>
            </div>
        </div>

        <!-- Filtro per categoria -->
        <div class="mt-10 max-w-xs">
            <label class="block text-gray-700 font-medium mb-1">Filtra per categoria:</label>
            <select v-model="selectedCategory" class="w-full border px-4 py-2 rounded">
            <option value="">Tutte le categorie</option>
            <option value="informatica">Informatica</option>
            <option value="matematica">Matematica</option>
            <option value="economia">Economia</option>
            </select>
        </div>

        <!-- Video caricati -->
        <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Video disponibili</h2>

        <div v-if="filteredVideos.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
            v-for="video in filteredVideos"
            :key="video.id"
            class="bg-white shadow rounded-lg p-4"
            >
            <h3 class="font-bold text-lg mb-1">{{ video.title }}</h3>
            <p class="text-sm text-gray-600 mb-2">
                {{ video.category }} - Tutor: {{ video.tutor_name }}
            </p>
            <video
                controls
                :src="`http://localhost/skiddies/backend/${video.file_path}`"
                class="w-full rounded mb-2"
            />

            <!-- Bottoni -->
            <div class="flex space-x-4 mb-2">
                <button
                @click="addToPlaylist(video.id)"
                :disabled="playlistVideos.has(video.id)"
                class="text-sm bg-green-500 text-white px-3 py-1 rounded"
                >
                {{ playlistVideos.has(video.id) ? 'Aggiunto alla Playlist' : 'Aggiungi alla Playlist' }}
                </button>

                <button
                @click="likeVideo(video.id)"
                :disabled="likedVideos.has(video.id)"
                class="text-sm bg-pink-500 text-white px-3 py-1 rounded"
                >
                {{ likedVideos.has(video.id) ? 'Liked ❤️' : 'Like ❤️' }}
                </button>
            </div>

            <!-- Commenti -->
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lascia un commento</label>
                <textarea
                v-model="commentInputs[video.id]"
                rows="2"
                class="w-full border rounded px-3 py-2 mb-2"
                placeholder="Scrivi un commento..."
                ></textarea>
                <button
                @click="submitComment(video.id)"
                class="bg-blue-600 text-white px-4 py-1 rounded text-sm"
                >
                Invia commento
                </button>
            </div>
            </div>
        </div>

        <p v-else class="text-gray-600 mt-4">Nessun video trovato per questa categoria.</p>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 text-center py-6 text-gray-500 text-sm mt-10">
        © 2025 Skiddies. Tutti i diritti riservati.
        </footer>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Reactive data
const videos = ref([])
const selectedCategory = ref('')
const commentInputs = ref({})
const likedVideos = ref(new Set())
const playlistVideos = ref(new Set())

// Computed: filtro per categoria
const filteredVideos = computed(() => {
    if (!selectedCategory.value) return videos.value
    return videos.value.filter((video) => video.category === selectedCategory.value)
})

// Caricamento video al mount
onMounted(fetchVideos)

async function fetchVideos() {
    try {
        const res = await fetch('http://localhost/skiddies/backend/api/get_all_videos.php', {
        credentials: 'include',
        })
        const result = await res.json()
        videos.value = result.videos || []
    } catch (error) {
        console.error('Errore nel caricamento dei video:', error)
    }
}

// Logout
function logout() {
    fetch('http://localhost/skiddies/backend/api/logout.php', {
        method: 'POST',
        credentials: 'include',
    }).then(() => {
        router.push('/login')
    })
}

// Aggiungi alla playlist
async function addToPlaylist(videoId) {
    const res = await fetch('http://localhost/skiddies/backend/api/add_to_playlist.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ video_id: videoId }),
    })
    const result = await res.json()
    if (result.success) {
        playlistVideos.value.add(videoId)
    }
}

// Like al video
async function likeVideo(videoId) {
    const res = await fetch('http://localhost/skiddies/backend/api/like_video.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ video_id: videoId }),
    })
    const result = await res.json()
    if (result.success) {
        likedVideos.value.add(videoId)
    }
}

// Aggiungi commento
async function submitComment(videoId) {
    const comment = commentInputs.value[videoId]?.trim()
    if (!comment) return

    const res = await fetch('http://localhost/skiddies/backend/api/add_comment.php', {
        method: 'POST',
        credentials: 'include',
        headers: {
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({
        video_id: videoId,
        comment: comment
        })
    })

    const result = await res.json()
    if (result.success) {
        commentInputs.value[videoId] = ''
        console.log('Commento inviato con successo')
    } else {
        console.error('Errore:', result)
    }
}

</script>
