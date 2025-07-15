-- Base de datos para la tienda de chuches
CREATE DATABASE IF NOT EXISTS candy_shop;
USE candy_shop;

-- Tabla de categorías/zonas
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    is_weight_based BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tabla de ventas
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10,2) NOT NULL,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de detalles de venta
CREATE TABLE sale_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT,
    product_id INT,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertar categorías iniciales
INSERT INTO categories (name, description) VALUES 
('caramelos', 'Caramelos y dulces a granel'),
('articulos', 'Artículos sueltos como chicles y chocolates'),
('nevera', 'Bebidas frías y productos refrigerados');

-- Insertar productos de ejemplo
INSERT INTO products (name, category_id, price, stock, is_weight_based) VALUES 
-- Caramelos (categoría 1)
('Gominolas Surtidas', 1, 1.00, 100, TRUE),
('Regaliz', 1, 1.00, 100, TRUE),
('Caramelos Duros', 1, 1.00, 100, TRUE),
('Ositos de Goma', 1, 1.00, 100, TRUE),

-- Artículos sueltos (categoría 2)
('Chicles Trident', 2, 0.50, 50, FALSE),
('Lacasitos', 2, 1.20, 30, FALSE),
('Huevo Kinder', 2, 2.50, 20, FALSE),
('Kit Kat', 2, 1.80, 25, FALSE),
('Chupa Chups', 2, 0.30, 40, FALSE),

-- Nevera (categoría 3)
('Coca Cola 33cl', 3, 1.50, 24, FALSE),
('Agua 50cl', 3, 1.00, 30, FALSE),
('Fanta Naranja 33cl', 3, 1.50, 20, FALSE),
('Red Bull', 3, 2.80, 12, FALSE);
