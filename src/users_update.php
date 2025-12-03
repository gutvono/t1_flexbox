<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$celular = isset($_POST['celular']) ? trim($_POST['celular']) : '';
$nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : '';
if ($id <= 0 || $nome === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $celular === '' || ($nivel !== 'usuario' && $nivel !== 'administrador')) {
  echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
  exit;
}
try {
  $pdo = db();
  $stmt = $pdo->prepare('UPDATE usuarios SET nome=?, email=?, celular=?, nivel=? WHERE id=?');
  $stmt->execute([$nome, $email, $celular, $nivel, $id]);
  echo json_encode(['success' => true]);
} catch (Throwable $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
