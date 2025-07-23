<template>
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <router-link to="/" class="flex items-center space-x-3">
            <img src="/img/logo.png" alt="Skiddies Logo" class="h-20 w-auto">
            <h1 class="text-2xl font-bold text-gray-800">Skiddies</h1>
        </router-link>
      </div>
      <!-- Navigazione -->
      <nav class="space-x-6">
        <router-link to="/login"
          class="text-gray-600 hover:text-blue-600 transition-colors">Login</router-link>
        <router-link to="/register"
          class="text-gray-600 hover:text-blue-600 transition-colors">Registrati</router-link>
      </nav>
    </div>
  </header>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h3 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">Registrati</h3>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <form @submit.prevent="handleRegister" class="bg-white py-8 px-6 shadow rounded-lg space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
          <input
            type="text"
            v-model="name"
            placeholder="Inserisci il tuo nome"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
          <input
            type="email"
            v-model="email"
            placeholder="Inserisci la tua email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
          <input
            type="password"
            v-model="password"
            placeholder="Inserisci la password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Conferma Password <span class="text-red-500">*</span></label>
          <input
            type="password"
            v-model="confirmPassword"
            placeholder="Conferma la password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Ruolo <span class="text-red-500">*</span></label>
          <select
            v-model="role"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
          >
            <option value="user">Studente</option>
            <option value="tutor">Tutor</option>
          </select>
        </div>

        <p class="text-sm text-center mt-2">
          Hai già un account?
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
            Accedi
          </router-link>
        </p>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition font-semibold"
        >
          Registrati
        </button>

        <div v-if="message" class="mt-4 flex items-center justify-between bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded">
          <span>{{ message }}</span>
          <button @click="message = ''" class="ml-2 text-xl leading-none hover:text-red-500">&times;</button>
        </div>
      </form>
    </div>
  </div>
  <footer class="bg-gray-50 text-center py-4 text-gray-500 text-sm mt-auto">
    2025 Skiddies | Made By Diana Tichy & Sofia Ricci
  </footer>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
const router = useRouter()

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
      router.push('/login') 
    } else {
      message.value = result.error || 'Errore imprevisto.'
    }

  } catch (error) {
    message.value = 'Errore di rete o server non raggiungibile.'
    console.error(error)
  }
}
</script>
