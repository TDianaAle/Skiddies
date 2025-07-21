<template>
    <header class="bg-white shadow">
        <div class="w-full h-20 px-4 flex items-center justify-between">
            <div class="flex items-center">
                <img src="/img/logo.png" alt="Logo" class="h-16 w-auto" />
                <h1 class="text-xl font-bold text-gray-800 ml-3">Skiddies - Studente</h1>
            </div>

            <div class="flex items-center gap-4 pr-2">
                <button @click="$emit('logout')" class="text-red-600 hover:underline">Esci</button>

                <div class="relative" ref="dropdownRef">
                    <img 
            :src="userImageUrl" 
            alt="Profile Picture" 
            class="h-10 w-10 rounded-full border-2 border-indigo-400 cursor-pointer object-cover"
            @click="$emit('toggle-dropdown')"
            @error="handleImageError"
        />
                    <div v-if="showDropdown"
                        class="absolute right-2 mt-2 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                        @mousedown.stop>
                        <button @mousedown.stop.prevent="$emit('go-profile')"
                            class="block w-full text-left px-5 py-3 text-gray-700 hover:bg-indigo-100 rounded-md transition duration-150 ease-in-out">
                            Personalizza profilo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>



<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'


const props = defineProps({
    userImageUrl: String,
    showDropdown: Boolean
})

const dropdownRef = ref(null)

const localUserImageUrl = ref(props.userImageUrl || '/img/user.png')

watch(() => props.userImageUrl, (newValue) => {
    localUserImageUrl.value = newValue || '/img/user.png'
})

const handleImageError = (e) => {
    console.warn('Immagine profilo non trovata, uso immagine di default:', e.target.src);
    e.target.src = '/img/user.png';
    
    // Aggiorna anche il localStorage per evitare futuri errori
    if (props.userImageUrl !== '/img/user.png') {
        localStorage.setItem('userImageUrl', '/img/user.png');
        // Emetti un evento per informare il componente padre
        emit('image-error');
    }
}

const emit = defineEmits(['logout', 'toggle-dropdown', 'leave-dropdown', 'go-profile', 'image-error'])

// Chiudi dropdown se clicchi fuori
function handleClickOutside(event) {
  if (props.showDropdown && dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    emit('leave-dropdown')
  }
}

onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside)
})
onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleClickOutside)
})
</script>