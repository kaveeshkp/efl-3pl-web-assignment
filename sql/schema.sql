
CREATE DATABASE IF NOT EXISTS efl_3pl_shop
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE efl_3pl_shop;

CREATE TABLE users (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  email      VARCHAR(120) NOT NULL UNIQUE,
  full_name  VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE suppliers (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  supplier_name VARCHAR(120) NOT NULL,
  contact_info  VARCHAR(255) NOT NULL
);

CREATE TABLE products (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(120) NOT NULL,
  category     VARCHAR(80)  NOT NULL,
  price        DECIMAL(10,2) NOT NULL,
  quantity     INT NOT NULL DEFAULT 0,
  description  TEXT,
  supplier_id  INT UNSIGNED NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_product_supplier
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
    ON DELETE SET NULL
);

INSERT INTO suppliers (supplier_name, contact_info) VALUES
('Lanka Pack Supplies', 'colombo@lankapack.lk · +94 11 2341000'),
('EFL Warehouse Partners', 'wh@efl3pl.global · +94 11 4791000'),
('Island Labels (Pvt) Ltd', 'sales@islandlabels.lk · +94 11 2550100');

INSERT INTO products (product_name, category, price, quantity, description, supplier_id) VALUES
('Export Carton 7-Ply', 'Packaging', 450.00, 500, 'Heavy duty carton for garment export', 1),
('Stretch Wrap Roll', 'Packaging', 1850.00, 80, '23 micron pallet wrap, 500m', 1),
('Barcode Label Roll', 'Labels', 980.00, 120, 'Thermal labels 100x150mm', 3),
('Pallet Jack', 'Equipment', 75000.00, 6, 'Manual pallet truck 2.5 ton', 2),
('Safety Vest', 'PPE', 650.00, 200, 'Hi-vis warehouse vest', 2);