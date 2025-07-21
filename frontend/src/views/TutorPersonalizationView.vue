<template>
    <!-- Pagina di personalizzazione profilo per TUTOR -->
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <HeaderTutor
        :userImageUrl="userImageUrl"
        :showDropdown="showDropdown"
        @logout="logout"
        @toggle-dropdown="toggleDropdown"
        @leave-dropdown="handleDropdownMouseLeave"
        @go-profile="goToProfile"
        @image-error="handleImageError"
    />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <SidebarTutor
            :isSidebarOpen="isSidebarOpen"
            @toggle-sidebar="toggleSidebar"
            @navigate="goTo"
        />

            <div class="bg-white w-full flex flex-col gap-5 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]">
                <main class="w-full min-h-screen py-1 md:w-2/3 lg:w-3/4">
                    <div class="p-2 md:p-4">
                        <div class="w-full px-6 pb-8 mt-8 sm:max-w-xl sm:rounded-lg">
                            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Profilo Tutor</h2>

                            <div v-if="loading" class="flex justify-center items-center py-10">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
                            </div>

                            <div v-else class="grid max-w-2xl mx-auto mt-8">
                                <div class="flex flex-col items-center space-y-5 sm:flex-row sm:space-y-0">

                                    <img class="object-cover w-40 h-40 p-1 rounded-full ring-2 ring-indigo-300 dark:ring-indigo-500"
                                        :src="userImageUrl" 
                                        alt="Bordered avatar"
                                        @error="resetToDefaultImage">

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
import HeaderTutor from './HeaderTutorView.vue';
import SidebarTutor from './SidebarTutorView.vue';

