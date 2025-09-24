<?php
/**
 * user_queries.php
 *
 * Este script permite registrar manualmente una consulta realizada por un usuario,
 * guardando los detalles de la canción consultada en la tabla `queries`.
 *
 * Requiere:
 * - Una tabla `queries` con los campos:
 *   id, user_id, song_name, artist_name, album_name, release_date, popularity, queried_at.
 * - Un archivo de conexión a base de datos funcional (`db_connection.php`).
 */

// Permitir peticiones desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
// Establecer tipo de contenido como JSON
header("Content-Type: application/json; charset=UTF-8");

// Verificar existencia del archivo de conexión
if (!file_exists('db_connection.php')) {
    http_response_code(500);
    echo json_encode(["error" => "Archivo de conexión a la base de datos no encontrado."]);
    exit;
}
include 'db_connection.php';

// Verificar que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener los datos enviados mediante POST
    $user_id = $_POST['user_id'] ?? '';
    $song_name = $_POST['song_name'] ?? '';
    $artist_name = $_POST['artist_name'] ?? '';
    $album_name = $_POST['album_name'] ?? '';
    $release_date = $_POST['release_date'] ?? '';
    $popularity = $_POST['popularity'] ?? '';

    // Validar que los campos obligatorios están presentes
    if (empty($user_id) || empty($song_name) || empty($artist_name)) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos obligatorios: user_id, song_name o artist_name"]);
        exit;
    }

    // Preparar la sentencia SQL para insertar los datos en la tabla `queries`
    $stmt = $conn->prepare(
        "INSERT INTO queries (user_id, song_name, artist_name, album_name, release_date, popularity, queried_at)
         VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );
    $stmt->bind_param("issssi", $user_id, $song_name, $artist_name, $album_name, $release_date, $popularity);

    // Ejecutar la inserción y devolver respuesta
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Consulta registrada correctamente."]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    // Cerrar recursos
    $stmt->close();
    $conn->close();
    exit;

} 

// Obtener todos los usuarios registrados (para el dropdown)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, username FROM users ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $usuarios = [];

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    echo json_encode($usuarios);
    $conn->close();
    exit;
}


// Si la solicitud no es ni POST ni GET, devolver un error 405
http_response_code(405);
echo json_encode(["error" => "Método no permitido. Usa GET o POST."]);
?>
