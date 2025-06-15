drop database if exists webapp;

CREATE  DATABASE  webapp;

USE webapp;

CREATE TABLE imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_original VARCHAR(255),
    nome_arquivo VARCHAR(255),
    caminho VARCHAR(255),
    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de relacionamento imagem-usuário
CREATE TABLE imagem_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    imagem_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (imagem_id) REFERENCES imagens(id)
);

-- Inserindo usuários para teste
INSERT INTO usuarios (nome) VALUES ('Wellington'), ('Xavier');