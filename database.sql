-- ============================================================
--  Base de datos: finanzas_db
--  Sistema de Control de Finanzas - Entradas y Salidas
--  Universidad Don Bosco - 2026
-- ============================================================

CREATE DATABASE IF NOT EXISTS finanzas_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE finanzas_db;

-- ------------------------------------------------------------
--  Tabla usuarios  (usada por la clase Login)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id         INT          NOT NULL AUTO_INCREMENT,
  nombre     VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  creado_en  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Usuario de prueba: admin@finanzas.com / admin123
INSERT INTO usuarios (nombre, email, password) VALUES
  ('Administrador',
   'admin@finanzas.com',
   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ------------------------------------------------------------
--  Tabla entradas  (usada por la clase Entradas)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS entradas (
  id            INT            NOT NULL AUTO_INCREMENT,
  tipo_entrada  VARCHAR(100)   NOT NULL,
  monto         DECIMAL(10,2)  NOT NULL,
  fecha         DATE           NOT NULL,
  factura       VARCHAR(255)   DEFAULT NULL,
  creado_en     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
--  Tabla salidas   (usada por la clase Salidas)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS salidas (
  id            INT            NOT NULL AUTO_INCREMENT,
  tipo_salida   VARCHAR(100)   NOT NULL,
  monto         DECIMAL(10,2)  NOT NULL,
  fecha         DATE           NOT NULL,
  factura       VARCHAR(255)   DEFAULT NULL,
  creado_en     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
--  Datos de ejemplo (para probar el reporte inmediatamente)
-- ------------------------------------------------------------
INSERT INTO entradas (tipo_entrada, monto, fecha) VALUES
  ('Sueldo del mes',    500.00, CURDATE()),
  ('Cheque de sistema', 300.00, CURDATE()),
  ('Remesa',            700.00, CURDATE());

INSERT INTO salidas (tipo_salida, monto, fecha) VALUES
  ('Luz',    40.00,  CURDATE()),
  ('Gas',   150.00,  CURDATE()),
  ('Comida',300.00,  CURDATE());
