<?php
require_once __DIR__ . '/../src/db.php';
$pdo = db();
$rows = [];
try {
  $rows = $pdo->query('SELECT id, titulo, descricao, img, valor FROM produtos ORDER BY id ASC')->fetchAll();
} catch (Throwable $e) {
  $rows = [];
}
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="auth-wrapper">
  <section class="auth-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="mb-0" style="color: var(--secondary-color)">Produtos</h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#produtoModal" id="addProdutoBtn">Adicionar Produto</button>
    </div>
    <div class="table-responsive">
      <table class="table table-striped align-middle" id="produtosTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Imagem</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr data-id="<?php echo (int)$r['id']; ?>" data-img="<?php echo htmlspecialchars($r['img'], ENT_QUOTES, 'UTF-8'); ?>">
              <td class="p-id"><?php echo (int)$r['id']; ?></td>
              <td class="p-titulo"><?php echo htmlspecialchars($r['titulo'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="p-descricao"><?php echo htmlspecialchars($r['descricao'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="p-valor"><?php echo number_format((float)$r['valor'], 2, ',', '.'); ?></td>
              <td class="p-img"><img src="/<?php echo htmlspecialchars($r['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="img" style="width:64px;height:64px;object-fit:cover;border:1px solid var(--accent-color);border-radius:4px"></td>
              <td>
                <button class="btn btn-sm btn-primary btn-edit">Editar</button>
                <button class="btn btn-sm btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDelete">Excluir</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (!$rows) { ?>
        <div class="sem-produtos mt-2">Não temos nenhum jogo disponível no momento, desculpe. Aguarde, novidades em breve!</div>
      <?php } ?>
    </div>
    <div class="d-flex justify-content-end mt-2">
      <button type="button" class="btn btn-outline-secondary me-2" onclick="history.length>1?history.back():window.location.href='/index.php'">Voltar</button>
      <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/index.php'">Home</button>
    </div>
  </section>

  <div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="produtoModalLabel">Adicionar Produto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="produtoForm">
            <input type="hidden" name="id" id="produtoId">
            <div class="mb-3">
              <label for="titulo" class="form-label">Título</label>
              <input type="text" class="form-control" id="titulo" name="titulo" required minlength="3" maxlength="100" aria-required="true" aria-label="Título do produto">
              <div class="invalid-feedback">Título deve ter entre 3 e 100 caracteres.</div>
            </div>
            <div class="mb-3">
              <label for="descricao" class="form-label">Descrição</label>
              <textarea class="form-control" id="descricao" name="descricao" required minlength="10" maxlength="500" rows="4" aria-required="true" aria-label="Descrição do produto"></textarea>
              <div class="invalid-feedback">Descrição deve ter entre 10 e 500 caracteres.</div>
            </div>
            <div class="mb-3">
              <label for="valor" class="form-label">Valor (R$)</label>
              <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" required aria-required="true" aria-label="Valor do produto">
              <div class="invalid-feedback">Informe um valor decimal válido, mínimo R$ 0,01.</div>
            </div>
            <div class="mb-3">
              <label for="imagem" class="form-label">Imagem (PNG, até 5MB)</label>
              <input type="file" class="form-control" id="imagem" name="imagem" accept="image/png" aria-label="Imagem do produto">
              <div class="form-text">Dimensões mínimas 100x100px. Se não enviar, usaremos uma imagem padrão.</div>
            </div>
          </form>
          <div id="produtoErrors" class="alert alert-danger d-none" role="alert"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="saveProdutoBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="saveSpinner"></span>
            <span id="saveTexto">Salvar</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar exclusão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja excluir este produto? Esta ação remove também a imagem associada.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let modoEdicao = false; let idEdicao = null;
    const modalEl = document.getElementById('produtoModal');
    const produtoModal = new bootstrap.Modal(modalEl);
    const errorsEl = document.getElementById('produtoErrors');
    const saveBtn = document.getElementById('saveProdutoBtn');
    const saveSpinner = document.getElementById('saveSpinner');
    const saveTexto = document.getElementById('saveTexto');

    document.getElementById('addProdutoBtn').addEventListener('click', () => {
      modoEdicao = false; idEdicao = null;
      document.getElementById('produtoModalLabel').textContent = 'Adicionar Produto';
      document.getElementById('produtoForm').reset();
      errorsEl.classList.add('d-none'); errorsEl.textContent = '';
    });

    document.addEventListener('click', (e) => {
      const tr = e.target.closest('tr');
      if (!tr) return;
      const id = tr.dataset.id;
      if (e.target.classList.contains('btn-edit')) {
        modoEdicao = true; idEdicao = id;
        document.getElementById('produtoModalLabel').textContent = 'Editar Produto';
        document.getElementById('produtoId').value = id;
        document.getElementById('titulo').value = tr.querySelector('.p-titulo').textContent.trim();
        document.getElementById('descricao').value = tr.querySelector('.p-descricao').textContent.trim();
        const valorTxt = tr.querySelector('.p-valor').textContent.trim().replace('.', '').replace(',', '.');
        document.getElementById('valor').value = parseFloat(valorTxt).toFixed(2);
        errorsEl.classList.add('d-none'); errorsEl.textContent = '';
        produtoModal.show();
      }
    });

    saveBtn.addEventListener('click', async () => {
      errorsEl.classList.add('d-none'); errorsEl.textContent = '';
      const form = document.getElementById('produtoForm');
      if (!form.reportValidity()) return;
      saveBtn.disabled = true; saveSpinner.classList.remove('d-none'); saveTexto.textContent = 'Salvando...';
      const fd = new FormData(form);
      const url = modoEdicao ? '../src/products_update.php' : '../src/products_create.php';
      try {
        const resp = await fetch(url, { method: 'POST', body: fd });
        const json = await resp.json();
        if (!json.success) throw new Error(json.message || 'Erro ao salvar');
        atualizarTabela(json.product, modoEdicao);
        produtoModal.hide();
      } catch (err) {
        errorsEl.textContent = err.message; errorsEl.classList.remove('d-none');
      } finally {
        saveBtn.disabled = false; saveSpinner.classList.add('d-none'); saveTexto.textContent = 'Salvar';
      }
    });

    function atualizarTabela(prod, edit) {
      const tbody = document.querySelector('#produtosTable tbody');
      if (edit) {
        const tr = tbody.querySelector(`tr[data-id="${prod.id}"]`);
        tr.querySelector('.p-titulo').textContent = prod.titulo;
        tr.querySelector('.p-descricao').textContent = prod.descricao;
        tr.querySelector('.p-valor').textContent = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(parseFloat(prod.valor));
        tr.querySelector('.p-img img').src = '/' + prod.img;
        tr.dataset.img = prod.img;
      } else {
        const tr = document.createElement('tr');
        tr.setAttribute('data-id', prod.id);
        tr.setAttribute('data-img', prod.img);
        tr.innerHTML = `
          <td class="p-id">${prod.id}</td>
          <td class="p-titulo">${prod.titulo}</td>
          <td class="p-descricao">${prod.descricao}</td>
          <td class="p-valor">${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(parseFloat(prod.valor))}</td>
          <td class="p-img"><img src="/${prod.img}" alt="img" style="width:64px;height:64px;object-fit:cover;border:1px solid var(--accent-color);border-radius:4px"></td>
          <td>
            <button class="btn btn-sm btn-primary btn-edit">Editar</button>
            <button class="btn btn-sm btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDelete">Excluir</button>
          </td>`;
        tbody.appendChild(tr);
      }
    }

    let idParaExcluir = null;
    document.addEventListener('click', (e) => {
      const tr = e.target.closest('tr');
      if (e.target.classList.contains('btn-delete')) {
        idParaExcluir = tr?.dataset?.id || null;
      }
    });
    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
      if (!idParaExcluir) return;
      try {
        const resp = await fetch('../src/products_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ id: idParaExcluir }) });
        const json = await resp.json();
        if (json.success) {
          const tr = document.querySelector(`#produtosTable tbody tr[data-id="${idParaExcluir}"]`);
          tr?.remove();
          bootstrap.Modal.getInstance(document.getElementById('confirmDelete')).hide();
        } else {
          alert('Erro ao excluir: ' + (json.message || ''));
        }
      } catch(err) { alert('Falha na exclusão'); }
    });
  </script>
</body>
</html>
