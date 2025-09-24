<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
ini_set('display_errors','0'); ini_set('log_errors','1');

require_once __DIR__ . '/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success'=>false,'error'=>'Método no permitido (POST)']);
  exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');

if ($username === '' || $email === '') {
  http_response_code(400);
  echo json_encode(['success'=>false,'error'=>'Faltan username o email']);
  exit;
}

// ¿existe?
$chk = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
$chk->bind_param("ss", $username, $email);
$chk->execute(); $chk->store_result();
if ($chk->num_rows>0) {
  $chk->close();
  http_response_code(409);
  echo json_encode(['success'=>false,'error'=>'El usuario o email ya existe']);
  exit;
}
$chk->close();

// password_hash NOT NULL
$hash = password_hash(bin2hex(random_bytes(6)), PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?,?,?)");
$stmt->bind_param("sss", $username, $email, $hash);

if ($stmt->execute()) {
  $payload = ['success'=>true,'message'=>'Usuario registrado correctamente.','user'=>['id'=>$stmt->insert_id,'username'=>$username,'email'=>$email]];
  if (ob_get_level()) ob_clean();
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
} else {
  http_response_code(500);
  if (ob_get_level()) ob_clean();
  echo json_encode(['success'=>false,'error'=>'Error en BD: '.$stmt->error], JSON_UNESCAPED_UNICODE);
}
$stmt->close(); $conn->close(); exit;
