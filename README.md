# 🎵 Trabajo de Fin de Grado (TFG)  
## Herramienta de Búsqueda y Extracción de Información Musical Utilizando la Base de Datos de Spotify  

# 📖 Español  

## 📌 Descripción general  
Este proyecto implementa una herramienta para **buscar, análizar y extraer información musical** utilizando la **API pública de Spotify**.  

La aplicación recupera metadatos clave como:  
- Nombre de la canción  
- Artista  
- Álbum  
- Fecha de lanzamiento  
- Popularidad  

Se desarrollaron **dos versiones** del sistema:  
- **Versión 1 (V1):** prueba de concepto con interfaz básica y consultas por consola en Python.  
- **Versión 2 (V2):** versión final con interfaz web (HTML, CSS, JS bajo PHP) y registro de usuarios.  

Ambas versiones utilizan **XAMPP** como entorno local para gestionar la base de datos MySQL/MariaDB.  

## 🎧 Motivación  
La música es un lenguaje universal: transmite emoción, ritmo y estructura. Este proyecto nace con la idea de conectar ese lado emocional con la mirada analítica de la tecnología.  

Los objetivos iniciales fueron:  
- Comprender mejor lo que se esconde detrás de cada canción.  
- Transformar datos en información accesible y significativa.  
- Crear una herramienta académica, divulgativa y adaptable.  

## 🎯 Objetivos  
- Desarrollar una aplicación ligera, modular y sostenible.  
- Conectar con la API de Spotify y almacenar los datos en MySQL/MariaDB.  
- Presentar resultados en un entorno accesible y claro.  
- Incluir funciones analíticas como comparación de canciones, etiquetas narrativas y registro de consultas.  
- Favorecer la usabilidad incluso en personas sin perfil técnico.  

## 🛠️ Tecnologías usadas  
- **Lenguajes:** *Python*, *PHP*, *HTML*, *CSS*, *JavaScript*, *VueJS*, *SQL*, *NoSQL*.  
- **Base de datos:** *MySQL/MariaDB*.  
- **Entorno local:** *XAMPP* (Apache, PHP, MySQL).  
- **Control de versiones:** *Git*.  
- **Editor principal:** *Visual Studio Code*.  
- **Librerías Python:** `spotipy`, `mysql-connector-python`, `python-dotenv`.  

## 🔹 Versiones del proyecto  

### Versión 1 (V1 – Consola + Python)  
- Frontend inicial en Vue.js.  
- Consultas interactivas desde consola en Python.  
- Inserción de datos en MySQL/MariaDB mediante XAMPP.  
- Objetivo: validar la arquitectura técnica y el flujo de datos.  

### Versión 2 (V2 – Final con interfaz web)  
- Interfaz web en HTML, CSS y JavaScript bajo PHP.  
- Formulario de registro de usuarios y buscador de artistas.  
- Script en Python para conexión con la API de Spotify e inserción en MySQL/MariaDB.  
- Objetivo: mejorar accesibilidad, usabilidad y personalización de consultas.  

## 📐 Metodología  
El desarrollo se organizó de forma **iterativa e incremental**:  
1. **Investigación inicial** sobre APIs y tecnologías.  
2. **Diseño y desarrollo** en dos fases progresivas (V1 y V2).  
3. **Pruebas funcionales y de usabilidad** con distintos perfiles de usuario.  
4. **Documentación técnica** mediante este `README.md` y comentarios en el código.  

## 📊 Resultados  
- Sistema funcional y estable con consultas en tiempo real a la API.  
- Interfaz clara validada por usuarios no técnicos.  
- Funciones analíticas básicas implementadas (análisis individual, comparación, etiquetas narrativas).  
- Base de datos local estructurada y lista para ampliaciones futuras.  

## 🚀 Guía de instalación  

