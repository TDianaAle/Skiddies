<template>
    <form @submit.prevent="handleRegister">
        <input v-model="name" placeholder="Nome" required />
        <input v-model="email" type="email" placeholder="Email" required />
        <input v-model="pass" type="password" placeholder="Password" required />
        <input v-model="cpass" type="password" placeholder="Conferma Password" required />
        <select v-model="profession" required>
        <option disabled value="">Seleziona ruolo</option>
        <option value="tutor">Insegnante</option>
        <option value="student">Studente</option>
        </select>
        <input type="file" @change="e => image.value = e.target.files[0]" required />
        <button type="submit">Registrati</button>
        <p>{{ message }}</p>
    </form>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const pass = ref('')
const cpass = ref('')
const profession = ref('')
const image = ref(null)
const message = ref('')

const handleRegister = async () => {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('email', email.value)
    formData.append('pass', pass.value)
    formData.append('cpass', cpass.value)
    formData.append('profession', profession.value)
    formData.append('image', image.value)
    formData.append('submit', '1')

    try {
        const res = await axios.post('/api/register.php', formData)
        message.value = res.data.message || 'Registrazione completata!'
    } catch (err) {
        console.error(err)
        message.value = 'Errore nella registrazione.'
    }
}
</script>
<script setup>
import RegisterForm from '@/components/RegisterForm.vue'
</script>