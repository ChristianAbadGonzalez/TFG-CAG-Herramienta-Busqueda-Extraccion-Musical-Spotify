<?php
/**
 * buscar_artista.php
 *
 * Busca un artista con el script de Python, vuelca canciones en MySQL
 * y registra la consulta del usuario en la tabla `queries`.
 */


// Permite peticiones desde cualquier origen (Cross-Origin Resource Sharing - CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

// Preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Llamada AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
          && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isAjax) {
    header("Content-Type: application/json; charset=UTF-8");
}


// Validación de parámetros
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;


if ($nombre === '' || $user_id <= 0) {
    if ($isAjax) {
        http_response_code(400);
        echo json_encode([
            "status"  => "error",
            "mensaje" => "Faltan parámetros válidos: 'nombre' o 'user_id'."
        ]);
        exit;
    } else {
        header("Location: consulta_artista.html?error=1");
        exit;
    }
}


// Ejecució de los scripts de Python
$python = "C:\\Users\\M7600\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
$script = "C:\\Users\\M7600\\Desktop\\TFG\\TFG CHRISTIAN\\Spotify-Tool\\spotify_to_mysql.py";

// Capturamos el nombre del artista desde la solicitud de búsqueda
$artistaShell = escapeshellarg($nombre);
$comando = "\"$python\" \"$script\" $artistaShell";

// Capturamos salida y errores
$output = shell_exec($comando . " 2>&1");
// Guardamos log (append)
@file_put_contents(
    "C:\\Users\\M7600\\Desktop\\TFG\\TFG CHRISTIAN\\Spotify-Tool\\log.txt",
    "[" . date('Y-m-d H:i:s') . "] CMD: $comando\n$output\n",
    FILE_APPEND
);

// Conexión a BD y registro de consultas
require_once 'db_connection.php';

// 1) Consultamos canciones del artista
$stmt = $conn->prepare("
    SELECT nombre, artista, album, fecha_lanzamiento, popularidad
    FROM canciones
    WHERE artista = ?
    ORDER BY fecha_lanzamiento DESC
    LIMIT 5
");

// Vinculamos parámetros
$stmt->bind_param("s", $nombre);

// Ejecutamos la consulta
if (!$stmt->execute()) {
    // Manejo de errores
    if ($isAjax) {
        http_response_code(500);
        echo json_encode([
            "status"  => "error",
            "mensaje" => "Error al consultar canciones: " . $stmt->error
        ]);
        $stmt->close();
        $conn->close();
        exit;
    } else {
        header("Location: consulta_artista.html?user_id=$user_id&error=1");
        $stmt->close();
        $conn->close();
        exit;
    }
}
// Obtenemos resultados
$result = $stmt->get_result();

// Filtramos resultados duplicados
$alreadyInserted = [];
// Para cada canción obtenida
while ($row = $result->fetch_assoc()) {
    // Si ya fue insertada, la omitimos
    if (in_array($row['nombre'], $alreadyInserted, true)) {
        continue;
    }
    // Insertamos en `queries`
    $insert = $conn->prepare("
        INSERT INTO queries (user_id, song_name, artist_name, album_name, release_date, popularity)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    // Vinculamos parámetros
    $insert->bind_param(
        "issssi",
        $user_id,
        $row['nombre'],
        $row['artista'],
        $row['album'],
        $row['fecha_lanzamiento'],
        $row['popularidad']
    );
    // Ejecutamos la inserción
    $ok = $insert->execute();
    // Verificamos si hubo éxito. 
    $insert->close();

    if ($ok) {
        $alreadyInserted[] = $row['nombre'];
    }
}

$stmt->close();
$conn->close();

// Respuesta final
if ($isAjax) {
    echo json_encode([
        "status"  => "ok",
        "mensaje" => "Artista '{$nombre}' importado y consultas registradas para el usuario ID {$user_id}."
    ]);
    exit;
} else {
    header("Location: consulta_artista.html?user_id=$user_id&success=1");
    exit;
}
