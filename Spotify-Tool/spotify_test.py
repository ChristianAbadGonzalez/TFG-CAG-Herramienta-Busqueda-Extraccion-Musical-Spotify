"""
spotify_test.py

Este script realiza una prueba simple de conexión con la API de Spotify para verificar 
la autenticación y funcionalidad de búsqueda. Utiliza la biblioteca Spotipy y requiere 
un archivo `.env` con las credenciales de Spotify correctamente configuradas.

Requisitos:
- spotipy
- python-dotenv
"""

import os
from dotenv import load_dotenv
import spotipy
from spotipy.oauth2 import SpotifyOAuth

# Cargar variables de entorno desde el archivo .env
load_dotenv()

# Inicialización del cliente de Spotify con autenticación OAuth
sp = spotipy.Spotify(auth_manager=SpotifyOAuth(
    client_id=os.getenv("SPOTIPY_CLIENT_ID"),
    client_secret=os.getenv("SPOTIPY_CLIENT_SECRET"),
    redirect_uri=os.getenv("SPOTIPY_REDIRECT_URI"),
    scope="user-read-private"
))

# Consulta de prueba para buscar canciones relacionadas con el artista
query = "Imagine Dragons"
resultados = sp.search(q=query, type='track', limit=3)

# Mostrar los resultados obtenidos
print(f"\nResultados de búsqueda para: '{query}'\n")
for idx, item in enumerate(resultados['tracks']['items'], start=1):
    nombre_cancion = item['name']
    nombre_artista = item['artists'][0]['name']
    print(f"{idx}. {nombre_cancion} - {nombre_artista}")
