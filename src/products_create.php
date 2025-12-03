<?php
require_once __DIR__ . '/db.php';
$pdo = db();
header('Content-Type: application/json; charset=utf-8');

function json_error($msg) { echo json_encode(['success' => false, 'message' => $msg]); exit; }

$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$valor = trim($_POST['valor'] ?? '');

if (strlen($titulo) < 3 || strlen($titulo) > 100) json_error('Título deve ter entre 3 e 100 caracteres');
if (strlen($descricao) < 10 || strlen($descricao) > 500) json_error('Descrição deve ter entre 10 e 500 caracteres');
if (!is_numeric($valor)) json_error('Valor inválido');
$valorNum = floatval($valor);
if ($valorNum < 0.01) json_error('Valor mínimo é 0.01');

$imgRelPath = 'img/no-image-available.png';
if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
  $file = $_FILES['imagem'];
  if ($file['size'] > 5 * 1024 * 1024) json_error('Imagem excede 5MB');
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if ($mime !== 'image/png' || $ext !== 'png') json_error('Apenas PNG é permitido');
  $dim = getimagesize($file['tmp_name']);
  if (!$dim || $dim[0] < 100 || $dim[1] < 100) json_error('Imagem deve ter no mínimo 100x100');
  $base = 'produto_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
  $absDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'img';
  if (!is_dir($absDir)) json_error('Diretório de imagens não existe');
  $absPath = $absDir . DIRECTORY_SEPARATOR . $base;
  if (!move_uploaded_file($file['tmp_name'], $absPath)) json_error('Falha ao salvar imagem');
  $imgRelPath = 'img/' . $base;
}

$stmt = $pdo->prepare('INSERT INTO produtos (titulo, descricao, img, valor) VALUES (?, ?, ?, ?)');
$ok = $stmt->execute([$titulo, $descricao, $imgRelPath, number_format($valorNum, 2, '.', '')]);
if (!$ok) json_error('Erro ao inserir');
$id = (int)$pdo->lastInsertId();
echo json_encode(['success' => true, 'product' => [
  'id' => $id,
  'titulo' => $titulo,
  'descricao' => $descricao,
  'img' => $imgRelPath,
  'valor' => number_format($valorNum, 2, '.', '')
]]);
