using System;
using ActiveDirectory.Services;
using ActiveDirectory.Helpers;

namespace StoreControl2
{
    class Program
    {
        private static readonly string DOMAIN = "sunsetcorp.local";
        private static readonly string SERVER_IP = "10.10.100.8";

        private static readonly string SQL_CONNECTION = "Server=210.17.1.54;Database=store_control;User Id=desadmin;Password=M4B31$tr$d3s4rr0ll0*BDl0g1n#1*;TrustServerCertificate=true;";
        private static AuthenticationService _authService;
        private static ActiveDirectoryService _adService;
        private static DatabaseService _dbService;

        static void Main(string[] args)
        {
            // Inicializar servicios
            _authService = new AuthenticationService(DOMAIN);
            _dbService = new DatabaseService(SQL_CONNECTION);
            _adService = new ActiveDirectoryService(DOMAIN, SERVER_IP);

            Console.WriteLine("=== APLICACIÓN ACTIVE DIRECTORY ===");
            Console.WriteLine($"Dominio: {DOMAIN}");
            Console.WriteLine($"Servidor: {SERVER_IP}");
            Console.WriteLine("=====================================\n");

            // Aquí iremos agregando funcionalidades paso a paso
            MostrarMenu();
        }

        static void MostrarMenu()
        {
            Console.WriteLine("Selecciona una opción:");
            Console.WriteLine("1. Probar conexión al AD");
            Console.WriteLine("2. Listar usuarios (Clase 1)");
            Console.WriteLine("3. Autenticar usuario (Clase 2)");
            Console.WriteLine("4. Importar usuarios a SQL Server");
            Console.WriteLine("5. Ver usuarios desde SQL Server");
            Console.WriteLine("6. Probar conexión SQL Server");
            Console.WriteLine("7. Salir");
            Console.Write("\nOpción: ");

            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    ProbarConexion();
                    break;
                case "2":
                    ListarUsuarios();
                    break;
                case "3":
                    AutenticarUsuario();
                    break;
                case "4":
                    ImportarUsuariosSQL();
                    break;
                case "5":
                    VerUsuariosSQL();
                    break;
                case "6":
                    ProbarConexionSQL();
                    break;
                case "7":
                    Environment.Exit(0);
                    break;
                default:
                    Console.WriteLine("Opción inválida");
                    break;
            }

            Console.WriteLine("\nPresiona cualquier tecla para continuar...");
            Console.ReadKey();
            Console.Clear();
            MostrarMenu();
        }

