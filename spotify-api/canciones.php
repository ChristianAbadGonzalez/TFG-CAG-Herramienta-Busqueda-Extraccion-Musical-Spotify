<?php
/**
    * canciones.php
    *
    * Este script se encarga de recuperar todas las canciones almacenadas en la base de datos MySQL
    * y devolverlas en formato JSON, listo para ser consumido por el frontend.
    *
    * Requiere:
    * - Conexión válida a una base de datos MySQL.
    * - Una tabla llamada `canciones` con las columnas:
    *   id, nombre, artista, album, fecha_lanzamiento, popularidad.
*/

// Permite peticiones desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
// Establece el tipo de contenido a JSON
header("Content-Type: application/json; charset=UTF-8");

// Parámetros de conexión
$host = "localhost";
$user = "root";
$password = "";
$dbname = "spotify_db";

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Comprobar si la conexión ha fallado
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "Conexión fallida: " . $conn->connect_error
    ]);
    exit();
}

// Consulta SQL para recuperar todas las canciones
$sql = "SELECT id, nombre, artista, album, fecha_lanzamiento, popularidad FROM canciones";
$result = $conn->query($sql);

// Inicializar arreglo de respuesta
$canciones = [];

// Comprobar si la consulta se ejecutó correctamente
if ($result && $result->num_rows > 0) {
    // Guardar cada fila como un objeto en el array
    while ($row = $result->fetch_assoc()) {
        $canciones[] = $row;
    }
}

// Devolver el array como JSON (preservando acentos y caracteres especiales)
echo json_encode($canciones, JSON_UNESCAPED_UNICODE);

// Cerrar conexión
$conn->close();
?>
