<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
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


        <!-- Hero Section -->
        <section class="bg-blue-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Benvenuto su Skiddies!</h2>
                <p class="text-xl text-gray-600 font-semibold mb-6">La piattaforma per tutor e studenti dove imparare e insegnare
                    diventa semplice.</p>
            </div>
        </section>

        <!-- Features -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8 text-center">
                <div>
                   
                    <h3 class="text-xl font-semibold mb-2">Materiale Didattico</h3>
                    <p class="text-gray-600 text-md">Accedi a risorse educative di alta qualità, ovunque e in qualsiasi momento.
                    </p>
                </div>
                <div>
                   
                    <h3 class="text-xl font-semibold mb-2">Tutor Esperti</h3>
                    <p class="text-gray-600 text-md">Impara da tutor qualificati pronti ad aiutarti nel tuo percorso.</p>
                </div>
                <div>
                    
                    <h3 class="text-xl font-semibold mb-2">Piattaforma Interattiva</h3>
                    <p class="text-gray-600 text-md">Un ambiente moderno e intuitivo per una migliore esperienza di
                        apprendimento.</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <router-link to="/register"
                    class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition text-md font-semibold">
                    Registrati subito!</router-link>
            </div>

        </section>

        <!-- Footer -->
        <footer class="bg-gray-100 py-8 text-center text-gray-500 text-sm">
            2025 Skiddies | Made By Sofia & Diana
        </footer>
    </div>
</template>

<script setup>
// logica
</script>

<style scoped>
/*override locali? */
</style>
