<?php
require_once __DIR__ . '/config.php';
$pdo = new PDO('mysql:host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS);
$pdo->exec('SET NAMES utf8mb4');
$stmt = $pdo->query('SELECT id, titulo, descricao, img, valor FROM produtos');
$produtos = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<section class="jogos" id="jogos">
  <h2>Jogos</h2>
  <?php if (!$produtos) { ?>
    <div class="sem-produtos">
      Não temos nenhum jogo disponível no momento, desculpe. Aguarde, novidades em breve!
    </div>
  <?php } else { ?>
    <div class="jogos-container">
      <?php foreach ($produtos as $p) { ?>
        <div class="jogos-item">
          <img src="<?php echo htmlspecialchars($p['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="jogos-<?php echo (int)$p['id']; ?>">
          <h3><?php echo htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
          <p><?php echo htmlspecialchars($p['descricao'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><sup>R$</sup> <?php echo number_format((float)$p['valor'], 2, ',', '.'); ?></p>
          <a href="#">Comprar</a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</section>
