<?php
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Steemo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="superinfo-bg">
    <div class="superinfo">
      <p>Seg / Sex - 08:00 às 18:00</p>
      <a href="tel:+5521999999999">+55 21 9999-9999</a>
      <p>Rua dos Pinheiros, 300 - Jau - SP</p>
    </div>
  </div>

  <header>
    <div class="menu-bg">
      <div class="menu container">
        <div class="menu-logo">
          <a href="index.php"><img src="img/logo.png" alt="Steemo Logo"></a>
        </div>
        <div class="menu-hamburguer">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <nav class="menu-nav">
          <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#sobre">Sobre</a></li>
            <li><a href="#jogos">Jogos</a></li>
            <li><a href="#assinaturas">Assinaturas</a></li>
            <li><a href="#contato">Contato</a></li>
            <li><a href="public/login.php">Login</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <div class="userbar">
    <div id="userbar" class="container"></div>
  </div>

  <h1>Steemo - Aqui sua gameplay é no precinho</h1>

  <section class="sobre" id="sobre">
    <div class="sobre-info">
      <h2>Sobre</h2>
      <p>Steemo é uma empresa de jogos indie que se dedica a criar experiências de jogo únicas e emocionantes. Fundada
        em 2020, a Steemo tem como objetivo fornecer jogos no precinho para todos os jogadores, independentemente de seu
        nível de habilidade ou recursos financeiros.</p>
      <p>Nossa loja oferece uma ampla seleção de jogos indie e AAA com preços acessíveis, promoções frequentes e um
        sistema de recompensas que beneficia nossos clientes mais fiéis. Acreditamos que todos merecem acesso a grandes
        títulos sem comprometer o orçamento, por isso trabalhamos com os melhores distribuidores para garantir os
        melhores preços do mercado.</p>
    </div>
    <div class="sobre-imagens">
      <div class="sobre-img">
        <img src="img/sobre-1.png" alt="sobre-1">
      </div>
      <div class="sobre-img">
        <img src="img/sobre-2.png" alt="sobre-2">
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/src/jogos_component.php'; ?>

  <section class="assinaturas" id="assinaturas">
    <h2>Assinaturas</h2>
    <div class="assinaturas-item">
      <h3>Plano 1 - Mensal</h3>
      <p><sup>R$</sup> 29,99</p>
      <ul>
        <li>Até 30 títulos</li>
        <li>Download ilimitado</li>
        <li>Jogos gratuitos</li>
        <li>Suporte limitado</li>
      </ul>
      <a href="#">Assinar</a>
    </div>
    </div>
    <div class="assinaturas-item">
      <h3>Plano 2 - Trimestral</h3>
      <p><sup>R$</sup> 49,99</p>
      <ul>
        <li>Até 90 títulos</li>
        <li>Download ilimitado</li>
        <li>Jogos gratuitos</li>
        <li>Suporte limitado</li>
      </ul>
      <a href="#">Assinar</a>
    </div>
    </div>
    <div class="assinaturas-item">
      <h3>Plano 3 - Anual</h3>
      <p><sup>R$</sup> 149,99</p>
      <ul>
        <li>Todo catálogo disponível</li>
        <li>Download ilimitado</li>
        <li>Jogos gratuitos</li>
        <li>Suporte 24h</li>
      </ul>
      <a href="#">Assinar</a>
    </div>
    </div>
  </section>

  <section class="newsletter">
    <div class="newsletter-info">
      <h2>Newsletter</h2>
      <p>Inscreva-se para receber novidades, ofertas e atualizações do nosso catálogo.</p>
    </div>
    <form class="newsletter-form" action="#">
      <input type="email" name="email" id="email" placeholder="Seu email">
      <button type="submit">Inscrever-se</button>
    </form>
  </section>

  <section class="contato" id="contato">
    <h2>Contato</h2>
    <div class="contato-container">
      <div class="contato-item">
        <h3>Email</h3>
        <p>contato@steemo.com</p>
      </div>
      <div class="contato-item">
        <h3>Telefone</h3>
        <p>(21) 9999-9999</p>
      </div>
      <div class="contato-item">
        <h3>Endereço</h3>
        <p>Rua dos Pinheiros, 300 - Jau - SP</p>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2023 Steemo. Todos os direitos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuHamburguer = document.querySelector('.menu-hamburguer');
      const menuNav = document.querySelector('.menu-nav');
      
      menuHamburguer.addEventListener('click', function() {
        menuHamburguer.classList.toggle('ativo');
        menuNav.classList.toggle('ativo');
      });
      
      const menuLinks = document.querySelectorAll('.menu-nav a');
      menuLinks.forEach(link => {
        link.addEventListener('click', function() {
          menuHamburguer.classList.remove('ativo');
          menuNav.classList.remove('ativo');
        });
      });

      const userbar = document.getElementById('userbar');
      try {
        const auth = JSON.parse(localStorage.getItem('auth') || 'null');
        if (auth && auth.username) {
          const role = auth.role || 'usuario';
          const wrapper = document.createElement('div');
          wrapper.className = 'user-dropdown';
          wrapper.innerHTML = `
            <button class="user-btn" aria-haspopup="true" aria-expanded="false">${auth.username}</button>
            <ul class="user-menu" role="menu" hidden>
              <li role="menuitem"><a href="#" id="logout-link">Logout</a></li>
              ${role === 'administrador' ? '<li role="menuitem"><a href="public/products.php" id="manage-products-link">Gerenciar produtos</a></li>' : ''}
              ${role === 'administrador' ? '<li role="menuitem"><a href="public/users.php" id="manage-users-link">Gerenciar usuarios</a></li>' : ''}
            </ul>`;
          userbar.appendChild(wrapper);
          const btn = wrapper.querySelector('.user-btn');
          const menu = wrapper.querySelector('.user-menu');
          btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            if (expanded) { menu.hidden = true; } else { menu.hidden = false; }
          });
          document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) { menu.hidden = true; btn.setAttribute('aria-expanded','false'); }
          });
          document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { menu.hidden = true; btn.setAttribute('aria-expanded','false'); }
          });
          const logout = wrapper.querySelector('#logout-link');
          logout.addEventListener('click', (e) => {
            e.preventDefault();
            try { localStorage.removeItem('auth'); } catch(err) {}
            window.location.href = '/index.php';
          });
        }
      } catch(err) {}
    });
  </script>
</body>
</html>
