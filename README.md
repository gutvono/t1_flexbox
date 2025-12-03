# Steemo Login/Cadastro (Protótipo)

## Visão Geral

- Adiciona telas de `login` e `cadastro` com PHP.
- Integra com MySQL em container Docker.
- Estrutura organizada em `public`, `src`, `scripts`, `docker`.
- Senhas armazenadas em texto plano e sem validações robustas.

## Estrutura de Pastas

- `public/` páginas acessíveis: `login.php`, `register.php`.
- `src/` backend PHP: `config.php`, `db.php`, `handle_login.php`, `handle_register.php`.
- `scripts/` SQL: `init.sql` (criação), `seed.sql` (popular).
- `docker/` Dockerfile do MySQL.

## Executar o MySQL com Docker

1. Construir imagem (no diretório raiz do projeto):
   - `docker build -t steemo-mysql -f docker/Dockerfile .`
2. Subir container:
   - `docker run -d --name steemo-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=steemo_db -e MYSQL_USER=steemo_user -e MYSQL_PASSWORD=steemo_pass steemo-mysql`

O banco ficará acessível em `127.0.0.1:3306`.

## Rodar os Scripts SQL para popular banco de dados

Executar comandos:
- `docker exec steemo-mysql mysql -uroot -proot -e "SOURCE /scripts/init.sql"`
- `docker exec steemo-mysql mysql -uroot -proot -e "SOURCE /scripts/seed.sql"`

Opção B (montar scripts ao iniciar para auto-inicialização):
- `docker run -d --name steemo-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=steemo_db -e MYSQL_USER=steemo_user -e MYSQL_PASSWORD=steemo_pass -v ${PWD}/scripts:/docker-entrypoint-initdb.d steemo-mysql`

Observação: o Dockerfile já copia `scripts/init.sql` e `scripts/seed.sql` para `/scripts/` dentro do container e cria symlinks em `/usr/local/bin`. Assim, os caminhos `init.sql` e `seed.sql` ficam disponíveis no `PATH`.

## Rodar o PHP (Servidor embutido)

- Requer PHP instalado localmente.
- No diretório do projeto (raiz):
  - `php -S localhost:8080 -t .`
- Acesse:
  - `http://localhost:8080/index.php`
  - `http://localhost:8080/public/login.php`
  - `http://localhost:8080/public/register.php`
  - Observação: Servir a raiz (`-t .`) garante o acesso aos arquivos em `src/` pelos wrappers em `public/handle_*.php` e ao CSS/IMG em `/css` e `/img`.

## Banco de Dados

