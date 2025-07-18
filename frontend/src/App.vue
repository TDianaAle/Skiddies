<template>
  <!-- Tutta l'app si basa sul router-view -->
  <router-view />
</template>

<script setup>
  async function loadProfile() {
    try {
      const res = await fetch('http://localhost/skiddies/backend/api/get_profile.php', {
        credentials: 'include'
      })
      const data = await res.json()

      if (data.success) {
        profileName.value = data.name
        profileImageUrl.value = `http://localhost/skiddies/backend/uploads/profile_images/${data.image}`
      } else {
        console.warn('Profilo non trovato:', data.error)
      }
    } catch (err) {
      console.error('Errore caricamento profilo:', err)
    }
  }

</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;600&display=swap');

body {
  font-family: 'Nunito', sans-serif;
}
</style>