        static void ProbarConexion()
        {
            Console.WriteLine("Probando conexión al Active Directory...");
            Console.WriteLine("==========================================");

            // Diagnóstico 1: Ping al servidor
            Console.WriteLine("1. Probando conectividad de red al servidor...");
            try
            {
                System.Net.NetworkInformation.Ping ping = new System.Net.NetworkInformation.Ping();
                var reply = ping.Send(SERVER_IP, 3000);
                if (reply.Status == System.Net.NetworkInformation.IPStatus.Success)
                {
                    Console.WriteLine($"✅ Ping exitoso al servidor {SERVER_IP} ({reply.RoundtripTime}ms)");
                }
                else
                {
                    Console.WriteLine($"❌ Ping falló al servidor {SERVER_IP}: {reply.Status}");
                    Console.WriteLine("💡 Problema de conectividad de red");
                    return;
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error de ping: {ex.Message}");
                Console.WriteLine("💡 Verifica la conectividad de red");
                return;
            }

            // Diagnóstico 2: Resolución DNS
            Console.WriteLine("2. Probando resolución DNS del dominio...");
            try
            {
                var hostEntry = System.Net.Dns.GetHostEntry(DOMAIN);
                Console.WriteLine($"✅ DNS resuelto: {DOMAIN} -> {hostEntry.AddressList[0]}");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error DNS: {ex.Message}");
                Console.WriteLine("💡 Problema de resolución DNS");
                return;
            }

            // Diagnóstico 3: Conexión AD
            Console.WriteLine("3. Probando conexión a Active Directory...");
            try
            {
                if (_adService.TestConnection())
                {
                    Console.WriteLine("✅ Conexión exitosa al dominio: " + DOMAIN);
                }
                else
                {
                    Console.WriteLine("❌ Error de conexión al AD");
                    Console.WriteLine("💡 El servidor responde pero AD no está disponible");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("❌ Error de conexión AD: " + ex.Message);
                Console.WriteLine("💡 Verifica que estés en la red del dominio");
                Console.WriteLine("💡 Verifica que el servidor AD esté ejecutándose");
            }

            Console.WriteLine("==========================================");
        }

        // CLASE 1: Listar usuarios
        static void ListarUsuarios()
        {
            Console.WriteLine("=== CLASE 1: IMPORTAR USUARIOS ===");
            Console.WriteLine("Diagnóstico detallado de consulta AD...");
            Console.WriteLine("========================================");

            try
            {
                Console.WriteLine("1. Iniciando consulta LDAP...");
                Console.WriteLine($"   Servidor: {SERVER_IP}");
                Console.WriteLine($"   Dominio: {DOMAIN}");

                var usuarios = _adService.GetAllUsers();

                Console.WriteLine($"2. Consulta completada: {usuarios.Count} usuarios encontrados");

                if (usuarios.Count > 0)
                {
                    Console.WriteLine("\n✅ Usuarios encontrados en el dominio:");
                    Console.WriteLine("========================================");
                    foreach (var usuario in usuarios)
                    {
                        Console.WriteLine($"Nombre: {usuario.DisplayName} - Usuario: {usuario.Username}");
                    }
                    Console.WriteLine($"\n📊 Total: {usuarios.Count} usuarios encontrados");
                }
                else
                {
                    Console.WriteLine("\n⚠️ No se encontraron usuarios en el dominio");
                    Console.WriteLine("\n🔍 Posibles causas:");
                    Console.WriteLine("  • Credenciales del sistema sin permisos");
                    Console.WriteLine("  • Filtros LDAP muy restrictivos");
                    Console.WriteLine("  • Servidor AD con políticas cambiadas");
                    Console.WriteLine("  • Cuenta de computadora perdió confianza");

                    Console.WriteLine("\n💡 Soluciones a probar:");
                    Console.WriteLine("  • Verificar si estás en la red correcta");
                    Console.WriteLine("  • Probar desde una máquina unida al dominio");
                    Console.WriteLine("  • Contactar al administrador de AD");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("\n❌ Error detallado al consultar AD:");
                Console.WriteLine($"   Mensaje: {ex.Message}");
                Console.WriteLine($"   Tipo: {ex.GetType().Name}");

                if (ex.InnerException != null)
                {
                    Console.WriteLine($"   Error interno: {ex.InnerException.Message}");
                }

                Console.WriteLine("\n💡 Diagnóstico sugerido:");
                Console.WriteLine("  • Ejecutar opción 1 'Probar conexión' primero");
                Console.WriteLine("  • Verificar que estés en la red del dominio");
                Console.WriteLine("  • Confirmar que el servidor AD está funcionando");
            }
        }

        // CLASE 2: Autenticar usuario
        static void AutenticarUsuario()
        {
            Console.WriteLine("=== CLASE 2: AUTENTICACIÓN ===");

            Console.Write("Usuario: ");
            string usuario = Console.ReadLine();

            Console.Write("Contraseña: ");
            string password = PasswordHelper.ReadPassword();

            try
            {
                bool esValido = _authService.AuthenticateUser(usuario, password);

                if (esValido)
                {
                    Console.WriteLine("\n✅ Autenticación exitosa!");
                    Console.WriteLine("Obteniendo datos del usuario...");
                    MostrarDatosUsuario(usuario);
                }
                else
                {
                    Console.WriteLine("\n❌ Usuario o contraseña incorrectos");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("\n❌ Error durante la autenticación: " + ex.Message);
                Console.WriteLine("💡 Verifica la conectividad con el dominio");
            }
        }

        // NUEVA FUNCIONALIDAD: Importar a SQL Server
        static void ImportarUsuariosSQL()
        {
            Console.WriteLine("=== IMPORTAR USUARIOS A SQL SERVER ===");
            Console.WriteLine("Proceso de importación...");
            Console.WriteLine("========================================");

            try
            {
                Console.WriteLine("1. Obteniendo usuarios desde Active Directory...");
                var usuarios = _adService.GetAllUsers();
                Console.WriteLine($"   ✅ {usuarios.Count} usuarios obtenidos desde AD");

                if (usuarios.Count == 0)
                {
                    Console.WriteLine("\n⚠️ No hay usuarios para importar");
                    return;
                }

                Console.WriteLine("2. Conectando a SQL Server...");
                if (!_dbService.TestConnection())
                {
                    Console.WriteLine("   ❌ Error de conexión a SQL Server");
                    Console.WriteLine("   💡 Verifica la cadena de conexión");
                    return;
                }
                Console.WriteLine("   ✅ Conexión SQL Server exitosa");

                Console.WriteLine("3. Importando usuarios a la base de datos...");
                int insertados = _dbService.ImportUsersToDatabase(usuarios);

                Console.WriteLine($"\n✅ IMPORTACIÓN COMPLETADA");
                Console.WriteLine($"   📊 {insertados} usuarios importados exitosamente");
                Console.WriteLine($"   📅 Fecha de importación: {DateTime.Now:dd/MM/yyyy HH:mm:ss}");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"\n❌ Error durante la importación: {ex.Message}");
                Console.WriteLine("💡 Verifica las conexiones a AD y SQL Server");
            }
        }

        // NUEVA FUNCIONALIDAD: Ver usuarios desde SQL
        static void VerUsuariosSQL()
        {
            Console.WriteLine("=== USUARIOS DESDE SQL SERVER ===");

            try
            {
                if (!_dbService.TestConnection())
                {
                    Console.WriteLine("❌ Error de conexión a SQL Server");
                    return;
                }

                var usuarios = _dbService.GetUsersFromDatabase();

                if (usuarios.Count > 0)
                {
                    Console.WriteLine($"\n✅ {usuarios.Count} usuarios encontrados en la base de datos:");
                    Console.WriteLine("===============================================");
                    foreach (var usuario in usuarios)
                    {
                        Console.WriteLine($"Nombre: {usuario.NombreCompleto} - Usuario: {usuario.UsuarioAd}");
                        Console.WriteLine($"  Email: {usuario.CorreoElectronico ?? "No disponible"}");
                        Console.WriteLine($"  Departamento: {usuario.Departamento ?? "No disponible"}");
                        Console.WriteLine("---");
                    }
                }
                else
                {
                    Console.WriteLine("\n⚠️ No hay usuarios en la base de datos");
                    Console.WriteLine("💡 Ejecuta la opción 4 para importar usuarios primero");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error al consultar SQL Server: {ex.Message}");
            }
        }

        // NUEVA FUNCIONALIDAD: Probar conexión SQL
        static void ProbarConexionSQL()
        {
            Console.WriteLine("=== PROBAR CONEXIÓN SQL SERVER ===");

            try
            {
                Console.WriteLine("Probando conexión a SQL Server...");

                if (_dbService.TestConnection())
                {
                    Console.WriteLine("✅ Conexión exitosa a SQL Server");
                    Console.WriteLine($"Cadena de conexión: {SQL_CONNECTION.Split(';')[0]}");
                }
                else
                {
                    Console.WriteLine("❌ Error de conexión a SQL Server");
                    Console.WriteLine("💡 Verifica:");
                    Console.WriteLine("  • Que SQL Server esté ejecutándose");
                    Console.WriteLine("  • La cadena de conexión sea correcta");
                    Console.WriteLine("  • Permisos de la base de datos");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
            }
        }

        static void MostrarDatosUsuario(string usuario)
        {
            try
            {
                var user = _adService.GetUserDetails(usuario);
                if (user != null)
                {
                    Console.WriteLine("\n=== DATOS DEL USUARIO AUTENTICADO ===");
                    Console.WriteLine($"Nombre completo: {user.DisplayName ?? "No disponible"}");
                    Console.WriteLine($"Usuario: {user.Username ?? "No disponible"}");
                    Console.WriteLine($"Email: {user.Email ?? "No disponible"}");
                    Console.WriteLine($"Último logon: {(user.LastLogon?.ToString("dd/MM/yyyy HH:mm:ss") ?? "No disponible")}");
                    Console.WriteLine($"Cuenta habilitada: {(user.Enabled?.ToString() ?? "No disponible")}");
                    Console.WriteLine($"Departamento: {user.Department ?? "No disponible"}");
                    Console.WriteLine($"Título: {user.Title ?? "No disponible"}");
                }
                else
                {
                    Console.WriteLine("\n⚠️ No se pudieron obtener los detalles del usuario");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("\n❌ Error al obtener datos del usuario: " + ex.Message);
            }
        }
    }
}