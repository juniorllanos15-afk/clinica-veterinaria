-- ============================================================
-- SCRIPT COMPLETO: Base de Datos para Clínica Veterinaria
-- Motor: PostgreSQL
-- ============================================================

-- Eliminar tablas existentes (orden inverso por FKs)
DROP TABLE IF EXISTS pago_cuota CASCADE;
DROP TABLE IF EXISTS pago CASCADE;
DROP TABLE IF EXISTS consulta_producto CASCADE;
DROP TABLE IF EXISTS consulta_servicio CASCADE;
DROP TABLE IF EXISTS consulta CASCADE;
DROP TABLE IF EXISTS mascota CASCADE;
DROP TABLE IF EXISTS servicio CASCADE;
DROP TABLE IF EXISTS producto CASCADE;
DROP TABLE IF EXISTS categoria CASCADE;
DROP TABLE IF EXISTS registro_evento CASCADE;
DROP TABLE IF EXISTS visita_pagina CASCADE;
DROP TABLE IF EXISTS menu CASCADE;
DROP TABLE IF EXISTS usuario CASCADE;
DROP TABLE IF EXISTS rol CASCADE;

-- ============================================================
-- TABLAS DEL SISTEMA (conservadas)
-- ============================================================

-- 1. ROL
CREATE TABLE rol (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. USUARIO
CREATE TABLE usuario (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    genero VARCHAR(20),
    tipo_documento VARCHAR(50),
    numero_documento VARCHAR(50) UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(50),
    direccion TEXT,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INTEGER NOT NULL REFERENCES rol(id),
    estado_usuario VARCHAR(20) DEFAULT 'activo',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. MENÚ DINÁMICO
CREATE TABLE menu (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ruta VARCHAR(200),
    orden INTEGER DEFAULT 0,
    rol_id INTEGER REFERENCES rol(id),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. VISITA DE PÁGINA
CREATE TABLE visita_pagina (
    id SERIAL PRIMARY KEY,
    ruta VARCHAR(255) NOT NULL UNIQUE,
    contador INTEGER DEFAULT 1,
    fecha_ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. REGISTRO DE EVENTO
CREATE TABLE registro_evento (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuario(id),
    ruta VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLAS VETERINARIA (nuevas)
-- ============================================================

-- 6. CATEGORÍA
CREATE TABLE categoria (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. SERVICIO
CREATE TABLE servicio (
    id SERIAL PRIMARY KEY,
    codigo_servicio VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria_id INTEGER REFERENCES categoria(id),
    duracion_estimada INTEGER, -- en minutos
    precio NUMERIC(12,2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8. PRODUCTO
CREATE TABLE producto (
    id SERIAL PRIMARY KEY,
    codigo_producto VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    stock INTEGER DEFAULT 0,
    precio NUMERIC(12,2) NOT NULL,
    categoria_id INTEGER NOT NULL REFERENCES categoria(id),
    estado VARCHAR(20) DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. MASCOTA
CREATE TABLE mascota (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especie VARCHAR(50),
    raza VARCHAR(50),
    fecha_nacimiento DATE,
    sexo VARCHAR(20),
    color VARCHAR(50),
    peso NUMERIC(6,2),
    estado VARCHAR(20) DEFAULT 'activo',
    dueno_id INTEGER NOT NULL REFERENCES usuario(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. CONSULTA
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

-- 11. CONSULTA_SERVICIO (servicios aplicados en una consulta)
CREATE TABLE consulta_servicio (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER NOT NULL REFERENCES consulta(id),
    servicio_id INTEGER NOT NULL REFERENCES servicio(id),
    cantidad INTEGER DEFAULT 1,
    precio NUMERIC(12,2) NOT NULL,
    subtotal NUMERIC(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_consulta_servicio_consulta ON consulta_servicio(consulta_id);
CREATE INDEX idx_consulta_servicio_servicio ON consulta_servicio(servicio_id);

-- 12. CONSULTA_PRODUCTO (productos recetados/vendidos en una consulta)
CREATE TABLE consulta_producto (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER NOT NULL REFERENCES consulta(id),
    producto_id INTEGER NOT NULL REFERENCES producto(id),
    cantidad INTEGER DEFAULT 1,
    precio NUMERIC(12,2) NOT NULL,
    subtotal NUMERIC(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_consulta_producto_consulta ON consulta_producto(consulta_id);
CREATE INDEX idx_consulta_producto_producto ON consulta_producto(producto_id);

-- 13. PAGO
CREATE TABLE pago (
    id SERIAL PRIMARY KEY,
    consulta_id INTEGER NOT NULL UNIQUE REFERENCES consulta(id),
    tipo_pago VARCHAR(20) NOT NULL, -- 'contado' o 'credito'
    cantidad_cuotas INTEGER NOT NULL,
    total NUMERIC(12,2) NOT NULL,
    fecha_pago DATE NOT NULL DEFAULT CURRENT_DATE,
    estado VARCHAR(20) DEFAULT 'pendiente', -- pendiente, pagado, atrasado, cancelado
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 14. PAGO_CUOTA (cuotas para pagos al crédito)
CREATE TABLE pago_cuota (
    id SERIAL PRIMARY KEY,
    pago_id INTEGER NOT NULL REFERENCES pago(id),
    numero_cuota INTEGER NOT NULL,
    monto NUMERIC(12,2) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    fecha_pago DATE,
    estado VARCHAR(20) DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- DATOS INICIALES (SEED)
-- ============================================================

-- Roles
INSERT INTO rol (nombre, descripcion) VALUES
('administrador', 'Acceso total al sistema'),
('veterinario', 'Gestión de consultas y mascotas'),
('cliente', 'Dueño de mascotas');

-- Usuarios de prueba (contraseña: 'password' en bcrypt)
INSERT INTO usuario (nombre, apellido, email, telefono, contrasena, rol_id, estado_usuario) VALUES
('Admin', 'Sistema', 'admin@veterinaria.com', '70000001', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPIm.sX6FmwHG.x.3LE8r6PxpVfF.Ky', 1, 'activo'),
('Carlos', 'Veterinario', 'veterinario@veterinaria.com', '70000002', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPIm.sX6FmwHG.x.3LE8r6PxpVfF.Ky', 2, 'activo'),
('María', 'Cliente', 'cliente@veterinaria.com', '70000003', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPIm.sX6FmwHG.x.3LE8r6PxpVfF.Ky', 3, 'activo');

-- Menús del sistema
INSERT INTO menu (nombre, ruta, orden, rol_id, activo) VALUES
-- Admin
('Dashboard',       '/admin/dashboard',       1, 1, TRUE),
('Usuarios',        '/admin/usuarios',        2, 1, TRUE),
('Roles',           '/admin/roles',           3, 1, TRUE),
('Menús',           '/admin/menus',           4, 1, TRUE),
('Categorías',      '/admin/categorias',      5, 1, TRUE),
('Servicios',       '/admin/servicios',       6, 1, TRUE),
('Productos',       '/admin/productos',       7, 1, TRUE),
('Mascotas',        '/admin/mascotas',        8, 1, TRUE),
('Consultas',       '/admin/consultas',       9, 1, TRUE),
('Pagos',           '/admin/pagos',          10, 1, TRUE),
('Historial',       '/admin/historial',      11, 1, TRUE),
('Reportes',        '/admin/reportes',       12, 1, TRUE),
('Eventos',         '/admin/eventos',        13, 1, TRUE),
('Visitas',         '/admin/visitas',        14, 1, TRUE),
('Admin BD',        '/admin/database',       15, 1, TRUE),
-- Veterinario
('Dashboard',       '/veterinario/dashboard',   1, 2, TRUE),
('Mis Consultas',   '/veterinario/consultas',   2, 2, TRUE),
('Mascotas',        '/veterinario/mascotas',    3, 2, TRUE),
-- Cliente
('Dashboard',       '/cliente/dashboard',   1, 3, TRUE),
('Mis Mascotas',    '/cliente/mascotas',    2, 3, TRUE),
('Mis Consultas',   '/cliente/consultas',   3, 3, TRUE),
('Mis Pagos',       '/cliente/pagos',       4, 3, TRUE);

-- Categorías de servicios/productos
INSERT INTO categoria (nombre, descripcion) VALUES
('Consultas', 'Atención médica general'),
('Vacunación', 'Vacunas y refuerzos'),
('Cirugía', 'Procedimientos quirúrgicos'),
('Laboratorio', 'Análisis y estudios clínicos'),
('Medicamentos', 'Farmacia veterinaria'),
('Accesorios', 'Productos para mascotas'),
('Estética', 'Baño, corte y cuidado'),
('Emergencia', 'Atención de urgencia');

-- Servicios
INSERT INTO servicio (codigo_servicio, nombre, descripcion, categoria_id, duracion_estimada, precio) VALUES
('SRV-001', 'Consulta General',           'Atención médica general para mascotas',                     (SELECT id FROM categoria WHERE nombre = 'Consultas'),    30,  80.00),
('SRV-002', 'Consulta Especializada',     'Atención especializada por sistema',                        (SELECT id FROM categoria WHERE nombre = 'Consultas'),    45, 150.00),
('SRV-003', 'Vacuna Cuádruple',           'Vacuna polivalente canina',                                 (SELECT id FROM categoria WHERE nombre = 'Vacunación'),  20, 120.00),
('SRV-004', 'Vacuna Antirrábica',         'Vacuna contra la rabia',                                    (SELECT id FROM categoria WHERE nombre = 'Vacunación'),  15,  80.00),
('SRV-005', 'Esterilización',             'Cirugía de esterilización',                                 (SELECT id FROM categoria WHERE nombre = 'Cirugía'),     90, 500.00),
('SRV-006', 'Cirugía General',            'Procedimientos quirúrgicos varios',                         (SELECT id FROM categoria WHERE nombre = 'Cirugía'),     60, 800.00),
('SRV-007', 'Análisis de Sangre',         'Hemograma completo y bioquímica',                           (SELECT id FROM categoria WHERE nombre = 'Laboratorio'), 30, 200.00),
('SRV-008', 'Ecografía',                  'Estudio ecográfico abdominal',                              (SELECT id FROM categoria WHERE nombre = 'Laboratorio'), 40, 250.00),
('SRV-009', 'Baño Medicado',              'Baño con shampoo medicado',                                 (SELECT id FROM categoria WHERE nombre = 'Estética'),    45, 100.00),
('SRV-010', 'Corte Higiénico',            'Corte de pelo y uñas',                                      (SELECT id FROM categoria WHERE nombre = 'Estética'),    30,  80.00),
('SRV-011', 'Emergencia 24h',             'Atención de emergencia fuera de horario',                   (SELECT id FROM categoria WHERE nombre = 'Emergencia'),  60, 300.00),
('SRV-012', 'Consulta Seguimiento',       'Consulta de control posterior a tratamiento',                (SELECT id FROM categoria WHERE nombre = 'Consultas'),   20,  50.00);

-- Productos
INSERT INTO producto (codigo_producto, nombre, descripcion, stock, precio, categoria_id) VALUES
('PROD-001', 'Desparasitante Oral',   'Tabletas desparasitantes de amplio espectro',    100,  45.00,  (SELECT id FROM categoria WHERE nombre = 'Medicamentos')),
('PROD-002', 'Antibiótico Amoxicilina', 'Suspensión oral 250mg',                          80,   65.00,  (SELECT id FROM categoria WHERE nombre = 'Medicamentos')),
('PROD-003', 'Shampoo Antipulgas',   'Shampoo para control de pulgas y garrapatas',     60,   55.00,  (SELECT id FROM categoria WHERE nombre = 'Medicamentos')),
('PROD-004', 'Collar Antipulgas',    'Collar repelente de pulgas por 8 meses',           40,  120.00,  (SELECT id FROM categoria WHERE nombre = 'Accesorios')),
('PROD-005', 'Cama para Mascota',    'Cama acolchonada tamaño mediano',                  25,  250.00,  (SELECT id FROM categoria WHERE nombre = 'Accesorios')),
('PROD-006', 'Comedero Automático',  'Comedero programable para mascotas',               15,  350.00,  (SELECT id FROM categoria WHERE nombre = 'Accesorios')),
('PROD-007', 'Juguete Mordedor',     'Juguete de goma resistente para dentición',        50,   35.00,  (SELECT id FROM categoria WHERE nombre = 'Accesorios')),
('PROD-008', 'Correa Reflectiva',    'Correa ajustable con banda reflectiva',            70,   60.00,  (SELECT id FROM categoria WHERE nombre = 'Accesorios'));

-- Mascotas de ejemplo
INSERT INTO mascota (nombre, especie, raza, fecha_nacimiento, sexo, color, peso, dueno_id) VALUES
('Buddy',  'Perro', 'Golden Retriever', '2020-05-15', 'Macho',   'Dorado',    32.50, (SELECT id FROM usuario WHERE email = 'cliente@veterinaria.com')),
('Luna',   'Perro', 'Pastor Alemán',   '2021-08-22', 'Hembra',  'Negro',     28.00, (SELECT id FROM usuario WHERE email = 'cliente@veterinaria.com')),
('Misi',   'Gato',  'Siamés',          '2022-01-10', 'Hembra',  'Crema',      4.20, (SELECT id FROM usuario WHERE email = 'cliente@veterinaria.com'));

-- Consultas de ejemplo
INSERT INTO consulta (fecha_consulta, motivo_consulta, diagnostico, tratamiento, estado, mascota_id, veterinario_id) VALUES
('2024-01-15', 'Revisión general y vacunación', 'Paciente sano', 'Vacuna cuádruple aplicada', 'activo',
 (SELECT id FROM mascota WHERE nombre = 'Buddy'),
 (SELECT id FROM usuario WHERE email = 'veterinario@veterinaria.com')),
('2024-02-20', 'Dolor abdominal y vómitos', 'Gastritis leve', 'Dieta blanda y medicación', 'activo',
 (SELECT id FROM mascota WHERE nombre = 'Luna'),
 (SELECT id FROM usuario WHERE email = 'veterinario@veterinaria.com')),
('2024-03-10', 'Control de peso y vacunación', 'Sobrepeso leve', 'Plan de alimentación y ejercicio', 'activo',
 (SELECT id FROM mascota WHERE nombre = 'Misi'),
 (SELECT id FROM usuario WHERE email = 'veterinario@veterinaria.com'));

-- Servicios aplicados en consultas
INSERT INTO consulta_servicio (consulta_id, servicio_id, cantidad, precio, subtotal)
SELECT
    (SELECT id FROM consulta WHERE motivo_consulta = 'Revisión general y vacunación' LIMIT 1),
    (SELECT id FROM servicio WHERE codigo_servicio = 'SRV-001'),
    1, precio, precio
FROM servicio WHERE codigo_servicio = 'SRV-001';

INSERT INTO consulta_servicio (consulta_id, servicio_id, cantidad, precio, subtotal)
SELECT
    (SELECT id FROM consulta WHERE motivo_consulta = 'Revisión general y vacunación' LIMIT 1),
    (SELECT id FROM servicio WHERE codigo_servicio = 'SRV-003'),
    1, precio, precio
FROM servicio WHERE codigo_servicio = 'SRV-003';

INSERT INTO consulta_servicio (consulta_id, servicio_id, cantidad, precio, subtotal)
SELECT
    (SELECT id FROM consulta WHERE motivo_consulta = 'Dolor abdominal y vómitos' LIMIT 1),
    (SELECT id FROM servicio WHERE codigo_servicio = 'SRV-001'),
    1, precio, precio
FROM servicio WHERE codigo_servicio = 'SRV-001';

-- Productos recetados en consultas
INSERT INTO consulta_producto (consulta_id, producto_id, cantidad, precio, subtotal)
SELECT
    (SELECT id FROM consulta WHERE motivo_consulta = 'Dolor abdominal y vómitos' LIMIT 1),
    (SELECT id FROM producto WHERE codigo_producto = 'PROD-001'),
    1, precio, precio
FROM producto WHERE codigo_producto = 'PROD-001';

INSERT INTO consulta_producto (consulta_id, producto_id, cantidad, precio, subtotal)
SELECT
    (SELECT id FROM consulta WHERE motivo_consulta = 'Control de peso y vacunación' LIMIT 1),
    (SELECT id FROM producto WHERE codigo_producto = 'PROD-001'),
    1, precio, precio
FROM producto WHERE codigo_producto = 'PROD-001';

-- Pagos
INSERT INTO pago (consulta_id, tipo_pago, cantidad_cuotas, total, fecha_pago, estado) VALUES
((SELECT id FROM consulta WHERE motivo_consulta = 'Revisión general y vacunación' LIMIT 1), 'contado', 1, 200.00, '2024-01-15', 'pagado'),
((SELECT id FROM consulta WHERE motivo_consulta = 'Dolor abdominal y vómitos' LIMIT 1), 'credito', 3, 125.00, '2024-02-20', 'pendiente'),
((SELECT id FROM consulta WHERE motivo_consulta = 'Control de peso y vacunación' LIMIT 1), 'contado', 1, 50.00, '2024-03-10', 'pagado');

-- Cuotas del pago al crédito
INSERT INTO pago_cuota (pago_id, numero_cuota, monto, fecha_vencimiento, fecha_pago, estado)
SELECT
    p.id, 1, 45.00, '2024-03-20', '2024-03-18', 'pagado'
FROM pago p
JOIN consulta c ON c.id = p.consulta_id
WHERE c.motivo_consulta = 'Dolor abdominal y vómitos'
LIMIT 1;

INSERT INTO pago_cuota (pago_id, numero_cuota, monto, fecha_vencimiento, fecha_pago, estado)
SELECT
    p.id, 2, 40.00, '2024-04-20', NULL, 'pendiente'
FROM pago p
JOIN consulta c ON c.id = p.consulta_id
WHERE c.motivo_consulta = 'Dolor abdominal y vómitos'
LIMIT 1;

INSERT INTO pago_cuota (pago_id, numero_cuota, monto, fecha_vencimiento, fecha_pago, estado)
SELECT
    p.id, 3, 40.00, '2024-05-20', NULL, 'pendiente'
FROM pago p
JOIN consulta c ON c.id = p.consulta_id
WHERE c.motivo_consulta = 'Dolor abdominal y vómitos'
LIMIT 1;

-- ============================================================
-- RESUMEN
-- ============================================================
-- Tablas creadas: 14
--   Sistema: rol, usuario, menu, visita_pagina, registro_evento
--   Veterinaria: categoria, servicio, producto, mascota, consulta,
--                consulta_servicio, consulta_producto, pago, pago_cuota
-- 
-- Usuarios de prueba:
--   admin@veterinaria.com     (rol: administrador)
--   veterinario@veterinaria.com (rol: veterinario)
--   cliente@veterinaria.com   (rol: cliente)
--   Contraseña: password
-- ============================================================
