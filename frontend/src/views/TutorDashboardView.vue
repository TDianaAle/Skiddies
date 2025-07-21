<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Header -->
    <HeaderTutor
      :userImageUrl="userImageUrl"
      :showDropdown="showDropdown"
      @logout="logout"
      @toggle-dropdown="toggleDropdown"
      @leave-dropdown="handleDropdownMouseLeave"
      @go-profile="goToProfile"
      @image-error="handleImageError"
    />

    <div class="flex min-h-screen">
      <SidebarTutor
        :isSidebarOpen="isSidebarOpen"
        @toggle-sidebar="toggleSidebar"
        @navigate="goTo"
      />

      <!-- Main Content -->
      <main 
        class="flex-1 transition-all duration-300 ease-in-out justify-center"
        :class="isSidebarOpen ? 'ml-0' : 'ml-20'"
        role="main"
        aria-label="Dashboard principale del tutor"
      >
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
          <!-- Welcome Section -->
          <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">
              Benvenuto nella tua Dashboard
            </h1>
            <p class="text-gray-600 text-lg">Gestisci i tuoi video educativi e monitora l'engagement degli studenti</p>
          </div>

          <!-- Upload Video Section -->
          <section 
            class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-2xl shadow-lg border border-white/20 max-w-2xl mx-auto"
            aria-labelledby="upload-heading"
          >
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
              </div>
              <h2 id="upload-heading" class="text-2xl font-bold text-gray-800">Carica un nuovo video</h2>
            </div>
            
            <form @submit.prevent="uploadVideo" class="space-y-6">
              <div class="space-y-2">
                <label for="video-title" class="block text-sm font-semibold text-gray-700">
                  Titolo del video *
                </label>
                <input
                  id="video-title"
                  v-model="newVideo.title"
                  type="text"
                  placeholder="Inserisci il titolo del video"
                  required
                  aria-describedby="title-error"
                  class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-gray-800 placeholder-gray-400"
                />
              </div>
              
              <div class="space-y-2">
                <label for="video-category" class="block text-sm font-semibold text-gray-700">
                  Categoria *
                </label>
                <select 
                  id="video-category"
                  v-model="newVideo.category" 
                  required 
                  aria-describedby="category-error"
                  class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-gray-800 bg-white"
                >
                  <option disabled value="">Seleziona una categoria</option>
                  <option value="matematica">📐 Matematica</option>
                  <option value="informatica">💻 Informatica</option>
                  <option value="economia">📊 Economia</option>
                </select>
              </div>
              
              <div class="space-y-2">
                <label for="video-file" class="block text-sm font-semibold text-gray-700">
                  File video *
                </label>
                <div class="relative">
                  <input 
                    id="video-file"
                    type="file" 
                    accept="video/*" 
                    @change="handleFileChange" 
                    required 
                    aria-describedby="file-error"
                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                  />
                </div>
              </div>
              
              <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-6 py-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!newVideo.title || !newVideo.category || !videoFile"
              >
                <span class="flex items-center justify-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                  Carica Video
                </span>
              </button>
            </form>
          </section>

          <!-- Videos Grid Section -->
          <section aria-labelledby="videos-heading">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 rounded-full">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                </div>
                <h2 id="videos-heading" class="text-2xl font-bold text-gray-800">I tuoi video</h2>
              </div>
              <div class="text-sm text-gray-500 bg-white/50 px-3 py-1 rounded-full">
                {{ videos.length }} video{{ videos.length !== 1 ? '' : '' }}
              </div>
            </div>
            
            <!-- Empty State -->
            <div 
              v-if="videos.length === 0" 
              class="text-center py-16 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20"
            >
              <div class="p-6 bg-gray-100 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-700 mb-2">Nessun video caricato</h3>
              <p class="text-gray-500 mb-6">Inizia caricando il tuo primo video educativo</p>
              <button 
                @click="scrollToUploadForm"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Carica il primo video
              </button>
            </div>

            <!-- Videos Grid -->
            <div 
              v-else
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
              role="grid"
              aria-label="Griglia dei video caricati"
            >
              <div
                v-for="video in videos"
                :key="video.id"
                role="gridcell"
                tabindex="0"
                class="group bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-blue-100"
                @click="toggleVideo(video.id)"
                @keydown.enter="toggleVideo(video.id)"
                @keydown.space.prevent="toggleVideo(video.id)"
                :aria-label="`Apri video: ${video.title}`"
              >
                <!-- Video Thumbnail -->
                <div class="relative overflow-hidden rounded-t-2xl">
                  <video
                    :src="`http://localhost/skiddies/backend/${video.file_path}`"
                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                    muted
                    preload="metadata"
                    playsinline
                    :aria-label="`Anteprima del video: ${video.title}`"
                  ></video>
                  <div class="absolute top-3 right-3 bg-black/50 text-white px-2 py-1 rounded-lg text-xs backdrop-blur-sm">
                    {{ getCategoryEmoji(video.category) }}
                  </div>
                  <!-- Play Overlay -->
                  <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                    <div class="bg-white/90 rounded-full p-4 transform scale-75 group-hover:scale-100 transition-transform duration-200">
                      <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 5v14l11-7z"/>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Video Info -->
                <div class="p-5 space-y-3">
                  <div class="flex items-start justify-between gap-2">
                    <h3 class="text-lg font-bold text-gray-800 truncate group-hover:text-blue-600 transition-colors">
                      {{ video.title }}
                    </h3>
                    <div class="flex items-center gap-1 text-red-500 shrink-0" :title="`${video.likes} mi piace`">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                      </svg>
                      <span class="text-sm font-semibold">{{ video.likes }}</span>
                    </div>
                  </div>
                  
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="bg-gray-100 px-3 py-1 rounded-full">
                      {{ formatCategoryName(video.category) }}
                    </span>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex items-center justify-between pt-2">
                    <button 
                      @click.stop="deleteVideo(video.id)"
                      class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 px-3 py-2 rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-red-200"
                      :aria-label="`Elimina video: ${video.title}`"
                      title="Elimina video"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Elimina
                    </button>
                    
                    <button 
                      @click.stop="toggleVideo(video.id)"
                      class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 px-3 py-2 rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-200"
                      :aria-label="`Visualizza dettagli video: ${video.title}`"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      Dettagli
                    </button>
                  </div>

                  <!-- Comments Preview -->
                  <div class="border-t pt-3 mt-3">
                    <div v-if="commentsMap[video.id] && commentsMap[video.id].length > 0" class="space-y-2">
                      <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ commentsMap[video.id].length }} commento{{ commentsMap[video.id].length !== 1 ? 'i' : '' }}
                      </div>
                      <div class="max-h-20 overflow-y-auto space-y-1">
                        <div
                          v-for="comment in commentsMap[video.id].slice(0, 2)"
                          :key="comment.id"
                          class="text-sm text-gray-600 bg-gray-50 p-2 rounded-lg"
                        >
                          <p class="truncate">{{ comment.comment }}</p>
                          <span class="text-xs text-gray-400">{{ formatDate(comment.date) }}</span>
                        </div>
                        <div v-if="commentsMap[video.id].length > 2" class="text-xs text-blue-600 font-medium">
                          +{{ commentsMap[video.id].length - 2 }} altri commenti
                        </div>
                      </div>
                    </div>
                    <div v-else class="text-sm text-gray-400 italic flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                      </svg>
                      Nessun commento ancora
                    </div>
                  </div>
                </div>
              </div>
            </div>

        <!-- Video Detail Modal -->
        <div
          v-if="expandedVideo"
          class="fixed inset-0 bg-black/70 backdrop-blur-sm flex justify-center items-center z-50 p-4"
          role="dialog"
          aria-modal="true"
          :aria-labelledby="`modal-title-${expandedVideo}`"
          @click.self="toggleVideo(null)"
        >
          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-y-auto relative animate-pulse"
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white/95 backdrop-blur-sm border-b border-gray-100 p-6 rounded-t-2xl">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h3 
                    :id="`modal-title-${expandedVideo}`"
                    class="text-2xl font-bold text-gray-800 mb-2 pr-8"
                  >
                    {{ getExpandedVideo?.title }}
                  </h3>
                  <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1 bg-gray-100 px-3 py-1 rounded-full">
                      {{ getCategoryEmoji(getExpandedVideo?.category) }}
                      {{ formatCategoryName(getExpandedVideo?.category) }}
                    </span>
                    <span class="flex items-center gap-1 text-red-500">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                      </svg>
                      {{ getExpandedVideo?.likes }} mi piace
                    </span>
                  </div>
                </div>
                <button
                  @click="toggleVideo(null)"
                  class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200"
                  aria-label="Chiudi modal"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-6">
              <!-- Video Player -->
              <div class="relative bg-gray-900 rounded-xl overflow-hidden">
                <video
                  v-if="getExpandedVideo"
                  :key="getExpandedVideo.id"
                  :src="`http://localhost/skiddies/backend/${getExpandedVideo.file_path}`"
                  controls
                  playsinline
                  class="w-full h-[60vh] object-contain"
                  :aria-label="`Riproduzione video: ${getExpandedVideo.title}`"
                ></video>
              </div>

              <!-- Comments Section -->
              <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                  <h4 class="text-lg font-semibold text-gray-800">
                    Commenti ({{ commentsMap[expandedVideo]?.length || 0 }})
                  </h4>
                </div>

                <div v-if="commentsMap[expandedVideo] && commentsMap[expandedVideo].length > 0" class="space-y-4 max-h-60 overflow-y-auto">
                  <div
                    v-for="comment in commentsMap[expandedVideo]"
                    :key="comment.id"
                    class="bg-white p-4 rounded-lg border border-gray-200"
                  >
                    <p class="text-gray-800 mb-2">{{ comment.comment }}</p>
                    <span class="text-xs text-gray-500">{{ formatDate(comment.date) }}</span>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                  <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                  <p class="text-lg font-medium mb-1">Nessun commento ancora</p>
                  <p class="text-sm">Questo video non ha ancora ricevuto commenti dagli studenti</p>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button 
                  @click="deleteVideo(getExpandedVideo?.id)"
                  class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors focus:outline-none focus:ring-4 focus:ring-red-100"
                  :aria-label="`Elimina definitivamente il video: ${getExpandedVideo?.title}`"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                  Elimina questo video
                </button>
                <button 
                  @click="toggleVideo(null)"
                  class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition-colors focus:outline-none focus:ring-4 focus:ring-gray-100"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Chiudi
                </button>
              </div>
            </div>
          </div>
        </div>
          </section>
        </div>
      </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white/80 backdrop-blur-sm border-t border-gray-200 text-center py-6 mt-12">
      <div class="container mx-auto px-4">
        <p class="text-gray-600 text-sm">
          © 2025 <span class="font-semibold text-blue-600">Skiddies</span> | 
          Made with ❤️ by <span class="font-medium">Diana Tichy</span> & <span class="font-medium">Sofia Ricci</span>
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import HeaderTutor from './HeaderTutorView.vue'
import SidebarTutor from './SidebarTutorView.vue'

