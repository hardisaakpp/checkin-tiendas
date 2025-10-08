# 🎯 Sistema de Check-in para Tiendas

Sistema de registro de clientes para promociones y sorteos de las marcas **Roland** y **MAC Cosmetics**.

## 📋 Descripción

Este sistema permite a los clientes registrarse en tiendas físicas mediante códigos QR únicos, participando en promociones exclusivas. Los datos se almacenan en SQL Server y se envían correos de confirmación automáticos.

## 🏗️ Estructura del Proyecto

```
htdocs/
├── checkin/              # Sistema para tiendas Roland
│   ├── index.php         # Punto de entrada con QR
│   ├── p_verif.php       # Verificación de identificación
│   ├── inicio.php        # Formulario de datos
│   ├── p_fin.php         # Proceso final y envío de email
│   └── ...
├── checkin-mac/          # Sistema para tiendas MAC Cosmetics
│   └── [mismos archivos que checkin/]
├── cx/                   # Conexión a base de datos
│   ├── bd_scs.php        # Funciones de base de datos
│   └── dat_scs.php       # Credenciales (NO se sube a GitHub)
└── assets/               # Recursos compartidos
    ├── dist/             # CSS y JS
    ├── lib/              # Librerías (PHPMailer, etc)
    └── img/              # Imágenes y promociones
```

## 🔧 Requisitos

- **PHP 7.4+** con extensiones:
  - `sqlsrv` (SQL Server Driver)
  - `pdo_sqlsrv`
  - `mbstring`
  - `openssl`
- **SQL Server** 2016+
- **Apache/XAMPP** o servidor web compatible
- **Composer** (opcional, para dependencias)

## 📦 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU_USUARIO/TU_REPO.git
cd TU_REPO
```

### 2. Configurar Base de Datos

#### a) Copiar archivo de configuración:
```bash
cp cx/dat_scs.example.php cx/dat_scs.php
```

#### b) Editar `cx/dat_scs.php` con tus credenciales:
```php
define("HOST","tu_servidor_sql");
define("USUARIO","tu_usuario");
define("PSW","tu_contraseña");
define("BD","STORECONTROL");
```

#### c) Ejecutar scripts SQL:

**Para Roland:**
```sql
-- La tabla chk_cliente ya existe
-- Verificar con:
SELECT * FROM plataforma_web.dbo.chk_cliente;
```

**Para MAC Cosmetics:**
```sql
-- Ejecutar el script de creación de tabla MAC
-- Ver sección "Base de Datos" más abajo
```

### 3. Configurar Servidor Web

#### XAMPP:
- Colocar el proyecto en `C:\xampp\htdocs\`
- Acceder a: `http://localhost/checkin/` o `http://localhost/checkin-mac/`

#### Apache Virtual Host (producción):
```apache
<VirtualHost *:80>
    ServerName checkin.tudominio.com
    DocumentRoot "C:/xampp/htdocs"
    
    <Directory "C:/xampp/htdocs">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 4. Configurar Email (PHPMailer)

Las credenciales de correo están hardcodeadas en `p_fin.php`:
```php
$mail->Username = "cs@sunsetcorpholding.com";
$mail->Password = "Mabel@2024";
```

**⚠️ IMPORTANTE:** Cambiar estas credenciales o moverlas a archivo de configuración.

## 🗄️ Base de Datos

### Estructura de Tablas

#### Tabla para Roland:
```sql
plataforma_web.dbo.chk_cliente
- chk_id (PK)
- tnd_id (ID tienda)
- gen_id (ID género)
- chk_txt_nombre
- chk_txt_apellido
- chk_val_tip_iden (1=cédula, 2=pasaporte)
- chk_txt_iden
- chk_txt_correo
- chk_txt_celular
- chk_sts_estado
- chk_fch_creacion
```

#### Tabla para MAC:
```sql
-- Ejecutar este script para crear la tabla MAC
CREATE TABLE plataforma_web.dbo.chk_cliente_mac (
    chk_mac_id INT IDENTITY(1,1) PRIMARY KEY,
    tnd_id INT NOT NULL,
    gen_id INT NOT NULL,
    chk_mac_txt_nombre NVARCHAR(100) NOT NULL,
    chk_mac_txt_apellido NVARCHAR(100) NOT NULL,
    chk_mac_val_tip_iden TINYINT NOT NULL,
    chk_mac_txt_iden NVARCHAR(20) NOT NULL,
    chk_mac_txt_correo NVARCHAR(150) NOT NULL,
    chk_mac_txt_celular NVARCHAR(15) NOT NULL,
    chk_mac_sts_estado TINYINT DEFAULT 1 NOT NULL,
    chk_mac_fch_creacion DATETIME DEFAULT GETDATE() NOT NULL,
    CONSTRAINT UQ_chk_mac_iden UNIQUE (chk_mac_txt_iden)
);
```

#### Otras tablas:
```sql
plataforma_web.dbo.chk_genero     -- Catálogo de géneros
plataforma_web.dbo.log_chk        -- Logs de accesos
STORECONTROL.dbo.Almacen          -- Info de tiendas
```

## 🔐 Seguridad

### Archivos NO incluidos en Git (por seguridad):
- ✅ `cx/dat_scs.php` - Credenciales de BD
- ✅ `*.log` - Archivos de log
- ✅ Credenciales de email (deben moverse a config)

### Recomendaciones:
1. Usar **prepared statements** (ya implementado en `transac_data_sec()`)
2. Validar inputs en frontend y backend
3. Usar **HTTPS** en producción
4. Implementar **rate limiting** para prevenir spam
5. Mover credenciales a variables de entorno

## 🚀 Uso

### Flujo de Usuario

1. **Escaneo QR**: Cliente escanea código QR en tienda
   - URL: `index.php?MG$=ABC2880[ID_TIENDA]?809ID=465`

2. **Ingreso de Identificación**: 
   - Cédula ecuatoriana (10 dígitos)
   - Pasaporte (6-10 caracteres)

3. **Verificación**: Sistema verifica si ya está registrado

4. **Formulario**: Completa datos personales
   - Nombres, apellidos
   - Email, teléfono
   - Género
   - Acepta términos y condiciones

5. **Confirmación**: Recibe email con detalles de la promoción

### Generar URL de Tienda

```php
// Formato de URL:
$id_tienda = 123;
$url = "https://tudominio.com/checkin/index.php?MG$=ABC2880{$id_tienda}?809ID=465";

