-- Criação da tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    role ENUM('cliente', 'admin') DEFAULT 'cliente',
    bairro VARCHAR(255),
    logradouro VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de carrinho
CREATE TABLE carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pendente', 'em andamento', 'saiu para entrega', 'entregue', 'cancelado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Criação da tabela de itens_pedido
CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

INSERT INTO produtos (nome, descricao, preco, imagem, status) 
VALUES ('Sorvete de Chocolate', 'Sorvete de chocolate intenso com pedaços de chocolate belga', 13.00, 'chocolate.jpg', 'ativo');

INSERT INTO produtos (nome, descricao, preco, imagem, status) 
VALUES ('Casquinha Crocante', 'Casquinha de sorvete crocante e levemente doce', 3.50, 'casquinha.jpg', 'ativo');

INSERT INTO produtos (nome, descricao, preco, imagem, status) 
VALUES ('Milkshake de Baunilha', 'Milkshake cremoso de baunilha feito com sorvete artesanal', 15.00, 'milkshake_baunilha.jpg', 'ativo');

INSERT INTO produtos (nome, descricao, preco, imagem, status) 
VALUES ('Picolé de Limão', 'Picolé refrescante de limão siciliano', 2.00, 'picole_limao.jpg', 'ativo');

