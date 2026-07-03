# Clínica Veterinaria - Sistema de Gestión

Sistema web integral para administrar una clínica veterinaria con gestión de consultas, productos, servicios, mascotas y pagos con integración PagoFácil QR.

## Tecnologías

- **Backend**: Laravel 12 + PHP 8.3
- **Frontend**: Vue 3 + TypeScript + Tailwind CSS + Inertia.js
- **Base de Datos**: PostgreSQL 16
- **Build**: Vite

## Características Principales

- **Multi-rol**: Propietario, Veterinario y Cliente
- **Gestión de Consultas**: Con productos y servicios por línea (cantidad, precio, subtotal)
- **Sistema de Pagos**: Integración con PagoFácil QR, planes de cuotas, QR por cuota
- **Mascotas**: Registro con datos completos (especie, raza, peso, etc.)
- **Webhook**: Actualización automática de estado de pagos vía callback
- **Historial**: Vista completa de consultas con desglose de productos/servicios/pagos
- **Panel de Administración**: Control total del sistema

## Requisitos

- PHP 8.2+
- Composer 2.x
- Node.js 18+
- PostgreSQL 15+

## Instalación

```bash
# Clonar repositorio
git clone https://github.com/daviddlv007/proyecto-parcial-2-tecno.git
cd proyecto-parcial-2-tecno

# Copiar configuración
cp .env.example .env
# Editar .env con datos de conexión a BD

# Instalar dependencias
composer install
npm install

# Generar key
php artisan key:generate

# Migraciones y seeders
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve
```

## Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| juniorllanos15@gmail.com | 123123 | Propietario |
| maria.vet@clinica.bo | 123123 | Veterinario |
| ana.perez@mail.com | 123123 | Cliente |

## Configuración PagoFácil

Variables en `.env`:

```
PAGOFACIL_API_URL=https://masterqr.pagofacil.com.bo/api/services/v2
PAGOFACIL_TOKEN_SERVICE=...
PAGOFACIL_TOKEN_SECRET=...
PAGOFACIL_PAYMENT_METHOD=34
PAGOFACIL_CALLBACK_URL=https://tu-dominio/api/pagofacil/callback
PAGOFACIL_PAYMENT_AMOUNT=0.10
```

## Licencia

Proyecto académico - Universidad Mayor de San Andrés
