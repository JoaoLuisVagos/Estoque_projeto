CREATE TABLE IF NOT EXISTS bebidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo_bebida ENUM('alcoolica', 'nao-alcoolica') NOT NULL,
    estoque_total DECIMAL(10, 2) NOT NULL DEFAULT 0,
    excluido INT NOT NULL DEFAULT 0,
    responsavel VARCHAR(100) NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS movimentacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bebida_id INT,
    tipo ENUM('entrada','saida') NOT NULL,
    excluido INT NOT NULL DEFAULT 0,
    volume DECIMAL(10, 2) NOT NULL,
    responsavel VARCHAR(100) NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bebida_id) REFERENCES bebidas(id)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
