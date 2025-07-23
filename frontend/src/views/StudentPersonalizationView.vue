<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <HeaderStudent :userImageUrl="userImageUrl" :showDropdown="showDropdown" @logout="logout"
            @toggle-dropdown="toggleDropdown" @leave-dropdown="handleDropdownMouseLeave" @go-profile="goToProfile"
            @image-error="handleImageError" />

        <div class="flex min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <!-- Sidebar -->
            <SidebarStudent :isSidebarOpen="isSidebarOpen" @toggle-sidebar="toggleSidebar" @navigate="goTo" />
            <div
                class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 w-full flex flex-col gap-5 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]">
                <main class="w-full md:w-2/3 lg:w-3/4 py-8 px-4 max-w-full overflow-hidden">
                    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-10 max-w-full overflow-hidden">
                        <h2 class="text-3xl font-bold text-indigo-800 border-b pb-4 mb-6">
                            Profilo Studente
                        </h2>

                        <!-- Loader -->
                        <div v-if="loading" class="flex justify-center items-center py-10">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
                        </div>

                        <!-- Profilo Form -->
                        <div v-else class="grid grid-cols-1 gap-8">
                            <!-- Avatar + upload -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-6 min-w-0 flex-wrap">
                                <img class="object-cover w-32 h-32 p-1 rounded-full ring-4 ring-indigo-300 max-w-full"
                                    :src="userImageUrl" alt="Avatar" @error="resetToDefaultImage" />

                                <div class="flex flex-col gap-3 w-full sm:w-auto max-w-full">
                                    <input type="file" accept="image/*" @change="onImageChange"
                                        class="w-full max-w-xs text-sm text-indigo-900 bg-white rounded-lg border border-indigo-300 px-4 py-2 hover:bg-indigo-50 transition" />

                                    <button @click="removePicture"
                                        class="w-full max-w-xs text-sm text-indigo-700 bg-white border border-indigo-300 px-4 py-2 rounded-lg hover:bg-indigo-50 transition">
                                        Elimina immagine
                                    </button>
                                </div>
                            </div>


                            <!-- Form Fields -->
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">Nome</label>
                                    <input id="first_name" v-model="userProfile.name" type="text"
                                        placeholder="Il tuo nome"
                                        class="mt-1 block w-full rounded-md border border-indigo-300 bg-indigo-50 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                </div>

                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700">Password</label>
                                    <input id="password" v-model="userProfile.password" type="password"
                                        placeholder="Nuova password (facoltativa)"
                                        class="mt-1 block w-full rounded-md border border-indigo-300 bg-indigo-50 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input id="email" v-model="userProfile.email" type="email"
                                        placeholder="your@email.com"
                                        class="mt-1 block w-full rounded-md border border-indigo-300 bg-indigo-50 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="profession"
                                        class="block text-sm font-medium text-gray-700">Ruolo</label>
                                    <input id="profession" v-model="userProfile.role" type="text" disabled
                                        class="mt-1 block w-full rounded-md border border-indigo-300 bg-indigo-50 text-gray-500 p-2.5 text-sm" />
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end mt-6">
                                <button @click="saveProfile" :disabled="loading"
                                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition disabled:opacity-50">
                                    {{ loading ? 'Salvataggio...' : 'Salva modifiche' }}
                                </button>
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
import HeaderStudent from './HeaderStudentView.vue';
import SidebarStudent from './SidebarStudentView.vue';

export default {
    components: {
        HeaderStudent,
        SidebarStudent
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
                role: 'Studente'  // Ruolo fisso per gli studenti
            },
            loading: false,
            userType: 'student' // Questa pagina è solo per studenti
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
            this.$router.push('/studentPersonalization');
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

                    await this.fetchUserProfile();

                    // Reindirizza alla dashboard dopo 1 secondi
                    setTimeout(() => {
                        this.$router.push('/student');
                    }, 1000);
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
                    this.userProfile.role = 'Studente'; // Sempre "Studente" per questa pagina

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

                    // Verifica che sia effettivamente uno studente
                    if (data.userType && data.userType !== 'student') {
                        console.warn('Questo utente non è uno studente, reindirizzamento...');
                        this.$router.push('/'); // Reindirizza alla home se non è uno studente
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

    },
    mounted() {
        // NON caricare l'immagine dal localStorage all'inizio
        // Ogni utente deve avere la sua immagine specifica dal database

        this.isSidebarOpen = true;

        // Carica i dati del profilo quando il componente viene montato
        // Questo precompilerà tutti i campi con i dati attuali dell'utente
        // e caricherà l'immagine corretta per questo specifico utente
        this.fetchUserProfile();
    }
}
</script>