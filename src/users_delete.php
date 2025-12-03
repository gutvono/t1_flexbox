<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
  echo json_encode(['success' => false, 'message' => 'ID inválido']);
  exit;
}
try {
  $pdo = db();
  $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id=?');
  $stmt->execute([$id]);
  echo json_encode(['success' => true]);
} catch (Throwable $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
