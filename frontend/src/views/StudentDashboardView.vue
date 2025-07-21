<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <HeaderStudent
            :userImageUrl="userImageUrl"
            :showDropdown="showDropdown"
            @logout="logout"
            @toggle-dropdown="toggleDropdown"
            @leave-dropdown="handleDropdownMouseLeave"
            @go-profile="goToProfile"
            />

            <div class="flex min-h-screen bg-gray-100">
            <!-- Sidebar -->
            <SidebarStudent
            :isSidebarOpen="isSidebarOpen"
            @toggle-sidebar="toggleSidebar"
            @navigate="goTo"
        />

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <main class="py-8 px-6 max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Benvenuto nella tua area studente!</h2>

            <!-- Funzionalità -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold mb-2">La tua playlist</h3>
                    <p class="text-gray-600 mb-4">Visualizza le playlist a cui sei iscritto.</p>
                    <!-- Pulsante "Vai a Playlist" -->
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
                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition relative">
                <h3 class="text-lg font-semibold mb-2">Messaggi</h3>
                <p class="text-gray-600 mb-4">Controlla messaggi e comunicazioni.</p>

                <!-- Pulsante Messaggi -->
                <button @click="showMessageTooltip = !showMessageTooltip"
                        class="w-full flex items-center py-2 bg-yellow-100 rounded text-yellow-700 relative">
                    <div class="min-w-[80px] flex justify-center items-center">
                    <!-- Icona Font Awesome -->
                    <i class="fa-regular fa-message text-lg"></i>
                    </div>
                    <span class="whitespace-nowrap font-semibold">Messaggi</span>
                </button>
                <!-- Tooltip -->
                    <div v-if="showMessageTooltip"
                        class="absolute z-10 top-0 right-0 mt-[-20px] translate-y-[-100%] bg-white border border-gray-300 rounded-lg shadow px-4 py-2 text-sm text-gray-700 flex items-center space-x-2">
                        <span>📭 Non ci sono ancora messaggi!</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold mb-2">Profilo</h3>
                    <p class="text-gray-600 mb-4">Aggiorna le tue informazioni personali.</p>

                    <!-- Bottone Vai al profilo -->
                    <button @click="goToProfile"
                            class="w-full flex items-center py-2 bg-blue-100 rounded text-blue-700">
                        <div class="min-w-[80px] flex justify-center items-center">
                        <i class="fa-regular fa-user text-lg"></i>
                        </div>
                        <span class="whitespace-nowrap font-semibold">Profilo</span>
                    </button>
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
import HeaderStudent from './HeaderStudentView.vue'
import SidebarStudent from './SidebarStudentView.vue'

const router = useRouter()
const userImageUrl = ref('/img/user.png') // Inizia sempre con l'immagine di default
const showDropdown = ref(false)
const isSidebarOpen = ref(true)

const videos = ref([])
const selectedCategory = ref('')
const commentInputs = ref({})
const likedVideos = ref(new Set())
const playlistVideos = ref(new Set())
const showMessageTooltip = ref(false);

function toggleMessageTooltip() {
    showMessageTooltip.value = true

    // Nasconde il messaggio dopo 2 secondi
    setTimeout(() => {
        showMessageTooltip.value = false
    }, 2000)
}



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

function goTo(route) {
    router.push(`/${route}`)
}

const filteredVideos = computed(() => {
    if (!selectedCategory.value) return videos.value
    return videos.value.filter((video) => video.category === selectedCategory.value)
})

onMounted(() => {
    fetchVideos()
    fetchUserProfile() // Carica il profilo utente inclusa l'immagine
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
            // Verifica se l'immagine esiste
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
            // Utente senza immagine personalizzata
            userImageUrl.value = '/img/user.png';
            localStorage.setItem('userImageUrl', '/img/user.png');
        }
    } catch (error) {
        console.error('Errore nel caricamento profilo utente:', error);
        userImageUrl.value = '/img/user.png';
        localStorage.setItem('userImageUrl', '/img/user.png');
    }
}

function logout() {
    // Pulisci il localStorage
    localStorage.removeItem('userToken');
    localStorage.removeItem('userImageUrl');
    
    fetch('http://localhost/skiddies/backend/api/logout.php', {
        method: 'POST',
        credentials: 'include'
    }).then(() => {
        router.push('/login')
    })
}
</script>
