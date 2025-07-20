<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <header class="bg-white shadow">
            <div class="w-full h-20 px-4 flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Bottone sidebar toggle completamente a sinistra -->
                    <button @click="toggleSidebar" class="focus:outline-none mr-6">
                        <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle"
                            class="h-6 w-6" />
                    </button>
                    <!-- Logo distanziato dal bottone -->
                    <div class="flex items-center">
                        <img src="/img/logo.png" alt="Logo" class="h-16 w-auto" />
                        <h1 class="text-xl font-bold text-gray-800 ml-3">Skiddies - Studente</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="logout" class="text-red-600 hover:underline">Esci</button>
                    <div class="relative" @mouseleave="handleDropdownMouseLeave">
                        <img :src="userImageUrl" alt="User Avatar"
                            class="h-10 w-10 rounded-full border-2 border-indigo-400 cursor-pointer object-cover"
                            @click="toggleDropdown" />
                        <div v-if="showDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                            @mousedown.stop>
                            <button @mousedown.stop.prevent="goToProfile"
                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50">Personalizza
                                profilo</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <div class="flex min-h-screen bg-gray-100">
            <!-- Sidebar dinamica con transizione fluida -->
            <aside class="bg-white shadow-md transition-width duration-300 ease-in-out overflow-hidden"
                :style="{ width: isSidebarOpen ? '256px' : '80px' }">
                <nav class="py-6">
                    <div class="space-y-2">
                        <button @click="goTo('student')"
                            class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                            <div class="min-w-[80px] flex justify-center items-center">
                                <img src="/img/home.png" alt="Home" class="h-5 w-5" />
                            </div>
                            <span class="whitespace-nowrap font-semibold transition-opacity duration-300"
                                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                                Dashboard
                            </span>
                        </button>

                        <button @click="goTo('courses')"
                            class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                            <div class="min-w-[80px] flex justify-center items-center">
                                <img src="/img/book.png" alt="Corsi" class="h-5 w-5" />
                            </div>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                                Corsi
                            </span>
                        </button>

                        <button @click="goTo('messages')"
                            class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                            <div class="min-w-[80px] flex justify-center items-center">
                                <img src="/img/message.png" alt="Messaggi" class="h-5 w-5" />
                            </div>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                                Messaggi
                            </span>
                        </button>

                        <button @click="goTo('playlist')"
                            class="w-full flex items-center py-2 rounded hover:bg-gray-100 text-gray-700">
                            <div class="min-w-[80px] flex justify-center items-center">
                                <img src="/img/playlist.png" alt="Playlist" class="h-5 w-5" />
                            </div>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                :class="isSidebarOpen ? 'opacity-100' : 'opacity-0'">
                                Playlist
                            </span>
                        </button>
                    </div>
                </nav>
            </aside>

            <div class="bg-white w-full flex flex-col gap-5 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]">
                <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4">
                    <div class="p-2 md:p-4">
                        <div class="w-full px-6 pb-8 mt-8 sm:max-w-xl sm:rounded-lg">
                            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Public Profile</h2>

                            <div v-if="loading" class="flex justify-center items-center py-10">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
                            </div>

                            <div v-else class="grid max-w-2xl mx-auto mt-8">
                                <div class="flex flex-col items-center space-y-5 sm:flex-row sm:space-y-0">

                                    <img class="object-cover w-40 h-40 p-1 rounded-full ring-2 ring-indigo-300 dark:ring-indigo-500"
                                        :src="userImageUrl" alt="Bordered avatar">

                                    <div class="flex flex-col space-y-5 sm:ml-8">

                                        <input type="file" accept="image/*" @change="onImageChange"
                                            class="py-3.5 px-7 text-base font-medium text-indigo-900 bg-white rounded-lg border border-indigo-200 hover:bg-indigo-100" />


                                        <button @click="removePicture"
                                            class="py-3.5 px-7 text-base font-medium text-indigo-900 bg-white border border-indigo-200 hover:bg-indigo-100 rounded-lg">
                                            Delete picture
                                        </button>
                                    </div>
                                </div>

                                <div class="items-center mt-8 sm:mt-14 text-[#202142]">
                                    <div
                                        class="flex flex-col items-center w-full mb-2 space-x-0 space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0 sm:mb-6">
                                        <div class="w-full">
                                            <label for="first_name"
                                                class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white">Nome</label>
                                            <input type="text" id="first_name" v-model="userProfile.name"
                                                class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                placeholder="Il tuo nome" required>
                                        </div>

                                        <div class="w-full">
                                            <label for="password"
                                                class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white">Password</label>
                                            <input type="password" id="password" v-model="userProfile.password"
                                                class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                                placeholder="Nuova password (lascia vuoto per non modificare)">
                                        </div>

                                    </div>

                                    <div class="mb-2 sm:mb-6">
                                        <label for="email"
                                            class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white">Email</label>
                                        <input type="email" id="email" v-model="userProfile.email"
                                            class="bg-indigo-50 border border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                            placeholder="your.email@mail.com" required>
                                    </div>

                                    <div class="mb-2 sm:mb-6">
                                        <label for="profession"
                                            class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white">Ruolo</label>
                                        <input type="text" id="profession" v-model="userProfile.role" disabled
                                            class="bg-indigo-50 border border-indigo-300 text-gray-500 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 "
                                            placeholder="Il tuo ruolo" required>
                                    </div>

                                    <div class="flex justify-end">
                                        <button @click="saveProfile" :disabled="loading"
                                            class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg 
                                            text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 disabled:opacity-50">
                                            {{ loading ? 'Salvataggio...' : 'Salva' }}
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-gray-50 text-center py-4 text-gray-500 text-sm mt-auto">
            2025 Skiddies | Made By Diana Tichy & Sofia Ricci
        </footer>
    </div>

