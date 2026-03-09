CREATE DATABASE IF NOT EXISTS controle_ponto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE controle_ponto;

CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    matricula VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL UNIQUE,
    descricao TEXT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS grupos_alunos (
    grupo_id INT NOT NULL,
    aluno_id INT NOT NULL,
    PRIMARY KEY (grupo_id, aluno_id),
    CONSTRAINT fk_ga_grupo FOREIGN KEY (grupo_id) REFERENCES grupos (id) ON DELETE CASCADE,
    CONSTRAINT fk_ga_aluno FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS escalas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS escalas_grupos (
    escala_id INT NOT NULL,
    grupo_id INT NOT NULL,
    PRIMARY KEY (escala_id, grupo_id),
    CONSTRAINT fk_eg_escala FOREIGN KEY (escala_id) REFERENCES escalas (id) ON DELETE CASCADE,
    CONSTRAINT fk_eg_grupo FOREIGN KEY (grupo_id) REFERENCES grupos (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS registros_ponto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    escala_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    observacao VARCHAR(255) NULL,
    registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rp_aluno FOREIGN KEY (aluno_id) REFERENCES alunos (id),
    CONSTRAINT fk_rp_escala FOREIGN KEY (escala_id) REFERENCES escalas (id)
);
