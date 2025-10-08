# 🧪 Guía de Pruebas - Sistema Check-in MAC

## ✅ Cambios Implementados

- [x] Tabla `chk_cliente_mac` creada en base de datos
- [x] Tabla `log_chk_mac` creada para auditoría
- [x] `checkin-mac/p_verif.php` modificado
- [x] `checkin-mac/p_fin.php` modificado
- [x] `checkin-mac/index.php` modificado
- [x] Código subido a GitHub

## 📋 Pasos para Probar el Sistema

### 1️⃣ Ejecutar Script SQL

**IMPORTANTE:** Antes de probar, ejecuta el script SQL en tu servidor:

```bash
# Abrir SQL Server Management Studio (SSMS)
# O usar sqlcmd desde terminal:
sqlcmd -S 10.10.100.12 -U consultas -P "Datos.22" -i "C:\xampp\htdocs\database\create_table_mac.sql"
```

O manualmente en SSMS:
1. Conectarse al servidor SQL: `10.10.100.12`
2. Abrir archivo: `database/create_table_mac.sql`
3. Ejecutar (F5)
4. Verificar mensajes:
   ```
   ✓ Tabla chk_cliente_mac creada exitosamente
   ✓ Índice IX_chk_mac_tienda creado
   ✓ Índice IX_chk_mac_correo creado
   ✓ Índice IX_chk_mac_estado creado
   ✓ Índice IX_chk_mac_fecha creado
   ✓ Tabla log_chk_mac creada exitosamente
   ```

### 2️⃣ Verificar Tablas Creadas

```sql
-- Verificar estructura de tabla MAC
SELECT TOP 1 * FROM plataforma_web.dbo.chk_cliente_mac;

-- Verificar tabla de logs
SELECT TOP 1 * FROM plataforma_web.dbo.log_chk_mac;

-- Ver todas las tablas relacionadas
SELECT TABLE_NAME 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_NAME LIKE 'chk%' OR TABLE_NAME LIKE 'log_chk%';
```

Deberías ver:
- `chk_cliente` (Roland)
- `chk_cliente_mac` (MAC) ← Nueva
- `log_chk` (Roland)
- `log_chk_mac` (MAC) ← Nueva

### 3️⃣ Preparar URL de Prueba

Genera una URL de test para una tienda MAC:

```php
// Ejemplo con ID de tienda: 1
https://localhost/checkin-mac/index.php?MG$=ABC28801?809ID=465

// Para producción:
https://tudominio.com/checkin-mac/index.php?MG$=ABC2880[ID_TIENDA]?809ID=465
```

### 4️⃣ Probar Flujo Completo

#### Paso 1: Acceder al Sistema
1. Abrir navegador
2. Ir a: `http://localhost/checkin-mac/index.php?MG$=ABC28801?809ID=465`
3. **Verificar:** Debe mostrar imagen de promoción MAC
4. **Verificar en BD:** Log debe registrarse
   ```sql
   SELECT TOP 1 * FROM plataforma_web.dbo.log_chk_mac 
   ORDER BY log_mac_fch DESC;
   ```

#### Paso 2: Ingresar Identificación
1. Ingresar cédula de prueba: `1234567890`
2. Clic en "Participar"
3. **Verificar:** NO debe decir "ya registrado" (primera vez)

#### Paso 3: Completar Formulario
1. **Nombres:** Juan Carlos
2. **Apellidos:** Pérez López
3. **Email:** tu-email-real@gmail.com ⚠️ (usa uno real para recibir correo)
4. **Teléfono:** 0987654321
5. **Género:** Seleccionar uno
6. Marcar ambos checkboxes
7. Clic en "Finalizar"

#### Paso 4: Verificar Registro en BD
```sql
-- Ver el registro recién creado
SELECT * FROM plataforma_web.dbo.chk_cliente_mac 
WHERE chk_mac_txt_iden = '1234567890';

-- Debería mostrar:
-- chk_mac_txt_nombre: JUAN CARLOS
-- chk_mac_txt_apellido: PÉREZ LÓPEZ
-- chk_mac_txt_correo: tu email
-- chk_mac_sts_estado: 1
```

#### Paso 5: Verificar Email Recibido
1. Revisar bandeja de entrada
2. **Asunto:** "🎉 ¡Hola Juan Carlos! Tu registro para el sorteo MAC Colour Excess fue exitoso ✨"
3. Verificar contenido:
   - Saludo personalizado
   - Detalles del premio
   - Fechas del sorteo
   - Enlace a términos y condiciones

#### Paso 6: Probar Duplicados
1. Volver a acceder con la misma URL
2. Ingresar misma cédula: `1234567890`
3. **Verificar:** Debe mostrar mensaje "Ya te encuentras registrado"

### 5️⃣ Comparar con Roland (Verificación)

