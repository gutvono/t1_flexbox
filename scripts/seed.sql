USE steemo_db;
INSERT INTO usuarios (nome, email, celular, nivel, senha) VALUES
('Alice Silva', 'alice@example.com', '21999999999', 'usuario', '123456'),
('Bruno Souza', 'bruno@example.com', '21988888888', 'administrador', 'admin123'),
('Carla Lima', 'carla@example.com', '21977777777', 'usuario', 'senha');

INSERT INTO produtos (titulo, descricao, img, valor) VALUES
('Coal LLC', 'Complete sua cota ou morra. Minere quantidades cada vez mais absurdas de carvão usando técnicas de mineração duvidosas, mas eficazes. Ou relaxe e minere no seu próprio ritmo, em modo pacífico.', 'img/jogo-1.png', 29.99),
('Vampire Survivors', 'Acabe com milhares de criaturas noturnas e sobreviva até o amanhecer! Vampire Survivors é um jogo casual de terror gótico com elementos roguelite onde suas escolhas podem fazer você crescer rapidamente e aniquilar os milhares de monstros que aparecem pelo caminho.', 'img/jogo-2.png', 29.99),
('Stardew Valley', 'Você herdou a antiga fazenda do seu avô, em Stardew Valley. Com ferramentas de segunda-mão e algumas moedas, você parte para dar início a sua nova vida. Será que você vai aprender a viver da terra, a transformar esse matagal em um próspero lar?', 'img/jogo-3.png', 29.99);
