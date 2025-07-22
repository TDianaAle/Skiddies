<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
          <router-link to="/" class="flex items-center space-x-3">
            <img src="/img/logo.png" alt="Skiddies Logo" class="h-20 w-auto" />
            <h1 class="text-2xl font-bold text-gray-800">Skiddies</h1>
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="space-x-6">
          <router-link to="/login" class="text-gray-800 hover:text-blue-600">Login</router-link>
          <router-link to="/register" class="text-gray-800 hover:text-blue-600">Registrati</router-link>
        </nav>
      </div>
    </header>

    <!-- Hero Introduction -->
    <section class="flex-1 flex items-center justify-center px-6 py-20 bg-gradient-to-br from-blue-50 to-white">
      <div class="max-w-4xl text-center">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-6">
          Impara velocemente con Skiddies
        </h2>
        <p class="text-lg text-gray-600 mb-8">
          Benvenuto su <strong>Skiddies</strong> — la piattaforma dove studenti e tutor si incontrano.
          Se sei uno <strong>studente</strong>, esplora video lezioni per potenziare il tuo apprendimento.
          Se sei un <strong>tutor</strong>, carica i tuoi contenuti e condividi la tua esperienza.
        </p>
        <router-link to="/register" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full text-lg shadow hover:bg-blue-700 transition">
          Inizia ora!
        </router-link>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 py-8 text-center text-gray-500 text-sm mt-10">
      2025 Skiddies | Made By Diana Tichy & Sofia Ricci
    </footer>
  </div>
</template>

<script>
export default {
  name: 'HomeView'
}
</script>