### 🔧 Requisitos previos  
Asegúrate de tener instalados:  
- Python 3.x  
- PHP 8.x  
- MySQL/MariaDB  
- [XAMPP](https://www.apachefriends.org/) (obligatorio para ambas versiones)  

### ▶️ Instalación de dependencias  
Desde la carpeta del proyecto, instala las librerías de Python necesarias:  

```bash
pip install spotipy mysql-connector-python python-dotenv
```  

### ⚙️ Configuración de Spotify Developers  
1. Accede a [Spotify for Developers](https://developer.spotify.com).  
2. Crea una aplicación y añade como **Redirect URI**:  
   ```
   http://127.0.0.1:8888/callback
   ```  
3. Copia tu **Client ID** y **Client Secret**.  
4. En la carpeta principal del proyecto **Spotify-Tool**, crea un archivo `.env` con:  

```
SPOTIPY_CLIENT_ID=tu_client_id
SPOTIPY_CLIENT_SECRET=tu_client_secret
SPOTIPY_REDIRECT_URI=http://127.0.0.1:8888/callback
```  

### 🗄️ Configuración de la base de datos  
1. Abre el panel de XAMPP y activa **Apache** y **MySQL**.  
2. Accede a [http://localhost/phpmyadmin](http://localhost/phpmyadmin).  
3. Crea una base de datos llamada `spotify_db`.  
4. Importa el archivo `.sql` de la carpeta `/sql` para generar las tablas necesarias.  

### ▶️ Ejecución del script de inserción de datos (V1)  
1. Desde la terminal, en la carpeta `Spotify-Tool`:  

```bash
python spotify_to_mysql.py
```  

2. El script pedirá el nombre de un artista (ejemplo: `Imagine Dragons`).  
3. Los datos se guardarán en la base de datos `spotify_db`.  

## 👀 Visualización de resultados  
- **Desde la API local (PHP):**  
  [http://localhost/spotify-api/canciones.php](http://localhost/spotify-api/canciones.php)  
- **Desde el frontend Vue.js (opcional en V1):**  
  [http://localhost:8080](http://localhost:8080)  

### 🌐 Interfaz web (Versión 2)  
Para usar la **V2** con la interfaz web:  

1. Copia la carpeta `spotify-api` dentro de la carpeta `htdocs` de XAMPP:  

   ```
   C:\xampp\htdocs\spotify-api\
   ```  

2. Asegúrate de tener **Apache** y **MySQL** levantados en XAMPP.  

3. Abre tu navegador y entra en la URL:  

👉 [http://localhost/spotify-api/](http://localhost/spotify-api/)  

Desde ahí podrás acceder al buscador de artistas y al formulario de registro de usuarios.  

### 📚 Creditos

- **AUTOR: ABAD GONZÁLEZ, CHRISTIAN**
- **TUTOR: GUALDA GÓMEZ, DAVID**
- **CURSO ACADÉMICO: 2025/2026**
- **CENTRO: UNIVERSIDAD REY JUAN CARLOS (URJC)**

🎶 *La música es emoción, y los datos también pueden serlo. Esta herramienta es una invitación a mirar la música desde otra perspectiva.*  

---

# 🎵 Final Degree Project (TFG)  
## Music Information Search and Extraction Tool Using the Spotify Database


# 📖 English  

## 📌 Overview  
This project implements a tool to **search, analyze and extraction music information** using the **Spotify public API**.  

The application retrieves the following key metadata:  
- Track name  
- Artist  
- Album  
- Release date  
- Popularity  

Two versions of the system were developed:  
- **Version 1 (V1):** proof of concept with a basic interface and console-based queries in Python.  
- **Version 2 (V2):** final version with a web interface (HTML, CSS, JS with PHP) and user registration.  

Both versions require **XAMPP** as a local environment to manage the MySQL/MariaDB database.  

## 🎧 Motivation  
Music is a universal language: it conveys emotion, rhythm, and structure. This project was born with the idea of connecting that emotional side with the analytical perspective of technology.  

Main goals were:  
- To better understand what lies behind each song.  
- To transform raw data into accessible and meaningful information.  
- To provide an academic, educational, and adaptable tool.  

## 🎯 Objectives  
- Develop a lightweight, modular, and sustainable application.  
- Connect with the Spotify API and store data in MySQL/MariaDB.  
- Display results in a clear and accessible environment.  
- Implement analytical features such as song comparison, narrative labels, and query logging.  
- Ensure usability even for non-technical users.  

## 🛠️ Technologies Used  
- **Languages:** *Python*, *PHP*, *HTML*, *CSS*, *JavaScript*, *VueJS*, *SQL*, *NoSQL*.  
- **Database:** *MySQL/MariaDB*.  
- **Local environment:** *XAMPP* (Apache, PHP, MySQL).  
- **Version control:** *Git*.  
- **Main editor:** *Visual Studio Code*.  
- **Python libraries:** `spotipy`, `mysql-connector-python`, `python-dotenv`.  

## 🔹 Project Versions  

### Version 1 (V1 – Console + Python)  
- Initial frontend in Vue.js.  
- Interactive queries from the Python console.  
- Data insertion into MySQL/MariaDB through XAMPP.  
- Goal: validate the technical architecture and data flow.  

### Version 2 (V2 – Final Web Interface)  
- Web interface with HTML, CSS, and JavaScript under PHP.  
- User registration form and artist search functionality.  
- Python script for connecting to the Spotify API and inserting into MySQL/MariaDB.  
- Goal: improve accessibility, usability, and search personalization.  

## 📐 Methodology  
Development was organized in an **iterative and incremental** way:  
1. **Initial research** on APIs and technologies.  
2. **Design and development** in two progressive phases (V1 and V2).  
3. **Functional and usability testing** with both technical and non-technical users.  
4. **Technical documentation** through this `README.md` and code comments.  

## 📊 Results  
- Functional and stable system with real-time queries to the API.  
- Clear interface validated by non-technical users.  
- Basic analytical features implemented (single analysis, comparison, narrative tags).  
- Local structured database ready for future expansions.  

## 🚀 Installation Guide  

### 🔧 Requirements  
Make sure you have installed:  
- Python 3.x  
- PHP 8.x  
- MySQL/MariaDB  
- [XAMPP](https://www.apachefriends.org/) (mandatory for both versions)  

### ▶️ Install dependencies  
From the project folder, install the required Python libraries:  

```bash
pip install spotipy mysql-connector-python python-dotenv
```  

### ⚙️ Spotify Developers configuration  
1. Go to [Spotify for Developers](https://developer.spotify.com).  
2. Create an application and add this **Redirect URI**:  
   ```
   http://127.0.0.1:8888/callback
   ```  
3. Copy your **Client ID** and **Client Secret**.  
4. In the project root folder, create a `.env` file with:  

```
SPOTIPY_CLIENT_ID=your_client_id
SPOTIPY_CLIENT_SECRET=your_client_secret
SPOTIPY_REDIRECT_URI=http://127.0.0.1:8888/callback
```  

### 🗄️ Database configuration  
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.  
2. Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).  
3. Create a database named `spotify_db`.  
4. Import the `.sql` file from the `/sql` folder to generate the required tables.  

### ▶️ Run the data insertion script (V1)  
1. From the terminal, go to the `Spotify-Tool` folder:  

```bash
python spotify_to_mysql.py
```  

2. The script will ask for an artist name (e.g., `Imagine Dragons`).  
3. Retrieved tracks will be stored in the `spotify_db` database.  

## 👀 Results visualization  
- **From the local PHP API:**  
  [http://localhost/spotify-api/canciones.php](http://localhost/spotify-api/canciones.php)  
- **From the Vue.js frontend (optional in V1):**  
  [http://localhost:8080](http://localhost:8080)

### 🌐 Web Interface (Version 2)  
To use the **V2** web interface:  

1. Copy the `spotify-api` folder inside XAMPP's `htdocs` directory:  

   ```
   C:\xampp\htdocs\spotify-api\
   ```  

2. Ensure **Apache** and **MySQL** are running in XAMPP.  

3. Open your browser and go to:  

👉 [http://localhost/spotify-api/](http://localhost/spotify-api/)  

From there, you will have access to the artist search tool and the user registration form.  

### 📚 Credits

- **AUTHOR: ABAD GONZÁLEZ, CHRISTIAN**
- **TUTOR: GUALDA GÓMEZ, DAVID**
- **ACADEMIC YEAR: 2025/2026**
- **CENTER: REY JUAN CARLOS UNIVERSITY (URJC)**

🎶 *Music is emotion, and data can be too. This tool is an invitation to look at music from another perspective.*

---
