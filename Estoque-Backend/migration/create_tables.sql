CREATE TABLE bebidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo_bebida ENUM('alcoolica', 'nao-alcoolica') NOT NULL,
    volume INT NOT NULL,
    estoque_total INT NOT NULL DEFAULT 0,
    excluido INT NOT NULL DEFAULT 0,
    responsavel VARCHAR(100) NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movimentacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bebida_id INT,
    tipo ENUM('entrada','saida') NOT NULL,
    excluido INT NOT NULL DEFAULT 0,
    volume INT NOT NULL,
    responsavel VARCHAR(100) NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bebida_id) REFERENCES bebidas(id)
);
