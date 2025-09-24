<?php
/**
 * get_users.php
 * Devuelve en JSON la lista de usuarios (id, username) para poblar el selector del index.
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

// No cache (importante para que el index no “vea” una lista vieja)
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (!file_exists(__DIR__ . '/db_connection.php')) {
    http_response_code(500);
    echo json_encode(["error" => "No se encuentra db_connection.php"]);
    exit;
}
require_once __DIR__ . '/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido. Usa GET."]);
    $conn->close();
    exit;
}

$sql = "SELECT id, username FROM users ORDER BY created_at DESC, id DESC";
$result = $conn->query($sql);

if ($result === false) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
    $conn->close();
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        "id"       => (int)$row["id"],
        "username" => $row["username"]
    ];
}

// (Opcional) Limpia cualquier output previo si hubiera, para evitar “Unexpected token”
if (ob_get_length()) { ob_clean(); }

echo json_encode($users, JSON_UNESCAPED_UNICODE);
$conn->close();
exit;
