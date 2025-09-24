"""
spotify_to_mysql.py

Este script permite buscar canciones de un artista en Spotify utilizando su API oficial
y almacenar los resultados en una base de datos MySQL. Las credenciales necesarias deben 
configurarse previamente en un archivo `.env`.

Requisitos:
- spotipy
- mysql-connector-python
- python-dotenv
"""

import os
import sys
from dotenv import load_dotenv
import spotipy
from spotipy.oauth2 import SpotifyOAuth
import mysql.connector

# Cargar variables de entorno desde el archivo .env
load_dotenv(dotenv_path='C:/Users/M7600/Desktop/TFG/TFG CHRISTIAN/Spotify-Tool/.env')

# Configuración para la conexión a MySQL
mysql_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '', 
    'database': 'spotify_db'
}

# Inicializar cliente de Spotify con autenticación OAuth
sp = spotipy.Spotify(auth_manager=SpotifyOAuth(
    client_id=os.getenv("SPOTIPY_CLIENT_ID"),
    client_secret=os.getenv("SPOTIPY_CLIENT_SECRET"),
    redirect_uri=os.getenv("SPOTIPY_REDIRECT_URI"),
    scope="user-read-private"
))

def guardar_canciones(query):
    """
    Busca canciones del artista indicado y las guarda en la base de datos MySQL.

    Parámetros:
    query (str): Nombre del artista a buscar en Spotify.
    """
    resultados = sp.search(q=query, type='track', limit=10)

    # Conexión a la base de datos
    db = mysql.connector.connect(**mysql_config)
    cursor = db.cursor()

    for item in resultados['tracks']['items']:
        id_cancion = item['id']
        nombre = item['name']
        artista = item['artists'][0]['name']
        album = item['album']['name']
        fecha = item['album']['release_date']
        popularidad = item['popularity']

        # Insertar canción en la base de datos (o actualizar si ya existe)
        sql = """
            INSERT INTO canciones (id, nombre, artista, album, fecha_lanzamiento, popularidad)
            VALUES (%s, %s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
                nombre=VALUES(nombre),
                artista=VALUES(artista),
                album=VALUES(album),
                fecha_lanzamiento=VALUES(fecha_lanzamiento),
                popularidad=VALUES(popularidad)
        """
        valores = (id_cancion, nombre, artista, album, fecha, popularidad)
        cursor.execute(sql, valores)

    db.commit()
    cursor.close()
    db.close()
    print(f"[✓] Canciones de '{query}' guardadas correctamente en la base de datos.\n")

# Punto de entrada principal
if __name__ == "__main__":
    if len(sys.argv) > 1:
        # Si se pasa el nombre del artista como argumento
        artista = " ".join(sys.argv[1:])
        guardar_canciones(artista)

        # ✅ Aquí ya está definida la variable artista
        with open("C:/Users/M7600/Desktop/TFG/TFG CHRISTIAN/Spotify-Tool/log.txt", "a", encoding="utf-8") as f:
            f.write(f"[✔] Script ejecutado con artista: {artista}\n")

    else:
        # Modo interactivo: solicitar nombre desde consola
        while True:
            artista = input("Introduce un artista o nombre de la canción (o escribe 'salir' para terminar): ")
            if artista.strip().lower() == 'salir':
                print("Finalizando el programa.")
                break
            elif not artista.strip():
                print("Por favor, introduce un nombre válido.\n")
            else:
                guardar_canciones(artista)