- Banco: `steemo_db`
- Tabela: `usuarios`
  - `id` INT AUTO_INCREMENT PRIMARY KEY
  - `nome` VARCHAR(255) NOT NULL
  - `email` VARCHAR(255) NOT NULL UNIQUE
  - `celular` VARCHAR(20) NOT NULL
  - `nivel` ENUM('usuario','administrador') NOT NULL
  - `senha` VARCHAR(255) NOT NULL
  - `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP

## Credenciais de Teste (Seeder)

- `alice@example.com` / `123456` (usuario)
- `bruno@example.com` / `admin123` (administrador)
- `carla@example.com` / `senha` (usuario)

## Integração no Site

- A home agora é `index.php`, mantendo aparência e funcionalidade idênticas à anterior.
- O menu da página principal contém o link `Login` apontando para `public/login.php`.
- A listagem de usuários é acessível exclusivamente pelo dropdown do usuário administrador (fora do navbar).

## Observações

- Projeto em caráter de protótipo, sem criptografia ou segurança avançada.
- Qualquer usuário pode escolher nível `administrador` no cadastro.

## Pré-requisitos

- MySQL Workbench (mais recente)
  - Download: https://dev.mysql.com/downloads/workbench/
  - Conexão passo-a-passo:
    - Abrir Workbench → `+` para nova conexão
    - `Hostname`: `127.0.0.1`
    - `Port`: `3306`
    - `Username`: `root`
    - `Password`: `root`
    - Testar conexão e salvar
  - Verificação: conectar e executar `SHOW DATABASES;` (deve listar `steemo_db` após rodar o init).

- WSL2 com Ubuntu (Windows)
  - Guia oficial: https://learn.microsoft.com/windows/wsl/install
  - Habilitar WSL: executar em PowerShell (Admin) `wsl --install`
  - Instalar Ubuntu pela Microsoft Store (Ubuntu 22.04 recomendado)
  - Verificação: `wsl -l -v` (deve mostrar Ubuntu com versão `2`).

- Docker Desktop (estável mais recente)
  - Download: https://www.docker.com/products/docker-desktop/
  - Configurações recomendadas:
    - Habilitar WSL2 integration para a distro Ubuntu
    - Recursos: 2 CPUs, 4GB RAM (mínimo) para o MySQL
  - Verificação: `docker --version` e `docker run hello-world`.

- PHP (8.1+ recomendado; 8.2 testado)
  - Download (Windows): https://windows.php.net/download
  - No Linux (WSL/Ubuntu): `sudo apt update && sudo apt install php php-cli`
  - Extensões necessárias:
    - `pdo_mysql`
  - Verificação:
    - `php -v`
    - Windows PowerShell: `php -m | findstr pdo_mysql`
    - Linux: `php -m | grep pdo_mysql`

## Comandos úteis de verificação

- Docker: `docker ps`, `docker logs steemo-mysql --tail=50`
- MySQL (CLI): `mysql -h 127.0.0.1 -uroot -proot -e "SHOW TABLES IN steemo_db;"`
- PHP servidor embutido: `php -S localhost:8080 -t public`

## CSS (classes principais adicionadas)

- `pagina`: container responsivo para páginas internas
- `form-card`: cartão de formulário com borda/tema
- `form-row`: agrupamento de label/campo
- `input-text` e `select`: campos de entrada
- `form-actions`: barra de ações do formulário
- `btn`, `btn-primario`, `btn-link`: botões consistentes com o tema

## Funcionalidades

- Login
  - Formulário essencial em `public/login.php` sem cabeçalho/rodapé
  - Campos: Email e Senha, botão com gradiente nas cores do tema
  - Botões Voltar: histórico e Home com fallback confiável
  - Feedback simples de sucesso/erro após envio
  - Após sucesso, os dados do usuário são salvos no `localStorage` (`username`, `role`) e ocorre redirecionamento automático para a home

- Cadastro
  - Formulário essencial em `public/register.php` sem cabeçalho/rodapé
  - Campos: Nome, Email, Celular, Nível (Usuário/Administrador), Senha
  - Botões Voltar: histórico e Home
  - Salva no banco com validação básica de email

- Listagem de Usuários
  - Página dedicada em `public/users.php` com tabela responsiva
  - Colunas: ID, Nome, Email, Celular, Nível, Criado em
  - Ações por linha:
    - Editar: transforma a linha em inputs, com “Salvar alterações” e “Descartar”
    - Excluir: modal de confirmação e remoção definitiva
  - CRUD completo:
    - Create: via cadastro
    - Read: via listagem
    - Update: `src/users_update.php` (POST)
    - Delete: `src/users_delete.php` (POST)
  - Feedback visual em erros, mantendo identidade visual com Bootstrap + tema
  - Acesso pela navbar da home via botão `Usuarios` ou diretamente em `http://localhost:8080/public/users.php`
  - Requisito técnico: MySQL (container) em execução e scripts `init.sql`/`seed.sql` aplicados

- Navegação
  - Botões de retorno funcionam em múltiplos cenários
  - Links diretos para login/cadastro a partir da página principal
- Header com dropdown do usuário (fora do navbar) exibindo: `Logout` sempre e, para administradores, `Gerenciar produtos` e `Gerenciar usuarios`
  - O dropdown lê o estado do `localStorage` e persiste entre recargas

- Responsividade
  - Layouts testados em diferentes larguras, mantendo legibilidade

### Como acessar a listagem de usuários
- Pelo dropdown do usuário (administrador) na home
- Diretamente: `http://localhost:8080/public/users.php` (apenas para testes diretos)
- Requisitos: MySQL ativo e scripts `init.sql`/`seed.sql` aplicados