**Verificar que las tablas están separadas:**

```sql
-- Ver registros Roland (NO debe tener tu prueba)
SELECT * FROM plataforma_web.dbo.chk_cliente 
WHERE chk_txt_iden = '1234567890';
-- Resultado: 0 filas ✅

-- Ver registros MAC (debe tener tu prueba)
SELECT * FROM plataforma_web.dbo.chk_cliente_mac 
WHERE chk_mac_txt_iden = '1234567890';
-- Resultado: 1 fila ✅
```

### 6️⃣ Queries de Verificación

```sql
-- Contar registros por marca
SELECT 'ROLAND' AS Marca, COUNT(*) AS Total 
FROM plataforma_web.dbo.chk_cliente
UNION ALL
SELECT 'MAC' AS Marca, COUNT(*) AS Total 
FROM plataforma_web.dbo.chk_cliente_mac;

-- Ver últimos 5 registros MAC
SELECT TOP 5 
    chk_mac_id,
    tnd_id,
    chk_mac_txt_nombre,
    chk_mac_txt_apellido,
    chk_mac_txt_iden,
    chk_mac_txt_correo,
    chk_mac_fch_creacion
FROM plataforma_web.dbo.chk_cliente_mac 
ORDER BY chk_mac_fch_creacion DESC;

-- Ver logs de acceso MAC
SELECT TOP 10 
    log_mac_id,
    tnd_id,
    log_mac_fch
FROM plataforma_web.dbo.log_chk_mac 
ORDER BY log_mac_fch DESC;
```

## 🐛 Solución de Problemas

### Error: "Could not connect to database"
```bash
# Verificar que SQL Server está corriendo
# Verificar credenciales en cx/dat_scs.php
# Verificar que tienes acceso a la red 10.10.100.12
```

### Error: "Invalid object name 'chk_cliente_mac'"
```bash
# La tabla no existe
# Ejecutar el script SQL: database/create_table_mac.sql
```

### Error: "Email not sent"
```bash
# Verificar credenciales SMTP en p_fin.php
# Verificar que el puerto 587 está abierto
# Activar debug: $mail->SMTPDebug = 2;
```

### Error: "Registro duplicado" (cuando no debería)
```sql
-- Verificar si el registro ya existe
SELECT * FROM plataforma_web.dbo.chk_cliente_mac 
WHERE chk_mac_txt_iden = 'TU_CEDULA';

-- Si necesitas eliminar para probar:
DELETE FROM plataforma_web.dbo.chk_cliente_mac 
WHERE chk_mac_txt_iden = 'TU_CEDULA';
```

## ✅ Checklist de Pruebas

- [ ] Script SQL ejecutado exitosamente
- [ ] Tabla `chk_cliente_mac` existe en BD
- [ ] Tabla `log_chk_mac` existe en BD
- [ ] Acceso a URL funciona
- [ ] Se registra log de acceso
- [ ] Formulario se muestra correctamente
- [ ] Registro se guarda en tabla MAC
- [ ] Email se envía correctamente
- [ ] Email se recibe con formato correcto
- [ ] Validación de duplicados funciona
- [ ] Datos NO se guardan en tabla Roland
- [ ] Datos SÍ se guardan en tabla MAC

## 📊 Reportes Útiles

```sql
-- Registros por tienda MAC
SELECT 
    tnd_id,
    COUNT(*) AS TotalRegistros,
    MIN(chk_mac_fch_creacion) AS PrimerRegistro,
    MAX(chk_mac_fch_creacion) AS UltimoRegistro
FROM plataforma_web.dbo.chk_cliente_mac
GROUP BY tnd_id
ORDER BY TotalRegistros DESC;

-- Registros por género
SELECT 
    g.gen_txt_nombre AS Genero,
    COUNT(*) AS Total
FROM plataforma_web.dbo.chk_cliente_mac c
INNER JOIN plataforma_web.dbo.chk_genero g ON c.gen_id = g.gen_id
GROUP BY g.gen_txt_nombre;

-- Registros por tipo de identificación
SELECT 
    CASE chk_mac_val_tip_iden 
        WHEN 1 THEN 'Cédula'
        WHEN 2 THEN 'Pasaporte'
    END AS TipoID,
    COUNT(*) AS Total
FROM plataforma_web.dbo.chk_cliente_mac
GROUP BY chk_mac_val_tip_iden;
```

## 🎯 Siguiente Paso

Una vez que todas las pruebas pasen, el sistema estará listo para producción. Solo necesitas:

1. ✅ Ejecutar script SQL en servidor de producción
2. ✅ Generar códigos QR para cada tienda MAC
3. ✅ Imprimir y colocar QR en tiendas
4. ✅ Capacitar al personal de tienda

---

**¿Encontraste algún problema?** Revisa la sección de solución de problemas o contacta al equipo de desarrollo.


