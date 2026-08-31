SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `meu_projeto_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `meu_projeto_db`;

-- Remove todas as tabelas primeiro (evita conflito de FK ao recriar com tipos diferentes)
DROP TABLE IF EXISTS `tb_logs_auditoria`;
DROP TABLE IF EXISTS `tb_exemplo`;
DROP TABLE IF EXISTS `tb_usuarios`;
DROP TABLE IF EXISTS `tb_perfis`;

-- 1. Perfis de usuário
CREATE TABLE `tb_perfis` (
  `id`   int unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_perfis` (id, tipo) VALUES (1, 'USER'), (2, 'ADMIN');

-- 2. Usuários
CREATE TABLE `tb_usuarios` (
  `id`         char(36) NOT NULL,
  `nome`       varchar(255) NOT NULL,
  `email`      varchar(255) NOT NULL,
  `senha`      varchar(255) NOT NULL,
  `perfil_id`  int unsigned DEFAULT NULL,
  `status`     tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `fk_user_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `tb_perfis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Usuário admin padrão (senha: #Senha123 — TROQUE EM PRODUÇÃO)
INSERT INTO `tb_usuarios` (id, nome, email, senha, perfil_id, status)
VALUES (UUID(), 'Admin', 'admin@exemplo.com', '$2y$12$Xs6FoqmLknRXhnEeuqeb/OvvqEvjuSZ6ArHqov/gAtubm9b88KONC', 2, 1);

-- 3. Logs de auditoria
CREATE TABLE `tb_logs_auditoria` (
  `id`          bigint NOT NULL AUTO_INCREMENT,
  `usuario_id`  char(36) NOT NULL,
  `modulo`      varchar(255) NOT NULL,
  `acao`        varchar(255) NOT NULL,
  `registro_id` varchar(36) DEFAULT NULL,
  `detalhe`     text,
  `created_at`  datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabela do módulo Exemplo (TEMPLATE — renomeie para o seu domínio)
CREATE TABLE `tb_exemplo` (
  `id`          char(36) NOT NULL,
  `titulo`      varchar(150) NOT NULL,
  `descricao`   varchar(500) DEFAULT NULL,
  `status`      tinyint(1) DEFAULT 1,
  `created_at`  datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

drop table tb_galeria;
CREATE TABLE `tb_galeria` (
  `id`          char(36) NOT NULL,
  `titulo`      varchar(150) NOT NULL,
  `legenda`   TEXT DEFAULT NULL,
  `status`      tinyint(1) DEFAULT 1,
  `tipo`        VARCHAR(50),
  `created_at`  datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `caminho` VARCHAR(255),  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;