-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_personal;
USE sistema_personal;

-- Tabla de personal
CREATE TABLE personal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    usuario VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Docente', 'Administrativo', 'Obrero', 'Director') NOT NULL,
    cedula VARCHAR(15) NOT NULL,
    telefono VARCHAR(15),
    direccion TEXT,
    fecha_ingreso DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo
INSERT INTO personal (nombre, apellido, usuario, password, rol, cedula, telefono, direccion, fecha_ingreso) VALUES
('María', 'González', 'maria.g', '123456', 'Director', 'V-12345678', '0412-1234567', 'Calle Principal, Monay', '2010-01-15'),
('Carlos', 'Pérez', 'carlos.p', '123456', 'Docente', 'V-87654321', '0416-7654321', 'Barrio El Centro, Monay', '2015-09-01'),
('Ana', 'Rodríguez', 'ana.r', '123456', 'Administrativo', 'V-11223344', '0424-1122334', 'Urbanización Las Flores', '2018-03-10'),
('Luis', 'Martínez', 'luis.m', '123456', 'Obrero', 'V-99887766', '0414-9988776', 'Sector El Calvario', '2020-06-20');