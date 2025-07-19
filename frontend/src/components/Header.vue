<template>
    <header class="bg-white shadow">
        <div class="w-full h-20 px-4 flex items-center justify-between">
            <div class="flex items-center">
                <button @click="$emit('toggleSidebar')" class="focus:outline-none mr-6">
                    <img :src="isSidebarOpen ? '/img/close.png' : '/img/open.png'" alt="Sidebar Toggle"
                        class="h-6 w-6" />
                </button>
                <div class="flex items-center">
                    <img src="/img/logo.png" alt="Logo" class="h-16 w-auto" />
                    <h1 class="text-xl font-bold text-gray-800 ml-3">Skiddies - Studente</h1>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button @click="$emit('logout')" class="text-red-600 hover:underline">Esci</button>
                <div class="relative" @mouseleave="$emit('handleDropdownMouseLeave')">
                    <img :src="userImageUrl" alt="User Avatar"
                        class="h-10 w-10 rounded-full border-2 border-indigo-400 cursor-pointer object-cover"
                        @click="$emit('toggleDropdown')" />
                    <div v-if="showDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                        @mousedown.stop>
                        <button @mousedown.stop.prevent="$emit('goToProfile')"
                            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50">Personalizza
                            profilo</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        userImageUrl: String,
        showDropdown: Boolean,
        isSidebarOpen: Boolean
    }
};
</script>
