<?php
require_once __DIR__ . '/db.php';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
$erros = [];
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $erros[] = 'Email inválido'; }
if ($senha === '') { $erros[] = 'Senha é obrigatória'; }
if (count($erros) === 0) {
  try {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT nome, nivel FROM usuarios WHERE email = ? AND senha = ? LIMIT 1');
    $stmt->execute([$email, $senha]);
    $user = $stmt->fetch();
    if ($user) {
      $msg = 'Usuário logado com sucesso';
    } else {
      $msg = 'Credenciais inválidas';
    }
  } catch (Throwable $e) {
    $msg = 'Erro ao autenticar: ' . $e->getMessage();
  }
} else {
  $msg = implode(' | ', $erros);
}
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="auth-wrapper">
  <main class="auth-card">
    <h1>Login</h1>
    <p><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></p>
    <p><a href="/public/login.php">Voltar</a> | <a href="/public/register.php">Cadastre-se</a></p>
  </main>
  <?php if (isset($user) && $user) { ?>
    <script>
      (function(){
        try {
          var payload = { username: <?php echo json_encode($user['nome']); ?>, role: <?php echo json_encode($user['nivel']); ?> };
          localStorage.setItem('auth', JSON.stringify(payload));
        } catch(e) {}
        setTimeout(function(){ window.location.href = '/index.php'; }, 1500);
      })();
    </script>
  <?php } ?>
</body>
</html>
