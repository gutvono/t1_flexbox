<?php
require_once __DIR__ . '/../src/db.php';
$pdo = db();
$users = [];
try {
  $users = $pdo->query('SELECT id, nome, email, celular, nivel, created_at FROM usuarios ORDER BY id ASC')->fetchAll();
} catch (Throwable $e) {
  $users = [];
}
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuários</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="auth-wrapper">
  <section class="auth-card">
    <h1 class="mb-3" style="color: var(--secondary-color)">Usuários</h1>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Celular</th>
            <th>Nível</th>
            <th>Criado em</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr data-id="<?php echo (int)$u['id']; ?>">
              <td class="cell-id"><?php echo (int)$u['id']; ?></td>
              <td class="cell-nome"><?php echo htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="cell-email"><?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="cell-celular"><?php echo htmlspecialchars($u['celular'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="cell-nivel"><?php echo htmlspecialchars($u['nivel'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="cell-created"><?php echo htmlspecialchars($u['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td>
                <button class="btn btn-sm btn-primary btn-edit">Editar</button>
                <button class="btn btn-sm btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#confirmModal">Excluir</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end mt-2">
      <button type="button" class="btn btn-outline-secondary me-2" onclick="history.length>1?history.back():window.location.href='/index.html'">Voltar</button>
      <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/index.php'">Home</button>
    </div>
  </section>

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar exclusão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja excluir este usuário?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toInputs = (tr) => {
      const nome = tr.querySelector('.cell-nome').textContent;
      const email = tr.querySelector('.cell-email').textContent;
      const celular = tr.querySelector('.cell-celular').textContent;
      const nivel = tr.querySelector('.cell-nivel').textContent;
      tr.querySelector('.cell-nome').innerHTML = `<input class="form-control" value="${nome}">`;
      tr.querySelector('.cell-email').innerHTML = `<input class="form-control" value="${email}">`;
      tr.querySelector('.cell-celular').innerHTML = `<input class="form-control" value="${celular}">`;
      tr.querySelector('.cell-nivel').innerHTML = `<select class="form-select"><option ${nivel==='usuario'?'selected':''} value="usuario">Usuário</option><option ${nivel==='administrador'?'selected':''} value="administrador">Administrador</option></select>`;
      const actions = tr.querySelector('td:last-child');
      actions.innerHTML = `<button class="btn btn-sm btn-success btn-save">Salvar alterações</button> <button class="btn btn-sm btn-secondary btn-cancel">Descartar</button>`;
    };

    const toTexts = (tr, data) => {
      tr.querySelector('.cell-nome').textContent = data.nome;
      tr.querySelector('.cell-email').textContent = data.email;
      tr.querySelector('.cell-celular').textContent = data.celular;
      tr.querySelector('.cell-nivel').textContent = data.nivel;
      const actions = tr.querySelector('td:last-child');
      actions.innerHTML = `<button class="btn btn-sm btn-primary btn-edit">Editar</button> <button class="btn btn-sm btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#confirmModal">Excluir</button>`;
    };

    document.addEventListener('click', async (e) => {
      const tr = e.target.closest('tr');
      if (!tr) return;
      const id = tr.dataset.id;
      if (e.target.classList.contains('btn-edit')) {
        toInputs(tr);
      } else if (e.target.classList.contains('btn-save')) {
        const nome = tr.querySelector('.cell-nome input').value.trim();
        const email = tr.querySelector('.cell-email input').value.trim();
        const celular = tr.querySelector('.cell-celular input').value.trim();
        const nivel = tr.querySelector('.cell-nivel select').value;
        try {
          const resp = await fetch('../src/users_update.php', {
            method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id, nome, email, celular, nivel })
          });
          const json = await resp.json();
          if (json.success) {
            toTexts(tr, { nome, email, celular, nivel });
          } else {
            alert('Erro ao salvar: ' + json.message);
          }
        } catch (err) {
          alert('Falha na requisição');
        }
      } else if (e.target.classList.contains('btn-cancel')) {
        const nome = tr.querySelector('.cell-nome input')?.value || tr.querySelector('.cell-nome').textContent;
        const email = tr.querySelector('.cell-email input')?.value || tr.querySelector('.cell-email').textContent;
        const celular = tr.querySelector('.cell-celular input')?.value || tr.querySelector('.cell-celular').textContent;
        const nivel = tr.querySelector('.cell-nivel select')?.value || tr.querySelector('.cell-nivel').textContent;
        toTexts(tr, { nome, email, celular, nivel });
      } else if (e.target.classList.contains('btn-delete')) {
        document.getElementById('confirmDelete').onclick = async () => {
          try {
            const resp = await fetch('../src/users_delete.php', {
              method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams({ id })
            });
            const json = await resp.json();
            if (json.success) {
              tr.remove();
              bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            } else {
              alert('Erro ao excluir: ' + json.message);
            }
          } catch (err) {
            alert('Falha na requisição');
          }
        };
      }
    });
  </script>
</body>
</html>
