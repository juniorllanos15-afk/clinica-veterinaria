# Despliegue - Clínica Veterinaria

## UN SOLO COMANDO

Desde `/home/grupo17sa/proyecto2` ejecuta:

```bash
bash setup.sh
```

## ¿Qué hace `setup.sh`?

1. **Clone o Pull** del repositorio
2. **Crear `.env`** desde la plantilla incrustrada
3. **Ejecutar `deploy.sh`**: composer, npm build, migraciones + seeders, caché

## Acceso

**URL:** `http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public`

**Usuarios de prueba:**
```
juniorllanos15@gmail.com / 123123 (Propietario)
maria.vet@clinica.bo     / 123123 (Veterinario)
ana.perez@mail.com       / 123123 (Cliente)
```

## Troubleshooting

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Reset completo de BD
php artisan migrate:fresh --seed

# Ejecutar setup nuevamente
cd /home/grupo17sa/proyecto2
bash setup.sh
```

## Entrega

```bash
cd /home/grupo17sa/proyecto2
tar -czvf 2025-2_INF513-P2_grupo17sa.tar.gz escuela-conduccion/
```
