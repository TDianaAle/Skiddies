<template>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h3 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">Accedi</h3>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <form @submit.prevent="handleLogin" class="bg-white py-8 px-6 shadow rounded-lg space-y-5">
        

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
            id="email"
            type="email"
            v-model="email"
            placeholder="Inserisci la tua email"
            required
            class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
            id="password"
            type="password"
            v-model="password"
            placeholder="Inserisci la tua password"
            required
            class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
        </div>

        <!-- Error Message -->
        <div v-if="message" class="text-red-500 text-sm mb-4">
            <span>{{ message }}</span>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
            Accedi
            </button>
        </div>
    </form>
</div>
</div>

        <p class="mt-4 text-sm text-center">
            Non hai un account? 
            <router-link to="/register" class="text-blue-500 hover:underline">Registrati</router-link>
        </p>
        
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');
const password = ref('');
const message = ref('');

async function handleLogin() {
  console.log("Form submit called"); // Verifica che la funzione venga chiamata

    if (!email.value || !password.value) {
        message.value = 'Tutti i campi sono obbligatori.';
        return;
    }

    try {
        const response = await fetch('http://localhost/skiddies/backend/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ email: email.value, password: password.value })
        });

        const result = await response.json();
        console.log(result); // Log del risultato

        if (response.ok && result.success) {
        message.value = 'Login effettuato con successo!';
        // Esegui il redirect verso la dashboard
        // router.push('/student-dashboard'); 
        } else {
        message.value = result.error || 'Errore imprevisto.';
        }
    } catch (error) {
        message.value = 'Errore di rete o server non raggiungibile.';
        console.error(error);
    }
}
</script>
