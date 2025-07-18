<template>
  <form @submit.prevent="handleRegister">
    <input v-model="name" placeholder="Nome" required />
    <input v-model="email" type="email" placeholder="Email" required />
    <input v-model="password" type="password" placeholder="Password" required />
    <input v-model="confirmPassword" type="password" placeholder="Conferma Password" required />
    <select v-model="role" required>
      <option disabled value="">Seleziona ruolo</option>
      <option value="tutor">Insegnante</option>
      <option value="user">Studente</option>
    </select>
    <!-- Per ora lascio l'immagine commentata, perché backend non la gestisce ancora -->
    <!-- <input type="file" @change="e => image = e.target.files[0]" /> -->
    <button type="submit">Registrati</button>
    <p>{{ message }}</p>
  </form>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const role = ref('')
//const // image = ref(null) al momento non gestiamo l'immagine profilo
const message = ref('')

const handleRegister = async () => {
    if (password.value !== confirmPassword.value) {
        message.value = "Le password non coincidono"
        return
    }

    try {
        const res = await axios.post('/api/register.php', {
        name: name.value,
        email: email.value,
        password: password.value,
        confirmPassword: confirmPassword.value,
        role: role.value,
        // image: image.value ? image.value : null
        }, {
        headers: {
            'Content-Type': 'application/json'
        }
        })

        if (res.data.success) {
        message.value = 'Registrazione completata!'
        // eventualmente resetta i campi qui
        name.value = ''
        email.value = ''
        password.value = ''
        confirmPassword.value = ''
        role.value = ''
        // image.value = null
        } else {
        message.value = res.data.error || 'Errore nella registrazione'
        }

    } catch (err) {
        console.error(err)
        message.value = 'Errore nella registrazione.'
    }
}
</script>
