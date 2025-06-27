<template>
    <div class="form-container">
    <!-----<video autoplay muted loop id="bg-video">
        <source src="/Skiddies/front-end/images/1409899-uhd_3840_2160_25fps.mp4" type="video/mp4">
    </video>--->

    <form @submit.prevent="handleRegister" class="register">
        <h3>Registrati</h3>
        
        <div class="flex">
            <div class="col">
            <p>Nome <span>*</span></p>
            <input 
                type="text" 
                v-model="name" 
                placeholder="Inserisci il tuo nome" 
                maxlength="50" 
                required 
                class="box"
            />

            <p>Ruolo <span>*</span></p>
            <select v-model="profession" required class="box">
                <option value="" disabled selected>--seleziona il tuo ruolo</option>
                <option value="tutor">insegnante</option>
                <option value="student">studente</option>
            </select>

            <p>Email <span>*</span></p>
            <input 
                type="email" 
                v-model="email" 
                placeholder="Inserisci la tua email" 
                maxlength="50" 
                required 
                class="box"
            />
            </div>

            <div class="col">
            <p>Password <span>*</span></p>
            <input 
                type="password" 
                v-model="password" 
                placeholder="Inserisci la password" 
                maxlength="50" 
                required 
                class="box"
            />

            <p>Conferma Password <span>*</span></p>
            <input 
                type="password" 
                v-model="confirmPassword" 
                placeholder="Conferma la password" 
                maxlength="50" 
                required 
                class="box"
            />

            <p>Seleziona immagine del profilo <span>*</span></p>
            <input 
                type="file" 
                ref="image" 
                accept="image/*" 
                required 
                class="box"
            />
            </div>
        </div>

        <p class="link">Hai già un account? <a href="/login">Login</a></p>
        <input type="submit" class="btn" value="Registrati ora" />
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
        profession: '',
        image: null,
        };
    },
    methods: {
        async handleRegister() {
        // Prepara i dati per il submit
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('email', this.email);
        formData.append('password', this.password);
        formData.append('confirmPassword', this.confirmPassword);
        formData.append('profession', this.profession);
        formData.append('image', this.$refs.image.files[0]);

        try {
            // Invia la richiesta al backend
            const response = await axios.post('http://localhost:3000/api/register', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (response.data.success) {
            alert('Registrazione avvenuta con successo!');
            this.$router.push('/login'); // Reindirizza alla pagina di login
            } else {
            alert('Errore durante la registrazione!');
            }
        } catch (error) {
            console.error('Errore nel server:', error);
        }
        }
    }
};
</script>