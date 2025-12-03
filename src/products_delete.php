<?php
require_once __DIR__ . '/db.php';
$pdo = db();
header('Content-Type: application/json; charset=utf-8');

function json_error($msg) { echo json_encode(['success' => false, 'message' => $msg]); exit; }

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) json_error('ID inválido');

$stmtSel = $pdo->prepare('SELECT img FROM produtos WHERE id = ?');
$stmtSel->execute([$id]);
$row = $stmtSel->fetch();
if (!$row) json_error('Produto não encontrado');
$imgPath = $row['img'];

$stmtDel = $pdo->prepare('DELETE FROM produtos WHERE id = ?');
$ok = $stmtDel->execute([$id]);
if (!$ok) json_error('Falha ao excluir');

if ($imgPath && $imgPath !== 'img/no-image-available.png') {
  $abs = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $imgPath);
  if (is_file($abs)) @unlink($abs);
}

echo json_encode(['success' => true]);
