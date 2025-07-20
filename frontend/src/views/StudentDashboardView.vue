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
                <h1 class="text-xl font-bold text-gray-800 ml-3">Skiddies - Studente</h1>
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
                <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap font-semibold transition-opacity duration-300">
                    Dashboard
                </span>
                </button>

                <button @click="goTo('playlist')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                <div class="min-w-[80px] flex justify-center items-center">
                    <!-- Cambia l'immagine, es. un'icona playlist/video -->
                    <img src="/img/playlist.png" alt="Playlist" class="h-5 w-5" />
                </div>
                <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap transition-opacity duration-300">
                    Playlist
                </span>
                </button>


                <button @click="goTo('messages')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                <div class="min-w-[80px] flex justify-center items-center">
                    <img src="/img/message.png" alt="Messaggi" class="h-5 w-5" />
                </div>
                <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap transition-opacity duration-300">
                    Messaggi
                </span>
                </button>

                <button @click="goTo('playlist')" class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                <div class="min-w-[80px] flex justify-center items-center">
                    <img src="/img/playlist.png" alt="Playlist" class="h-5 w-5" />
                </div>
                <span :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'" class="whitespace-nowrap transition-opacity duration-300">
                    Playlist
                </span>
                </button>
            </div>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <main class="py-8 px-6 max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Benvenuto nella tua area studente!</h2>

            <!-- Funzionalità -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">I tuoi corsi</h3>
                <p class="text-gray-600">Visualizza i corsi a cui sei iscritto.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Messaggi</h3>
                <p class="text-gray-600">Controlla messaggi e comunicazioni.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Profilo</h3>
                <p class="text-gray-600">Aggiorna le tue informazioni personali.</p>
                </div>
            </section>

            <!-- Filtro -->
            <div class="mt-10 max-w-xs">
                <label class="block text-gray-700 font-medium mb-1">Filtra per categoria:</label>
                <select v-model="selectedCategory"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300">
                <option value="">Tutte le categorie</option>
                <option value="informatica">Informatica</option>
                <option value="matematica">Matematica</option>
                <option value="economia">Economia</option>
                </select>
            </div>

            <!-- Video -->
            <section class="mt-10">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Video disponibili</h2>
                <div v-if="filteredVideos.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="video in filteredVideos" :key="video.id" class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="font-bold text-lg mb-1 truncate">{{ video.title }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                    {{ video.category }} • Tutor: <span class="font-medium">{{ video.tutor_name }}</span>
                    </p>
                    <video controls :src="`http://localhost/skiddies/backend/${video.file_path}`" class="w-full rounded-lg mb-3 shadow" />

                    <div class="flex flex-wrap gap-2 mb-3">
                    <button
                        @click="addToPlaylist(video.id)"
                        :disabled="playlistVideos.has(video.id)"
                        class="text-sm px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600 disabled:opacity-50"
                    >
                        {{ playlistVideos.has(video.id) ? '✔ Playlist' : 'Aggiungi alla Playlist' }}
                    </button>

                    <button
                        @click="likeVideo(video.id)"
                        :disabled="likedVideos.has(video.id)"
                        class="text-sm px-3 py-1 rounded bg-pink-500 text-white hover:bg-pink-600 disabled:opacity-50"
                    >
                        {{ likedVideos.has(video.id) ? '❤️ Liked' : 'Like ❤️' }}
                    </button>
                    </div>

                    <!-- ✅ Commenti corretti -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lascia un commento</label>
                    <textarea
                        v-model="commentInputs[video.id]"
                        rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring focus:ring-blue-300"
                        placeholder="Scrivi un commento..."
                    ></textarea>
                    <button
                        @click="submitComment(video.id)"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm"
                    >
                        Invia commento
                    </button>
                    </div>
                </div>
                </div>
                <p v-else class="text-gray-600 mt-4">Nessun video trovato per questa categoria.</p>
            </section>
            </main>

            <!-- Footer -->
            <footer class="bg-gray-50 text-center py-4 text-gray-500 text-sm mt-auto">
            2025 Skiddies | Made By Diana Tichy & Sofia Ricci
            </footer>
        </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const userImageUrl = ref(localStorage.getItem('userImageUrl') || 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80')
const showDropdown = ref(false)
const isSidebarOpen = ref(true)

const videos = ref([])
const selectedCategory = ref('')
const commentInputs = ref({})
const likedVideos = ref(new Set())
const playlistVideos = ref(new Set())


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

const filteredVideos = computed(() => {
    if (!selectedCategory.value) return videos.value
    return videos.value.filter((video) => video.category === selectedCategory.value)
})

onMounted(() => {
    fetchVideos()
    window.addEventListener('storage', () => {
        userImageUrl.value = localStorage.getItem('userImageUrl') || userImageUrl.value
    })
    fetchPlaylist()
})


async function fetchVideos() {
    try {
        // Carica tutti i video
        const res = await fetch('http://localhost/skiddies/backend/api/get_all_videos.php', {
        credentials: 'include'
        });
        
        // Verifica se la risposta è valida
        if (!res.ok) {
        const text = await res.text();
        console.error('Errore nella risposta API:', text);
        throw new Error(`Errore HTTP: ${res.status}`);
        }
        
        const result = await res.json();
        videos.value = result.videos || [];
        
        try {
        // Carica i video già nella playlist
        const playlistRes = await fetch('http://localhost/skiddies/backend/api/get_playlist.php', {
            credentials: 'include'
        });
        
        // Verifica se la risposta è valida
        if (!playlistRes.ok) {
            const text = await playlistRes.text();
            console.error('Errore nella risposta API playlist:', text);
            return; // Continua senza i dati della playlist
        }
        
        const playlistResult = await playlistRes.json();
        
        if (playlistResult.success) {
            // Aggiorna il set dei video nella playlist
            playlistResult.videos.forEach(video => {
            playlistVideos.value.add(video.id);
            });
        }
        } catch (playlistError) {
        console.error('Errore nel caricamento della playlist:', playlistError);
        // Continua senza i dati della playlist
        }
    } catch (error) {
        console.error('Errore nel caricamento dei video:', error);
    }
}

async function fetchPlaylist() {
    try {
        const res = await fetch('http://localhost/skiddies/backend/api/get_playlist.php', {
        credentials: 'include'
        });
        const result = await res.json();
        if (result.success) {
        // Riempie il Set con gli ID video
        playlistVideos.value = new Set(result.playlist.map(v => v.video_id));
        } else {
        console.error('Errore nel caricamento della playlist:', result.error);
        }
    } catch (err) {
        console.error('Errore nella richiesta:', err);
    }
}

async function addToPlaylist(videoId) {
    try {
        const res = await fetch('http://localhost/skiddies/backend/api/add_to_playlist.php', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ video_id: videoId })
        });

        const result = await res.json();
        if (result.success) {
        playlistVideos.value.add(videoId);
        } else {
        alert(result.error || 'Errore durante l\'aggiunta');
        }
    } catch (err) {
        console.error('Errore:', err);
        alert('Errore di rete');
    }
}


async function likeVideo(videoId) {
    const res = await fetch('http://localhost/skiddies/backend/api/like_video.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ video_id: videoId })
    })
    const result = await res.json()
    if (result.success) {
        likedVideos.value.add(videoId)
    }
}

async function submitComment(videoId) {
    const comment = commentInputs.value[videoId]?.trim()
    if (!comment) return

    const res = await fetch('http://localhost/skiddies/backend/api/add_comment.php', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
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

function logout() {
    fetch('http://localhost/skiddies/backend/api/logout.php', {
        method: 'POST',
        credentials: 'include'
    }).then(() => {
        router.push('/login')
    })
}
</script>