const router = useRouter()
const sidebarOpen = ref(false)
const isSidebarOpen = ref(true)
const showDropdown = ref(false)
const userImageUrl = ref('/img/user.png')
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

// Helper functions for better UI
const getCategoryEmoji = (category) => {
  const emojis = {
    'matematica': '📐',
    'informatica': '💻',
    'economia': '📊'
  }
  return emojis[category] || '📚'
}

const formatCategoryName = (category) => {
  const names = {
    'matematica': 'Matematica',
    'informatica': 'Informatica',
    'economia': 'Economia'
  }
  return names[category] || category
}

const formatDate = (dateString) => {
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('it-IT', { 
      day: 'numeric', 
      month: 'short', 
      hour: '2-digit', 
      minute: '2-digit' 
    })
  } catch (error) {
    return dateString
  }
}

const scrollToUploadForm = () => {
  const uploadSection = document.querySelector('[aria-labelledby="upload-heading"]')
  if (uploadSection) {
    uploadSection.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const logout = () => {
  fetch('http://localhost/skiddies/backend/api/logout.php', {
    method: 'POST',
    credentials: 'include'
  }).then(() => {
    router.push('/login')
  })
}

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

const handleDropdownMouseLeave = () => {
  showDropdown.value = false
}

const goToProfile = () => {
  console.log('goToProfile chiamato - navigazione verso /tutorPersonalization')
  router.push('/tutorPersonalization')
}

const handleImageError = () => {
  console.warn('Errore immagine profilo rilevato, reset a default')
  userImageUrl.value = '/img/user.png'
}

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}

const goTo = (route) => {
  router.push(`/${route}`)
}

const loadProfile = async () => {
  try {
    const res = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
      credentials: 'include'
    })
    const data = await res.json()
    console.log('Dati profilo dashboard tutor:', data)
    if (data.success) {
      profileName.value = data.name
      console.log('Tipo utente:', data.userType)
      if (data.image && data.image !== '') {
        userImageUrl.value = `http://localhost/skiddies/backend/uploads/profile_images/${data.image}`
      } else {
        userImageUrl.value = '/img/user.png'
      }
      profileImageUrl.value = userImageUrl.value
      
      // Verifica che sia effettivamente un tutor
      if (data.userType !== 'tutor') {
        console.warn('Utente non è un tutor, reindirizzamento al login')
        router.push('/login')
        return
      }
    } else {
      console.error('Errore caricamento profilo:', data.error)
      router.push('/login')
    }
  } catch (err) {
    console.error('Errore caricamento profilo:', err)
    userImageUrl.value = '/img/user.png'
    router.push('/login')
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

const deleteVideo = async (videoId) => {
  if (!confirm('Sei sicuro di voler eliminare questo video? Questa azione è irreversibile e il video verrà rimosso anche per tutti gli studenti.')) {
    return
  }

  try {
    const res = await fetch('http://localhost/skiddies/backend/api/delete_video.php', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ video_id: videoId })
    })
    
    const result = await res.json()
    if (result.success) {
      alert('Video eliminato con successo!')
      
      // Se il video eliminato è quello attualmente espanso, chiudi il modal
      if (expandedVideo.value === videoId) {
        expandedVideo.value = null
      }
      
      // Rimuovi il video dalla lista locale per aggiornare immediatamente l'UI
      videos.value = videos.value.filter(video => video.id !== videoId)
      // Rimuovi anche i commenti dalla mappa locale
      if (commentsMap.value[videoId]) {
        delete commentsMap.value[videoId]
      }
    } else {
      alert('Errore durante l\'eliminazione: ' + result.error)
    }
  } catch (err) {
    console.error('Errore eliminazione video:', err)
    alert('Errore di rete durante l\'eliminazione del video')
  }
}

onMounted(() => {
  loadProfile()
  fetchMyVideos()
})
</script>


