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

                <!-- Navigation -->
                <nav class="space-x-6">
                    <button id="userViewButton" class="text-gray-800 hover:text-gray-600">
                        <router-link to="/user"/>
                    </button>
                </nav>
            </div>
        </header>

        <!-- Footer -->
        <footer class="bg-gray-100 py-8 text-center text-gray-500 text-sm">
            2025 Skiddies | Made By Sofia & Diana
        </footer>
    </div>
</template>

<script>
export default {
    mounted() {
        const userViewButton = document.getElementById('userViewButton');
        const userDropdown = document.getElementById('userDropdown');

        userViewButton.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });
    }
}
</script>
