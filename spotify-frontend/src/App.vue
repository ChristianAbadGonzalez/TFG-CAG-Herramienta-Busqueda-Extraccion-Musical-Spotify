<template>
  <div style="padding: 2rem">
    <h1>Canciones guardadas en MySQL (Spotify)</h1>
    <input
      v-model="busqueda"
      placeholder="Buscar por nombre o artista"
      style="margin-bottom: 1rem; padding: 0.5rem; width: 300px;"
    />

    <table border="1" cellpadding="8" style="margin-top: 1rem; width: 100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Artista</th>
          <th>Álbum</th>
          <th>Fecha lanzamiento</th>
          <th>Popularidad</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cancion in cancionesFiltradas" :key="cancion.id">
          <td>{{ cancion.nombre }}</td>
          <td>{{ cancion.artista }}</td>
          <td>{{ cancion.album }}</td>
          <td>{{ cancion.fecha_lanzamiento }}</td>
          <td>{{ cancion.popularidad }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const canciones = ref([])
const busqueda = ref("")

onMounted(async () => {
  const respuesta = await axios.get('http://localhost/spotify-api/canciones.php')
  canciones.value = respuesta.data
})

const cancionesFiltradas = computed(() => {
  if (!busqueda.value) return canciones.value
  const texto = busqueda.value.toLowerCase()
  return canciones.value.filter(c =>
    c.nombre.toLowerCase().includes(texto) ||
    c.artista.toLowerCase().includes(texto)
  )
})
</script>
