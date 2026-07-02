-- PostgreSQL Database Schema - Clínica Veterinaria
-- Tablas de Negocio Únicamente

-- ===========================================
-- 1. Roles
-- ===========================================
CREATE TABLE rol (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 2. Usuarios
-- ===========================================
CREATE TABLE usuario (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    genero VARCHAR(20),
    tipo_documento VARCHAR(50),
    numero_documento VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(50),
    direccion TEXT,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INTEGER NOT NULL REFERENCES rol(id),
    estado_usuario VARCHAR(20) DEFAULT 'activo',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_usuario_email ON usuario(email);
CREATE INDEX idx_usuario_numero_documento ON usuario(numero_documento);

-- ===========================================
-- 3. Categorías de Productos
-- ===========================================
CREATE TABLE categoria (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 4. Servicios
-- ===========================================
CREATE TABLE servicio (
    id SERIAL PRIMARY KEY,
    codigo_servicio VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion_estimada INTEGER,
    precio NUMERIC(12, 2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 5. Productos
-- ===========================================
CREATE TABLE producto (
    id SERIAL PRIMARY KEY,
    codigo_producto VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    stock INTEGER NOT NULL DEFAULT 0,
    precio NUMERIC(12, 2) NOT NULL,
    categoria_id INTEGER NOT NULL REFERENCES categoria(id),
    estado VARCHAR(20) DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 6. Mascotas
-- ===========================================
CREATE TABLE mascota (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especie VARCHAR(50),
    raza VARCHAR(50),
    fecha_nacimiento DATE,
    sexo VARCHAR(20),
    color VARCHAR(50),
    peso NUMERIC(6, 2),
    estado VARCHAR(20) DEFAULT 'activo',
    dueno_id INTEGER NOT NULL REFERENCES usuario(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_mascota_dueno ON mascota(dueno_id);

-- ===========================================
-- 7. Consultas
-- ===========================================
CREATE TABLE consulta (
    id SERIAL PRIMARY KEY,
    fecha_consulta DATE NOT NULL DEFAULT CURRENT_DATE,
    motivo_consulta TEXT,
    diagnostico TEXT,
    tratamiento TEXT,
    observaciones TEXT,
    estado VARCHAR(20) DEFAULT 'activo',
    mascota_id INTEGER NOT NULL REFERENCES mascota(id),
    veterinario_id INTEGER NOT NULL REFERENCES usuario(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_consulta_mascota ON consulta(mascota_id);
CREATE INDEX idx_consulta_veterinario ON consulta(veterinario_id);

-- ===========================================
-- 8. Consulta - Servicio (intermedia)
-- ===========================================
CREATE TABLE consulta_servicio (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER NOT NULL REFERENCES consulta(id),
    servicio_id INTEGER NOT NULL REFERENCES servicio(id),
    cantidad INTEGER NOT NULL DEFAULT 1,
    precio NUMERIC(12, 2) NOT NULL,
    subtotal NUMERIC(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_consulta_servicio_cons ON consulta_servicio(consulta_id);
CREATE INDEX idx_consulta_servicio_serv ON consulta_servicio(servicio_id);

-- ===========================================
-- 9. Consulta - Producto (intermedia)
-- ===========================================
CREATE TABLE consulta_producto (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER NOT NULL REFERENCES consulta(id),
    producto_id INTEGER NOT NULL REFERENCES producto(id),
    cantidad INTEGER NOT NULL DEFAULT 1,
    precio NUMERIC(12, 2) NOT NULL,
    subtotal NUMERIC(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_consulta_producto_cons ON consulta_producto(consulta_id);
CREATE INDEX idx_consulta_producto_prod ON consulta_producto(producto_id);

-- ===========================================
-- 10. Pagos
-- ===========================================
CREATE TABLE pago (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER UNIQUE NOT NULL REFERENCES consulta(id),
    tipo_pago VARCHAR(20) NOT NULL,
    cantidad_cuotas INTEGER NOT NULL,
    total NUMERIC(12, 2) NOT NULL,
    fecha_pago DATE NOT NULL DEFAULT CURRENT_DATE,
    estado VARCHAR(20) DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_pago_consulta ON pago(consulta_id);

-- ===========================================
-- 11. Cuotas de Pago
-- ===========================================
CREATE TABLE pago_cuota (
    id SERIAL PRIMARY KEY,
    pago_id INTEGER NOT NULL REFERENCES pago(id),
    numero_cuota INTEGER NOT NULL,
    monto NUMERIC(12, 2) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    fecha_pago DATE,
    estado VARCHAR(20) DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 12. Menú Dinámico
-- ===========================================
CREATE TABLE menu (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ruta VARCHAR(255),
    icono VARCHAR(100),
    orden INTEGER DEFAULT 0,
    rol_id INTEGER REFERENCES rol(id) ON DELETE CASCADE,
    activo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_menu_rol ON menu(rol_id);

-- ===========================================
-- 13. Visitas a Páginas
-- ===========================================
CREATE TABLE visita_pagina (
    id SERIAL PRIMARY KEY,
    ruta VARCHAR(255) UNIQUE NOT NULL,
    contador BIGINT DEFAULT 1,
    fecha_ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_visita_pagina_contador ON visita_pagina(contador);

-- ===========================================
-- 14. Registro de Eventos
-- ===========================================
CREATE TABLE registro_evento (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuario(id) ON DELETE SET NULL,
    ruta VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_registro_evento_fecha ON registro_evento(fecha_evento);
