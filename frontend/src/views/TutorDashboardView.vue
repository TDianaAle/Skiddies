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

        <!-- Main content -->
        <main>
        <!-- Sezione carosello -->
        <section class="mb-10">
            <h2 class="text-xl font-semibold mb-4">I tuoi ultimi video</h2>
            <TutorVideoCarousel />
        </section>

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

        <!-- Lista video completa -->
        <section>
            <h2 class="text-xl font-semibold mb-4">Tutti i tuoi video</h2>
            <div v-if="videos.length === 0" class="text-gray-500">Nessun video caricato</div>
            <ul>
            <li v-for="video in videos" :key="video.id" class="mb-6 p-4 bg-white rounded shadow">
                <h3 class="font-semibold">{{ video.title }}</h3>
                <p class="text-sm text-gray-600">Categoria: {{ video.category }}</p>
                <video
                :src="`http://localhost/skiddies/backend/uploads/videos/${video.filename}`"
                controls
                class="w-full mt-2 rounded"
                />

                <!-- Commenti -->
                <div class="mt-4">
                <h4 class="font-semibold">Commenti:</h4>
                <ul>
                    <li
                    v-for="comment in video.comments"
                    :key="comment.id"
                    class="text-sm border-b py-1"
                    >
                    <strong>{{ comment.student_name }}:</strong> {{ comment.text }}
                    </li>
                </ul>
                </div>

                <!-- Likes -->
                <div class="mt-2 text-sm text-gray-700">👍 Likes: {{ video.likes_count }}</div>
            </li>
            </ul>
        </section>
        </main>
    </div>
</template>

<script setup>
import TutorVideoCarousel from '@/components/TutorVideoCarousel.vue'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const sidebarOpen = ref(false)
const profileImageUrl = ref('http://localhost/skiddies/backend/uploads/profile_images/default.jpg')
const profileName = ref('Tutor')
const videos = ref([])

const newVideo = ref({
    title: '',
    category: ''
})
const videoFile = ref(null)

function logout() {
    fetch('http://localhost/skiddies/backend/api/logout.php', {
        method: 'POST',
        credentials: 'include'
    }).then(() => {
        router.push('/login')
    })
}

async function loadProfile() {
    try {
        const res = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
        credentials: 'include'
        })
        const data = await res.json()
        if (data.success) {
        profileName.value = data.name
        profileImageUrl.value = `http://localhost/skiddies/backend/${data.image_path}`
        }
    } catch (error) {
        console.error('Errore caricamento profilo:', error)
    }
}

async function uploadProfileImage(e) {
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

async function updateProfileName() {
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

async function fetchMyVideos() {
    try {
        const res = await fetch('http://localhost/skiddies/backend/api/tutor_videos.php', {
        credentials: 'include'
        })
        const result = await res.json()
        if (result.success) {
        videos.value = result.videos
        }
    } catch (error) {
        console.error('Errore fetchMyVideos:', error)
    }
}

function handleFileChange(e) {
    videoFile.value = e.target.files[0]
}

async function uploadVideo() {
    if (!newVideo.value.title || !newVideo.value.category) {
        alert('Inserisci titolo e categoria');
        return;
    }
    if (!videoFile.value) {
        alert('Seleziona un file video');
        return;
    }

    const formData = new FormData();
    formData.append('title', newVideo.value.title);
    formData.append('category', newVideo.value.category);
    formData.append('video', videoFile.value);

    try {
        const res = await fetch('http://localhost/skiddies/backend/api/upload_video.php', {
            method: 'POST',
            credentials: 'include', // Mantiene la sessione attiva
            body: formData,
        });

        const result = await res.json();
        if (result.success) {
            alert('Video caricato con successo!');
            newVideo.value.title = '';
            newVideo.value.category = '';
            videoFile.value = null;

            await fetchMyVideos(); //Ricarica i video dopo upload
        } else {
            alert('Errore nel caricamento del video: ' + (result.error || 'Errore sconosciuto'));
        }
    } catch (error) {
        console.error('Errore upload video:', error);
        alert('Errore nella richiesta');
    }
}

onMounted(() => {
    loadProfile()
    fetchMyVideos()
})


</script>
const response = await fetch(...);
const data = await response.json();
console.log("Risposta:", data);
