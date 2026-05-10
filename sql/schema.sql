CREATE DATABASE IF NOT EXISTS agendapro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agendapro;

CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    login    VARCHAR(80)      NOT NULL UNIQUE,
    senha    VARCHAR(255)     NOT NULL,
    criado_em DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activities (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED    NOT NULL,
    nome        VARCHAR(150)    NOT NULL,
    descricao   TEXT,
    inicio      DATETIME        NOT NULL,
    termino     DATETIME        NOT NULL,
    status      ENUM('pendente','concluida','cancelada') NOT NULL DEFAULT 'pendente',
    criado_em   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_act_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_inicio (inicio)
) ENGINE=InnoDB;
