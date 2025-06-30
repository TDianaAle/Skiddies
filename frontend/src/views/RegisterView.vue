<template>
    <div class="form-container">
        <form @submit.prevent="handleRegister" class="register">
        <h3>Registrati</h3>
        <div class="flex">
            <div class="col">
            <p>Nome <span>*</span></p>
            <input
                type="text"
                v-model="name"
                placeholder="Inserisci il tuo nome"
                required
                class="box"
            />

            <p>Email <span>*</span></p>
            <input
                type="email"
                v-model="email"
                placeholder="Inserisci la tua email"
                required
                class="box"
            />

            <p>Password <span>*</span></p>
            <input
                type="password"
                v-model="password"
                placeholder="Inserisci la password"
                required
                class="box"
            />

            <p>Conferma Password <span>*</span></p>
            <input
                type="password"
                v-model="confirmPassword"
                placeholder="Conferma la password"
                required
                class="box"
            />

            <p>Ruolo <span>*</span></p>
            <select v-model="role" required class="box">
                <option value="user">Utente</option>
                <option value="tutor">Tutor</option>
            </select>
            </div>
        </div>

        <p class="link">
            Hai già un account? <router-link to="/login">Accedi</router-link>
        </p>

        <input type="submit" class="btn" value="Registrati" />
        </form>

        <div v-if="message" class="message">
        <span>{{ message }}</span>
        <i class="bx bx-x" @click="message = ''"></i>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const role = ref('user')
const message = ref('')

async function handleRegister() {
    if (!name.value || !email.value || !password.value || !confirmPassword.value || !role.value) {
        message.value = 'Tutti i campi sono obbligatori.'
        return
    }

    if (password.value !== confirmPassword.value) {
        message.value = 'Le password non coincidono.'
        return
    }

    try {
        const response = await fetch('http://localhost/skiddies/backend/api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify({
            name: name.value,
            email: email.value,
            password: password.value,
            confirmPassword: confirmPassword.value,
            role: role.value
        })
        })

        const text = await response.text()
        console.log('RAW RESPONSE:', text)

        let result
        try {
        result = JSON.parse(text)
        } catch (e) {
        message.value = 'Risposta non valida dal server.'
        return
        }

        if (response.ok && result.success) {
        message.value = 'Registrazione avvenuta con successo!'
        // router.push('/login')
        } else {
        message.value = result.error || 'Errore imprevisto.'
        }

    } catch (error) {
        message.value = 'Errore di rete o server non raggiungibile.'
        console.error(error)
    }
}
</script>
