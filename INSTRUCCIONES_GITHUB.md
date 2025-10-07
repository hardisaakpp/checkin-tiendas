# 🚀 Instrucciones para Subir a GitHub

## ✅ Pasos Completados

- [x] Git inicializado
- [x] .gitignore creado
- [x] README.md creado
- [x] Archivo de ejemplo de configuración creado
- [x] Commit inicial realizado

## 📌 Pasos Siguientes

### 1. Crear repositorio en GitHub

1. Ve a: https://github.com/new
2. Completa los datos:
   - **Repository name**: `checkin-system` (o el nombre que prefieras)
   - **Description**: Sistema de Check-in para tiendas Roland y MAC Cosmetics
   - **Visibility**: 
     - ✅ **Private** (RECOMENDADO - código propietario con credenciales)
     - ⚠️ Public (solo si estás seguro)
   - ❌ **NO marques** "Initialize this repository with a README"
3. Click en **"Create repository"**

### 2. Conectar tu repositorio local con GitHub

GitHub te mostrará comandos similares a estos. **Cópialos y ejecútalos en tu terminal:**

```bash
# Opción A: Si creaste un repositorio vacío
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
git branch -M main
git push -u origin main
```

O si prefieres SSH (recomendado para proyectos frecuentes):

```bash
git remote add origin git@github.com:TU_USUARIO/TU_REPO.git
git branch -M main
git push -u origin main
```

### 3. Verificar que se subió correctamente

1. Refresca la página de tu repositorio en GitHub
2. Deberías ver todos los archivos
3. Verifica que el README.md se vea correctamente

### 4. Comandos Útiles Git

```bash
# Ver estado actual
git status

# Ver commits realizados
git log --oneline

# Ver archivos ignorados
git status --ignored

# Ver diferencias antes de commit
git diff

# Agregar todos los cambios
git add .

# Commit con mensaje
git commit -m "Descripción del cambio"

# Subir cambios a GitHub
git push

# Descargar cambios de GitHub
git pull

# Ver repositorios remotos
git remote -v
```

## ⚠️ IMPORTANTE - Seguridad

### Archivos que NO están en GitHub (protegidos):

- ✅ `cx/dat_scs.php` - Credenciales de base de datos
- ✅ Todos los logs
- ✅ Carpetas de proyectos no relacionados

### Si necesitas clonar el proyecto en otro servidor:

1. Clonar repositorio:
   ```bash
   git clone https://github.com/TU_USUARIO/TU_REPO.git
   cd TU_REPO
   ```

2. Crear archivo de configuración:
   ```bash
   cp cx/dat_scs.example.php cx/dat_scs.php
   ```

3. Editar `cx/dat_scs.php` con las credenciales correctas

4. Configurar permisos (si es Linux):
   ```bash
   chmod 644 cx/dat_scs.php
   ```

## 🔄 Workflow Recomendado

### Antes de hacer cambios importantes:

```bash
# Crear rama para desarrollo
git checkout -b feature/nueva-tabla-mac

# Hacer tus cambios...

# Commit
git add .
git commit -m "Agregada tabla separada para MAC"

# Subir rama
git push -u origin feature/nueva-tabla-mac
```

### Después de probar que funciona:

```bash
# Volver a main
git checkout main

# Fusionar cambios
git merge feature/nueva-tabla-mac

# Subir a GitHub
git push
```

## 📊 Ejemplo de Flujo Completo

```bash
# 1. Ver qué archivos cambiaron
git status

# 2. Ver las diferencias
git diff

# 3. Agregar archivos modificados
git add checkin-mac/p_fin.php checkin-mac/p_verif.php

# 4. Commit descriptivo
git commit -m "Modificado checkin-mac para usar tabla chk_cliente_mac"

# 5. Subir a GitHub
git push

# 6. Verificar en GitHub que se subió correctamente
```

## 🆘 Solución de Problemas

### Error: "Permission denied"
```bash
# Verificar tu identidad
git config user.name
git config user.email

# Configurar si es necesario
git config user.name "Tu Nombre"
git config user.email "tu@email.com"
```

### Error: "remote origin already exists"
```bash
# Ver remotes actuales
git remote -v

# Eliminar remote existente
git remote remove origin

# Agregar nuevamente
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
```

### Error: "refusing to merge unrelated histories"
```bash
git pull origin main --allow-unrelated-histories
```

## 📝 Próximos Commits Sugeridos

Después de implementar los cambios de la tabla MAC:

```bash
git add checkin-mac/
git commit -m "Implementada tabla separada chk_cliente_mac para MAC Cosmetics"
git push
```

Agregar script SQL:

```bash
git add database/create_table_mac.sql
git commit -m "Agregado script SQL para crear tabla MAC"
git push
```

---

**¿Necesitas ayuda con algún paso?** Guarda este archivo para referencia futura.

