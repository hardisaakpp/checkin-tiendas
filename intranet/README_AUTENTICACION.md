# 🔐 Sistema de Autenticación - Intranet Mabel Group

## Instalación y Configuración

Este documento describe cómo configurar el sistema de autenticación para el módulo de administración del directorio telefónico.

### Paso 1: Crear la tabla de usuarios en la base de datos

1. Abre SQL Server Management Studio o tu cliente de SQL Server
2. Conéctate a la base de datos `plataforma_web`
3. Ejecuta el script `crear_tabla_usuarios.sql`
4. Verifica que la tabla `int_usuarios` se haya creado correctamente

### Paso 2: Crear el usuario administrador inicial

1. Abre tu navegador
2. Navega a: `http://localhost/intranet/crear_usuario_admin.php`
3. El script creará un usuario administrador con las siguientes credenciales:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`
   - **Rol:** `admin`

⚠️ **IMPORTANTE:**
- Guarda estas credenciales en un lugar seguro
- Cambia la contraseña después del primer acceso
- **ELIMINA** el archivo `crear_usuario_admin.php` después de crear el usuario por seguridad

### Paso 3: Probar el sistema

1. Abre tu navegador
2. Navega a la intranet: `http://localhost/intranet/`
3. Haz clic en el botón **"ADMIN DIRECTORIO"**
4. Serás redirigido a la página de login
5. Ingresa las credenciales:
   - Usuario: `admin`
   - Contraseña: `admin123`
6. Si las credenciales son correctas, serás redirigido a `admin_directorio.php`

## Archivos creados

- **`auth.php`** - Funciones de autenticación reutilizables
- **`login_admin.php`** - Página de login
- **`logout.php`** - Script para cerrar sesión
- **`crear_tabla_usuarios.sql`** - Script SQL para crear la tabla
- **`crear_usuario_admin.php`** - Script para crear usuario inicial (eliminar después)

## Archivos modificados

- **`admin_directorio.php`** - Ahora requiere autenticación antes de cargar
- **`header.php`** - Muestra información del usuario autenticado y botón de logout

## Estructura de la tabla de usuarios

```sql
int_usuarios
├── usu_id (INT, PRIMARY KEY, IDENTITY)
├── usu_txt_usuario (NVARCHAR(50), UNIQUE)
├── usu_txt_password (NVARCHAR(255)) -- Hash de contraseña
├── usu_txt_nombre (NVARCHAR(100))
├── usu_txt_rol (NVARCHAR(50)) -- 'admin', 'rrhh', 'usuario'
├── usu_sts_activo (BIT) -- 1 = activo, 0 = inactivo
├── usu_fec_creacion (DATETIME)
└── usu_fec_ultimo_acceso (DATETIME)
```

## Funciones disponibles en auth.php

- `requireAuth($rolRequerido)` - Requiere autenticación y opcionalmente un rol específico
- `login($usuario, $password)` - Autentica un usuario
- `logout()` - Cierra la sesión
- `tieneRol($rol)` - Verifica si el usuario tiene un rol específico
- `getUsuarioActual()` - Obtiene información del usuario actual

## Agregar más usuarios

Para agregar más usuarios, puedes:

1. **Usar SQL directamente:**
```sql
INSERT INTO plataforma_web.dbo.int_usuarios 
(usu_txt_usuario, usu_txt_password, usu_txt_nombre, usu_txt_rol, usu_sts_activo)
VALUES 
('usuario', 'hash_de_contraseña', 'Nombre Usuario', 'admin', 1);
```

2. **Crear un script PHP similar a `crear_usuario_admin.php`**

**Nota:** Para generar el hash de contraseña en PHP:
```php
$passwordHash = password_hash('tu_contraseña', PASSWORD_DEFAULT);
```

## Roles disponibles

- **`admin`** - Acceso completo a todas las funcionalidades administrativas
- **`rrhh`** - Acceso a módulos de recursos humanos (configurable)
- **`usuario`** - Usuario estándar (configurable según necesidades)

## Seguridad

✅ Las contraseñas se almacenan con hash usando `password_hash()` de PHP
✅ Las sesiones se validan en cada página protegida
✅ Las consultas SQL usan prepared statements para prevenir SQL injection
✅ Redirección automática si no hay sesión activa

## Solución de problemas

### Error: "Acceso no permitido" al intentar acceder a admin_directorio.php
- Verifica que la tabla `int_usuarios` existe
- Verifica que hay un usuario creado y activo
- Verifica que las credenciales son correctas

### Error de conexión a la base de datos
- Verifica la configuración en `auth.php` (líneas 13-16)
- Verifica que el servidor SQL Server está accesible
- Verifica las credenciales de conexión

### La sesión no se mantiene
- Verifica que las cookies están habilitadas en el navegador
- Verifica la configuración de PHP para sesiones
- Verifica que no hay problemas con el servidor web

## Soporte

Para más información o soporte, contacta al equipo de desarrollo.

