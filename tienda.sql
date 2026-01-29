-- 1. TABLA USUARIOS (Para Admin y Clientes)
CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    telefono VARCHAR(20),
    direccion TEXT,
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(20),
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. TABLA PRODUCTOS (Lo que subirá el Admin)
CREATE TABLE IF NOT EXISTS Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    precio_oferta DECIMAL(10, 2) DEFAULT 0,
    stock INT DEFAULT 10,
    imagen VARCHAR(255), 
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. TABLA PEDIDOS (Para simular la compra)
CREATE TABLE IF NOT EXISTS Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2),
    estado VARCHAR(50) DEFAULT 'Completado',
    notas TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- 4. TABLA DETALLES_PEDIDOS (Qué productos compró en cada pedido)
CREATE TABLE IF NOT EXISTS Detalles_Pedidos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);

INSERT INTO Usuarios (nombre, email, contrasena, rol, activo) 
VALUES (
    'Administrador Principal', 
    'admin@electrostore.com', 
    '$2y$10$wF/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F/F', -- Esto simula un hash, pero usa mejor la Opción A para garantizar acceso real
    'admin', 
    1
);