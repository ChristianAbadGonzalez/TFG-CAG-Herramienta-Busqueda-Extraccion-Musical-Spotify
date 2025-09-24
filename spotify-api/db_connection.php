<?php
// db_connection.php

// Configuración de la conexión a la base de datos
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "spotify_db";

// Desactivación de las notificaciones de errores de conexión
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $password, $dbname);

// Verificacón de conexión
if ($conn->connect_errno) {
  header("Content-Type: application/json; charset=UTF-8");
  http_response_code(500);
  echo json_encode([
    "error" => "DB connect failed",
    "details" => $conn->connect_error
  ]);
  exit;
}
// Configuración de la conexión
$conn->set_charset("utf8mb4");
?>