// Generar QR con: https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=URL
```

## 🧪 Testing

### Probar Registro:
```bash
# Acceder con ID de tienda de prueba
http://localhost/checkin/index.php?MG$=ABC28801?809ID=465
```

### Verificar en Base de Datos:
```sql
-- Ver últimos registros Roland
SELECT TOP 10 * FROM plataforma_web.dbo.chk_cliente 
ORDER BY chk_fch_creacion DESC;

-- Ver últimos registros MAC
SELECT TOP 10 * FROM plataforma_web.dbo.chk_cliente_mac 
ORDER BY chk_mac_fch_creacion DESC;
```

## 📧 Configuración de Email

### Servidor SMTP actual:
- Host: `smtp.office365.com`
- Puerto: `587`
- Seguridad: `STARTTLS`
- Usuario: `cs@sunsetcorpholding.com`

### Personalizar emails:
Editar `p_fin.php` líneas 47-110 (Subject y Body del email)

## 🐛 Solución de Problemas

### Error: "Could not connect to database"
- Verificar credenciales en `cx/dat_scs.php`
- Comprobar que SQL Server esté corriendo
- Verificar extensión `sqlsrv` de PHP: `php -m | grep sqlsrv`

### Error: "Email not sent"
- Verificar credenciales SMTP
- Revisar firewall/puerto 587
- Comprobar logs: activar `$mail->SMTPDebug = 2;`

### Error: "Registro duplicado"
- El sistema previene registros duplicados por identificación
- Verificar en BD si el registro ya existe

## 📝 Notas de Desarrollo

### Diferencias entre Roland y MAC:
- ✅ Tablas de BD separadas
- ✅ Imágenes de promoción diferentes
- ✅ Contenido de emails diferentes
- ✅ Términos y condiciones diferentes

### Archivos idénticos:
- `f_checkin.php` - Validador de cédula
- `header.php` / `footer.php` - Layout
- `bd_scs.php` - Funciones de BD

## 🤝 Contribuir

1. Fork el proyecto
2. Crear rama: `git checkout -b feature/nueva-funcionalidad`
3. Commit: `git commit -m "Descripción del cambio"`
4. Push: `git push origin feature/nueva-funcionalidad`
5. Crear Pull Request

## 📄 Licencia

Proyecto propietario - Mabel Group / Sunset Corp Holding

## 👥 Equipo

- **Desarrollo**: Departamento de Desarrollo MABEL
- **Email**: cs@sunsetcorpholding.com

## 🔄 Changelog

### [Próxima versión]
- [ ] Crear tabla MAC separada
- [ ] Mover credenciales a archivo config
- [ ] Implementar validación de cédula en backend
- [ ] Agregar logs de auditoría mejorados

### [Actual]
- ✅ Sistema funcionando para Roland
- ✅ Sistema funcionando para MAC (misma tabla)
- ✅ Envío de emails automático
- ✅ Validación de duplicados

---

**Última actualización:** Octubre 2025