export default {
    components: {
        HeaderTutor,
        SidebarTutor
    },
    data() {
        return {
            userImageUrl: '/img/user.png', // Sempre inizia con l'immagine di default
            showDropdown: false,
            isSidebarOpen: true,
            userProfile: {
                name: '',
                email: '',
                password: '',
                role: 'Tutor'  // Ruolo fisso per i tutor
            },
            loading: false,
            userType: 'tutor' // Questa pagina è solo per tutor
        };
    },
    methods: {
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        
        handleDropdownMouseLeave() {
            this.showDropdown = false;
        },
        
        handleImageError() {
            console.warn('Errore immagine profilo rilevato, reset a default');
            this.userImageUrl = '/img/user.png';
            localStorage.setItem('userImageUrl', '/img/user.png');
        },
        
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        
        goTo(route) {
            this.$router.push(`/${route}`);
        },
        
        goToProfile() {
            this.$router.push('/tutorPersonalization');
        },
        
        logout() {
            // Clear user data from localStorage
            localStorage.removeItem('userToken');
            localStorage.removeItem('userImageUrl');
            // Redirect to login
            this.$router.push('/login');
        },
        
        onImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validazione del file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Formato file non supportato. Usa JPG, PNG o GIF.');
                return;
            }
            
            // Controllo dimensione file (max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('Il file è troppo grande. Dimensione massima: 5MB.');
                return;
            }

            const formData = new FormData();
            formData.append('profile_image', file);

            // Mostra preview temporaneo
            const reader = new FileReader();
            reader.onload = (e) => {
                this.userImageUrl = e.target.result;
            };
            reader.readAsDataURL(file);

            fetch('http://localhost/skiddies/backend/api/upload_profile_image.php', {
                method: 'POST',
                credentials: 'include',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.userImageUrl = `http://localhost/skiddies/backend/uploads/profile_images/${data.filename}`;
                    localStorage.setItem('userImageUrl', this.userImageUrl);
                    alert('Immagine profilo aggiornata con successo!');
                } else {
                    alert('Errore nel caricamento dell\'immagine: ' + (data.error || 'Errore sconosciuto'));
                    // Ripristina l'immagine precedente in caso di errore
                    this.userImageUrl = localStorage.getItem('userImageUrl') || '/img/user.png';
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                alert('Errore nel caricamento dell\'immagine. Riprova più tardi.');
                // Ripristina l'immagine precedente in caso di errore
                this.userImageUrl = localStorage.getItem('userImageUrl') || '/img/user.png';
            });
        },
        
        removePicture() {
            // Reset to default user.png image
            this.userImageUrl = '/img/user.png';
            localStorage.setItem('userImageUrl', this.userImageUrl);
            
            // Chiama il backend per rimuovere il riferimento dal database
            const formData = new FormData();
            formData.append('reset_image', 'true');
            
            fetch('http://localhost/skiddies/backend/api/upload_profile_image.php', {
                method: 'POST',
                credentials: 'include',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Immagine profilo rimossa dal database');
                } else {
                    console.warn('Errore nella rimozione dal database:', data.error);
                }
            })
            .catch(error => {
                console.warn('Errore nella richiesta di rimozione:', error);
            });
        },
        
        async saveProfile() {
            try {
                this.loading = true;
                
                // Validazione dei campi obbligatori
                if (!this.userProfile.name.trim()) {
                    alert('Il nome è obbligatorio');
                    return;
                }
                
                if (!this.userProfile.email.trim()) {
                    alert('L\'email è obbligatoria');
                    return;
                }
                
                // Validazione email
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(this.userProfile.email)) {
                    alert('Inserisci un\'email valida');
                    return;
                }
                
                const formData = new FormData();
                formData.append('name', this.userProfile.name.trim());
                formData.append('email', this.userProfile.email.trim());
                
                // Invia la password solo se è stata modificata
                if (this.userProfile.password && this.userProfile.password.trim()) {
                    if (this.userProfile.password.length < 6) {
                        alert('La password deve essere di almeno 6 caratteri');
                        return;
                    }
                    formData.append('password', this.userProfile.password);
                }
                
                const response = await fetch('http://localhost/skiddies/backend/api/update_profile.php', {
                    method: 'POST',
                    credentials: 'include',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Profilo aggiornato con successo!');
                    this.userProfile.password = ''; // Pulisci il campo password dopo l'aggiornamento
                    
                    // Ricarica i dati del profilo per essere sicuri che siano aggiornati
                    await this.fetchUserProfile();
                } else {
                    alert('Errore nell\'aggiornamento del profilo: ' + (data.error || 'Errore sconosciuto'));
                }
            } catch (error) {
                console.error('Errore:', error);
                alert('Errore nell\'aggiornamento del profilo. Riprova più tardi.');
            } finally {
                this.loading = false;
            }
        },
        
        async fetchUserProfile() {
            try {
                this.loading = true;
                
                // Reset all'immagine di default all'inizio del caricamento
                this.userImageUrl = '/img/user.png';
                localStorage.removeItem('userImageUrl'); // Pulisci il localStorage per sicurezza
                
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
                    
                    // Precompila i campi con i dati dell'utente
                    this.userProfile.name = data.name || '';
                    this.userProfile.email = data.email || '';
                    this.userProfile.role = 'Tutor'; // Sempre "Tutor" per questa pagina
                    
                    // Gestisce l'immagine del profilo specifica per questo utente
                    if (data.image && data.image !== '') {
                        const imageUrl = `http://localhost/skiddies/backend/uploads/profile_images/${data.image}`;
                        
                        // Verifica se l'immagine esiste effettivamente
                        this.verifyImageExists(imageUrl)
                            .then(exists => {
                                if (exists) {
                                    console.log('Immagine trovata per l\'utente:', data.image);
                                    this.userImageUrl = imageUrl;
                                    localStorage.setItem('userImageUrl', imageUrl);
                                } else {
                                    console.warn('Immagine profilo non trovata nel server:', data.image);
                                    // Resta con l'immagine di default e pulisci il database
                                    this.resetToDefaultImage();
                                }
                            })
                            .catch(() => {
                                console.warn('Errore nella verifica immagine profilo');
                                // Resta con l'immagine di default
                                this.userImageUrl = '/img/user.png';
                                localStorage.setItem('userImageUrl', '/img/user.png');
                            });
                    } else {
                        // Questo utente non ha un'immagine personalizzata
                        console.log('Utente senza immagine personalizzata, uso default');
                        this.userImageUrl = '/img/user.png';
                        localStorage.setItem('userImageUrl', '/img/user.png');
                    }
                    
                    // Verifica che sia effettivamente un tutor
                    if (data.userType && data.userType !== 'tutor') {
                        console.warn('Questo utente non è un tutor, reindirizzamento...');
                        this.$router.push('/'); // Reindirizza alla home se non è un tutor
                        return;
                    }
                    
                } else {
                    console.error('Errore nel caricamento del profilo:', data.error);
                    // Se c'è un errore, potrebbe essere che l'utente non è loggato
                    this.$router.push('/login');
                }
            } catch (error) {
                console.error('Errore nella richiesta:', error);
                // In caso di errore di rete, reindirizza al login
                this.$router.push('/login');
            } finally {
                this.loading = false;
            }
        },
        
        // Metodo per verificare se un'immagine esiste
        verifyImageExists(url) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = () => resolve(true);
                img.onerror = () => resolve(false);
                img.src = url;
            });
        },
        
        // Metodo per resettare all'immagine di default
        resetToDefaultImage() {
            this.userImageUrl = '/img/user.png';
            localStorage.setItem('userImageUrl', '/img/user.png');
            
            // Informa il backend di pulire il riferimento nel database
            const formData = new FormData();
            formData.append('reset_image', 'true');
            
            fetch('http://localhost/skiddies/backend/api/upload_profile_image.php', {
                method: 'POST',
                credentials: 'include',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Riferimento immagine pulito dal database');
                }
            })
            .catch(error => {
                console.warn('Errore nella pulizia del database:', error);
            });
        }

        
        // Gli altri metodi rimangono invariati
    },
    mounted() {
        // NON caricare l'immagine dal localStorage all'inizio
        // Ogni utente deve avere la sua immagine specifica dal database
        
        this.isSidebarOpen = true;
        
        // Carica i dati del profilo quando il componente viene montato
        // Questo precompilerà tutti i campi con i dati attuali del tutor
        // e caricherà l'immagine corretta per questo specifico tutor
        this.fetchUserProfile();
    }
}
</script>