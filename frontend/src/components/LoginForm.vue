<template>
    <form @submit.prevent="handleLogin">
        <input v-model="email" type="email" placeholder="Email" required />
        <input v-model="password" type="password" placeholder="Password" required />
        <button type="submit">Login</button>
        <p>{{ message }}</p>
    </form>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const message = ref('')

const handleLogin = async () => {
    try {
        const res = await axios.post(
            '/api/login.php',
            JSON.stringify({ email: email.value, password: password.value }),
            {
                headers: { 'Content-Type': 'application/json' },
                withCredentials: true
            }
        )

        if (res.data.success) {
            message.value = 'Login effettuato con successo!'
        } else {
            message.value = res.data.error || 'Errore durante il login.'
        }
    } catch (err) {
        console.error(err)
        message.value = 'Errore durante la richiesta.'
    }
}
</script>
<script setup>
import LoginForm from '@/components/LoginForm.vue'
</script>

