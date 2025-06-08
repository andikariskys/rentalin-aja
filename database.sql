CREATE DATABASE IF NOT EXISTS rentalin_aja;
USE rentalin_aja;

CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Laki-laki', 'Perempuan') NOT NULL DEFAULT 'Laki-laki',
    address TEXT,
    phone VARCHAR(15) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- username: admin, password: admin | username: andika, password: andika | username: alta, password: alta123
INSERT INTO users VALUES ('b0206b4c-3cdf-41dc-a9b7-73886d863633', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'Laki-laki', 'Jl. Admin No. 1', '081234567890', 'admin');
INSERT INTO users VALUES ('4fdc831c-1a9b-4bfe-9e1b-e799479cd12e', 'andika', '7e51eea5fa101ed4dade9ad3a7a072bb', 'Andika Risky', 'Laki-laki', 'Jl. Karanganyar No. 1', '0811223344556', 'user');
INSERT INTO users VALUES ('5a6b7c8d-9e0f-1a2b-3c4d-5e6f7g8h9i0j', 'alta', 'a41ae22e9f10528c184e5acb86bebacc', 'Alta Moda', 'Perempuan', 'Jl. Mojogedang No. 2', '0822334455667', 'user');

CREATE TABLE IF NOT EXISTS categories (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

INSERT INTO categories VALUES ('b1c2d3e4-f5a6-7b8c-9d0e-f1a2b3c4d5e6', 'Electronics', 'Devices and gadgets');
INSERT INTO categories VALUES ('c2d3e4f5-a6b7-8c9d-0e1f-2a3b4c5d6e7f', 'Furniture', 'Home and office furniture');

CREATE TABLE IF NOT EXISTS products (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    price INT NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    category_id CHAR(36),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO products VALUES ('2ff42b89-5bee-4ac8-91cb-3c0774eff7f2', 'Laptop', 'laptop.jpg', 'High-performance laptop', 150000, 3, 'b1c2d3e4-f5a6-7b8c-9d0e-f1a2b3c4d5e6');
INSERT INTO products VALUES ('f6b9ae32-b856-4f84-a71c-a4be54e69760', 'Office Chair', 'office-chair.jpg', 'Ergonomic office chair', 30000, 2, 'c2d3e4f5-a6b7-8c9d-0e1f-2a3b4c5d6e7f');

CREATE TABLE IF NOT EXISTS borrowings (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    borrow_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    return_date DATETIME,
    code VARCHAR(50) NOT NULL UNIQUE,
    identity_type ENUM('KTP', 'SIM', 'KK', 'Ijazah'),
    identity_number VARCHAR(50),
    identity_name VARCHAR(100),
    status ENUM('pending', 'approved', 'returned', 'overdue') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO borrowings VALUES ('a1b2c3d4-e5f6-7a8b-9c0d-e1f2a3b4c5d6', '4fdc831c-1a9b-4bfe-9e1b-e799479cd12e', '2025-06-01 12:00:00', '2025-06-03 09:00:00', 'PIN250606-0900', 'KTP', '3313050000000001', 'Jacowi', 'returned');
INSERT INTO borrowings VALUES ('d4e5f6a7-b8c9-0d1e-f2a3-b4c5d6e7f8a9', '4fdc831c-1a9b-4bfe-9e1b-e799479cd12e', '2025-06-03 11:00:00', NULL, 'PIN250603-1100', 'SIM', '3313050000000002', 'Gabrin', 'approved');
INSERT INTO borrowings VALUES ('c3d4e5f6-a7b8-9c0d-e1f2-a3b4c5d6e7f8', '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7g8h9i0j', '2025-06-02 10:00:00', NULL, 'PIN250602-1000', NULL, NULL, NULL, 'pending');

CREATE TABLE IF NOT EXISTS borrowing_details (
    id CHAR(36) PRIMARY KEY,
    borrowing_id CHAR(36) NOT NULL,
    product_id CHAR(36) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (borrowing_id) REFERENCES borrowings(id)
);

INSERT INTO borrowing_details VALUES ('166b7ee3-bd64-4491-89f1-02e597c46e8c', 'a1b2c3d4-e5f6-7a8b-9c0d-e1f2a3b4c5d6', '2ff42b89-5bee-4ac8-91cb-3c0774eff7f2');
INSERT INTO borrowing_details VALUES ('5781173a-f935-4223-939a-fd5dec4b57fe', 'a1b2c3d4-e5f6-7a8b-9c0d-e1f2a3b4c5d6', 'f6b9ae32-b856-4f84-a71c-a4be54e69760');
INSERT INTO borrowing_details VALUES ('b2c3d4e5-f6a7-8b9c-0d1e-f2a3b4c5d6e7', 'd4e5f6a7-b8c9-0d1e-f2a3-b4c5d6e7f8a9', '2ff42b89-5bee-4ac8-91cb-3c0774eff7f2');
INSERT INTO borrowing_details VALUES ('9b95e6fc-7c9c-4838-b268-55dc8893d687', 'c3d4e5f6-a7b8-9c0d-e1f2-a3b4c5d6e7f8', 'f6b9ae32-b856-4f84-a71c-a4be54e69760');