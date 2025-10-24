# StoreControl2

## Descripción
StoreControl2 es una aplicación integrada que combina funcionalidades de Active Directory con gestión de tienda. Proporciona autenticación de usuarios contra AD y gestión de inventario, ventas y productos.

## Características
- **Integración con Active Directory**: Autenticación y gestión de usuarios
- **Gestión de Tienda**: Manejo de productos, inventario y ventas
- **Interfaz de Consola**: Menú interactivo fácil de usar

## Configuración del Dominio
- **Dominio**: `sunsetcorp.local`
- **Servidor AD**: `10.10.100.8`

## Estructura del Proyecto
```
StoreControl2/
├── src/
│   ├── ActiveDirectory/
│   │   ├── Services/
│   │   │   ├── ActiveDirectoryService.cs    # Conexión y consultas AD
│   │   │   └── AuthenticationService.cs     # Autenticación usuarios
│   │   ├── Models/
│   │   │   └── User.cs                      # Modelo de usuario AD
│   │   └── Helpers/
│   │       └── PasswordHelper.cs            # Utilidades contraseñas
│   ├── Store/                               # Módulo gestión tienda
│   ├── Common/                              # Código compartido
│   └── Program.cs                           # Punto de entrada
└── StoreControl2.csproj                     # Configuración proyecto
```

## Instalación y Ejecución

### Prerrequisitos
- .NET 6.0 SDK o superior
- Acceso a la red del dominio `sunsetcorp.local`

### Pasos
1. **Clonar o descargar el proyecto**
2. **Restaurar dependencias**:
   ```bash
   dotnet restore
   ```
3. **Compilar el proyecto**:
   ```bash
   dotnet build
   ```
4. **Ejecutar la aplicación**:
   ```bash
   dotnet run
   ```

## Funcionalidades Disponibles

### ✅ Active Directory
1. **Probar conexión**: Verifica conectividad con el servidor AD
2. **Listar usuarios**: Enumera todos los usuarios del dominio
3. **Autenticar usuario**: Valida credenciales y muestra información del usuario

### 🚧 En Desarrollo
- Gestión de productos
- Gestión de ventas
- Consultar inventario

## Uso
1. Ejecuta la aplicación
2. Selecciona una opción del menú principal
3. Sigue las instrucciones en pantalla
4. Para autenticación, ingresa usuario y contraseña del dominio

## Dependencias
- `System.DirectoryServices` v7.0.1
- `System.DirectoryServices.AccountManagement` v7.0.0

## Refactorización Realizada
Este proyecto es una refactorización de una aplicación monolítica hacia una arquitectura modular:
- **Separación de responsabilidades**
- **Servicios reutilizables**
- **Código más mantenible**
- **Fácil extensión para nuevas funcionalidades**