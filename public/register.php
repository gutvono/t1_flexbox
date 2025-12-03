<?php
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="auth-wrapper">
  <section class="auth-card">
    <h1 class="mb-3 text-center" style="color: var(--secondary-color)">Cadastro</h1>
    <form method="POST" action="./handle_register.php" class="needs-validation" novalidate>
      <div class="form-horizontal mb-2">
        <label for="nome">Nome completo</label>
        <input class="input-text form-control" type="text" id="nome" name="nome" required>
      </div>
      <div class="form-horizontal mb-2">
        <label for="email">Email</label>
        <input class="input-text form-control" type="email" id="email" name="email" required>
      </div>
      <div class="form-horizontal mb-2">
        <label for="celular">Celular</label>
        <input class="input-text form-control" type="text" id="celular" name="celular" required>
      </div>
      <div class="form-horizontal mb-2">
        <label for="nivel">Nível</label>
        <select class="select form-select" id="nivel" name="nivel" required>
          <option value="usuario">Usuário</option>
          <option value="administrador">Administrador</option>
        </select>
      </div>
      <div class="form-horizontal mb-3">
        <label for="senha">Senha</label>
        <input class="input-text form-control" type="password" id="senha" name="senha" required>
      </div>
      <button class="btn btn-gradient w-100 mb-2" type="submit">Cadastrar</button>
      <div class="d-flex justify-content-between align-items-center">
        <a class="btn btn-link p-0" href="./login.php">Já tenho conta</a>
        <div class="voltar-bar">
          <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="history.length>1?history.back():window.location.href='/index.php'">Voltar</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.location.href='/index.php'">Home</button>
        </div>
      </div>
    </form>
  </section>
</body>
</html>
