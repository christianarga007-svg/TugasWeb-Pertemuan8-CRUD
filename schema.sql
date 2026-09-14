CREATE DATABASE IF NOT EXISTS inventaris_db;
USE inventaris_db;

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(150) NOT NULL,
    kontak VARCHAR(50) NOT NULL
);

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    kategori_id INT NOT NULL,
    supplier_id INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
);

INSERT INTO kategori (nama_kategori) VALUES 
('Elektronik'), ('Pakaian'), ('Makanan'), ('Minuman'), ('Alat Tulis');

INSERT INTO supplier (nama_supplier, kontak) VALUES 
('PT Elektronik Jaya', '081111111111'), 
('CV Maju Bersama', '082222222222'), 
('Toko Makmur', '083333333333'), 
('PT Pangan Sejahtera', '084444444444'), 
('CV Tulis Menulis', '085555555555');

INSERT INTO produk (nama_produk, kategori_id, supplier_id, stok, harga) VALUES 
('Laptop Asus X515', 1, 1, 15, 7500000), 
('Kaos Polos Hitam', 2, 2, 100, 45000), 
('Mie Instan Goreng', 3, 4, 200, 3000), 
('Air Mineral 600ml', 4, 4, 50, 4000), 
('Buku Tulis Sinar Dunia', 5, 5, 300, 4500);