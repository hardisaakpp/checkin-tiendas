# 📝 Changelog - Sistema Check-in

## [1.1.0] - 2025-10-07

### ✨ Nuevas Características
- **Tabla separada para MAC Cosmetics**: Implementada tabla `chk_cliente_mac` independiente
- **Logs separados**: Tabla `log_chk_mac` para auditoría de accesos MAC
- **Script SQL completo**: Archivo `database/create_table_mac.sql` con:
  - Creación de tablas
  - Índices optimizados
  - Constraints de validación
  - Verificaciones de existencia
  - Queries útiles de ejemplo

### 🔧 Modificaciones
- **checkin-mac/p_verif.php**: Actualizado para consultar `chk_cliente_mac`
- **checkin-mac/p_fin.php**: 
  - Verificación de duplicados usando tabla MAC
  - INSERT a tabla `chk_cliente_mac` con campos correctos
- **checkin-mac/index.php**: Logs de acceso a tabla `log_chk_mac`

### 📊 Estructura de Base de Datos

#### Roland (sin cambios):
```
plataforma_web.dbo.chk_cliente
plataforma_web.dbo.log_chk
```

#### MAC (nuevo):
```
plataforma_web.dbo.chk_cliente_mac
plataforma_web.dbo.log_chk_mac
```

### 🎯 Beneficios
- ✅ Datos completamente separados por marca
- ✅ Reportes y análisis independientes
- ✅ Mejor control de promociones específicas
- ✅ Escalabilidad para futuras marcas
- ✅ Sin riesgo de mezclar datos

### 📝 Campos de la Tabla MAC
- `chk_mac_id` - ID autoincremental (PK)
- `tnd_id` - ID de tienda
- `gen_id` - ID de género
- `chk_mac_txt_nombre` - Nombres del cliente
- `chk_mac_txt_apellido` - Apellidos del cliente
- `chk_mac_val_tip_iden` - Tipo de identificación (1=cédula, 2=pasaporte)
- `chk_mac_txt_iden` - Número de identificación (UNIQUE)
- `chk_mac_txt_correo` - Email
- `chk_mac_txt_celular` - Teléfono celular
- `chk_mac_sts_estado` - Estado (1=activo, 0=inactivo)
- `chk_mac_fch_creacion` - Fecha de creación
- `chk_mac_fch_modificacion` - Fecha de modificación

### 🔒 Seguridad
- ✅ Prepared statements en todos los INSERT
- ✅ Validación de duplicados por identificación
- ✅ Constraints de base de datos
- ✅ Índices para prevenir duplicados

### 📚 Documentación
- ✅ README.md actualizado con instrucciones completas
- ✅ Script SQL documentado con comentarios
- ✅ .gitignore configurado correctamente
- ✅ Archivo de ejemplo para configuración de BD

---

## [1.0.0] - 2025-10-07

### 🎉 Lanzamiento Inicial
- Sistema de check-in para tiendas Roland
- Sistema de check-in para tiendas MAC (compartiendo tabla)
- Integración con PHPMailer para confirmaciones
- Validación de cédula ecuatoriana
- Soporte para pasaportes
- Diseño responsive con Bootstrap
- Modo claro/oscuro