</template>

<script>
export default {
    data() {
        return {
            userImageUrl: localStorage.getItem('userImageUrl') || 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGZhY2V8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60',
            showDropdown: false,
            isSidebarOpen: true,
            userProfile: {
                name: '',
                email: '',
                password: '',
                role: ''  // Inizializzato vuoto, verrà popolato dal backend
            },
            loading: false,
            userType: null // Aggiungiamo una variabile per tenere traccia del tipo di utente (studente o tutor)
        };
    },
    methods: {
        // Gli altri metodi rimangono invariati
        
        async fetchUserProfile() {
    try {
        this.loading = true;
        
        const response = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
            method: 'GET',
            credentials: 'include'
        });
        
        if (!response.ok) {
            throw new Error('Errore nel caricamento del profilo');
        }
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Dati profilo ricevuti:', data);
            this.userProfile.name = data.name;
            this.userProfile.email = data.email;
            this.userType = data.userType; // Salva il tipo di utente
            
            // Imposta il ruolo se disponibile
            if (data.role) {
                this.userProfile.role = data.role;
            } else if (this.userType) {
                // Se il ruolo non è disponibile ma abbiamo il tipo di utente, lo usiamo
                this.userProfile.role = this.userType === 'tutor' ? 'tutor' : 'studente';
            }
            
            // Aggiorna l'immagine del profilo se disponibile
            if (data.image) {
                this.userImageUrl = `http://localhost/skiddies/backend/uploads/profile_images/${data.image}`;
            }
        } else {
            console.error('Errore nel caricamento del profilo:', data.error);
        }
    } catch (error) {
        console.error('Errore nella richiesta:', error);
    } finally {
        this.loading = false;
    }
}

        
        // Gli altri metodi rimangono invariati
    },
    mounted() {
        if (this.$store && this.$store.state.userImageUrl) {
            this.userImageUrl = this.$store.state.userImageUrl;
        }
        this.isSidebarOpen = true;
        
        // Carica i dati del profilo quando il componente viene montato
        this.fetchUserProfile();
    }
}
</script>