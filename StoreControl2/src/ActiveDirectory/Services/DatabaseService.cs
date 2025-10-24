using System;
using System.Collections.Generic;
using Microsoft.Data.SqlClient;
using ActiveDirectory.Models;

namespace ActiveDirectory.Services
{
    public class DatabaseService
    {
        private readonly string _connectionString;

        public DatabaseService(string connectionString)
        {
            _connectionString = connectionString;
        }

        public bool TestConnection()
        {
            try
            {
                using var connection = new SqlConnection(_connectionString);
                connection.Open();
                return true;
            }
            catch
            {
                return false;
            }
        }

        public int ImportUsersToDatabase(List<UserDetails> usuarios)
        {
            int insertedCount = 0;
            int updatedCount = 0;

            using var connection = new SqlConnection(_connectionString);
            connection.Open();

            Console.WriteLine($"   📋 Procesando {usuarios.Count} usuarios desde Active Directory...");
            Console.WriteLine($"   🔒 Conservando: id_usuario, ad_guid (identificadores únicos)");
            Console.WriteLine($"   🔄 Sincronizando: datos actuales desde AD\n");

            foreach (var usuario in usuarios)
            {
                try
                {
                    // Verificar si el usuario ya existe
                    if (UsuarioExiste(connection, usuario.Username))
                    {
                        ActualizarUsuario(connection, usuario);
                        updatedCount++;
                        // El mensaje se muestra dentro de ActualizarUsuario
                    }
                    else
                    {
                        InsertarUsuario(connection, usuario);
                        insertedCount++;
                        Console.WriteLine($"   ➕ Nuevo usuario: {usuario.Username} - {usuario.DisplayName}");
                    }
                }
                catch (Exception ex)
                {
                    Console.WriteLine($"   ❌ Error con usuario {usuario.Username}: {ex.Message}");
                }
            }

            // Resumen de la sincronización
            Console.WriteLine($"\n📊 RESUMEN DE SINCRONIZACIÓN:");
            Console.WriteLine($"   ➕ Usuarios nuevos insertados: {insertedCount}");
            Console.WriteLine($"   🔄 Usuarios existentes sincronizados: {updatedCount}");
            Console.WriteLine($"   📅 Fecha de sincronización: {DateTime.Now:dd/MM/yyyy HH:mm:ss}");

            return insertedCount;
        }

        private bool UsuarioExiste(SqlConnection connection, string usuarioAd)
        {
            string query = "SELECT COUNT(*) FROM usuarios_ad WHERE usuario_ad = @usuario_ad";
            
            using var command = new SqlCommand(query, connection);
            command.Parameters.AddWithValue("@usuario_ad", usuarioAd ?? "");
            
            int count = (int)command.ExecuteScalar();
            return count > 0;
        }

        private void InsertarUsuario(SqlConnection connection, UserDetails usuario)
        {
            string query = @"
                INSERT INTO usuarios_ad 
                (ad_guid, usuario_ad, nombre_completo, correo_electronico, telefono, 
                 departamento, puesto, estado_cuenta, fecha_creacion, usuario_creacion, 
                 fecha_modificacion, usuario_modificacion, activo)
                VALUES 
                (@ad_guid, @usuario_ad, @nombre_completo, @correo_electronico, @telefono,
                 @departamento, @puesto, @estado_cuenta, @fecha_creacion, @usuario_creacion,
                 @fecha_modificacion, @usuario_modificacion, @activo)";

            using var command = new SqlCommand(query, connection);

            command.Parameters.AddWithValue("@ad_guid", usuario.Guid ?? Guid.NewGuid().ToString());
            command.Parameters.AddWithValue("@usuario_ad", usuario.Username ?? "");
            command.Parameters.AddWithValue("@nombre_completo", usuario.DisplayName ?? "");
            command.Parameters.AddWithValue("@correo_electronico", usuario.Email ?? "");
            command.Parameters.AddWithValue("@telefono", usuario.Phone ?? "");
            command.Parameters.AddWithValue("@departamento", usuario.Department ?? "");
            command.Parameters.AddWithValue("@puesto", usuario.Title ?? "");
            command.Parameters.AddWithValue("@estado_cuenta", usuario.Enabled ?? true);
            command.Parameters.AddWithValue("@fecha_creacion", DateTime.Now);
            command.Parameters.AddWithValue("@usuario_creacion", "SISTEMA_AD");
            command.Parameters.AddWithValue("@fecha_modificacion", DateTime.Now);
            command.Parameters.AddWithValue("@usuario_modificacion", "SISTEMA_AD");
            command.Parameters.AddWithValue("@activo", true);

            command.ExecuteNonQuery();
        }

        private void ActualizarUsuario(SqlConnection connection, UserDetails usuario)
        {
            // SINCRONIZACIÓN: Solo actualiza campos que pueden cambiar en AD
            // CONSERVA: id_usuario, ad_guid, fecha_creacion, usuario_creacion
            string query = @"
                UPDATE usuarios_ad SET
                    nombre_completo = @nombre_completo,
                    correo_electronico = @correo_electronico,
                    telefono = @telefono,
                    departamento = @departamento,
                    puesto = @puesto,
                    estado_cuenta = @estado_cuenta,
                    fecha_modificacion = @fecha_modificacion,
                    usuario_modificacion = @usuario_modificacion
                WHERE usuario_ad = @usuario_ad 
                  AND activo = 1";

            using var command = new SqlCommand(query, connection);

            command.Parameters.AddWithValue("@usuario_ad", usuario.Username ?? "");
            command.Parameters.AddWithValue("@nombre_completo", usuario.DisplayName ?? "");
            command.Parameters.AddWithValue("@correo_electronico", usuario.Email ?? "");
            command.Parameters.AddWithValue("@telefono", usuario.Phone ?? "");
            command.Parameters.AddWithValue("@departamento", usuario.Department ?? "");
            command.Parameters.AddWithValue("@puesto", usuario.Title ?? "");
            command.Parameters.AddWithValue("@estado_cuenta", usuario.Enabled ?? true);
            command.Parameters.AddWithValue("@fecha_modificacion", DateTime.Now);
            command.Parameters.AddWithValue("@usuario_modificacion", "SISTEMA_AD_SYNC");

            int filasAfectadas = command.ExecuteNonQuery();
            
            // Log para auditoría
            if (filasAfectadas > 0)
            {
                Console.WriteLine($"   🔄 Sincronizado: {usuario.Username} - {usuario.DisplayName}");
            }
        }

        public List<UserAD> GetUsersFromDatabase()
        {
            var usuarios = new List<UserAD>();

            using var connection = new SqlConnection(_connectionString);
            connection.Open();

            string query = @"
                SELECT id_usuario, ad_guid, usuario_ad, nombre_completo, correo_electronico,
                       telefono, departamento, puesto, estado_cuenta, fecha_creacion,
                       usuario_creacion, fecha_modificacion, usuario_modificacion, activo
                FROM usuarios_ad 
                WHERE activo = 1
                ORDER BY nombre_completo";

            using var command = new SqlCommand(query, connection);
            using var reader = command.ExecuteReader();

            while (reader.Read())
            {
                usuarios.Add(new UserAD
                {
                    IdUsuario = reader.GetInt32(0), // id_usuario
                    AdGuid = reader.IsDBNull(1) ? null : reader.GetGuid(1).ToString(), // ad_guid - convertir Guid a string
                    UsuarioAd = reader.IsDBNull(2) ? null : reader.GetString(2), // usuario_ad
                    NombreCompleto = reader.IsDBNull(3) ? null : reader.GetString(3), // nombre_completo
                    CorreoElectronico = reader.IsDBNull(4) ? null : reader.GetString(4), // correo_electronico
                    Telefono = reader.IsDBNull(5) ? null : reader.GetString(5), // telefono
                    Departamento = reader.IsDBNull(6) ? null : reader.GetString(6), // departamento
                    Puesto = reader.IsDBNull(7) ? null : reader.GetString(7), // puesto
                    EstadoCuenta = GetSafeBooleanValue(reader, 8), // estado_cuenta
                    FechaCreacion = reader.GetDateTime(9), // fecha_creacion
                    UsuarioCreacion = reader.IsDBNull(10) ? null : reader.GetString(10), // usuario_creacion
                    FechaModificacion = reader.IsDBNull(11) ? null : (DateTime?)reader.GetDateTime(11), // fecha_modificacion
                    UsuarioModificacion = reader.IsDBNull(12) ? null : reader.GetString(12), // usuario_modificacion
                    Activo = GetSafeBooleanValue(reader, 13) // activo
                });
            }

            return usuarios;
        }

        private bool GetSafeBooleanValue(Microsoft.Data.SqlClient.SqlDataReader reader, int columnIndex)
        {
            try
            {
                if (reader.IsDBNull(columnIndex))
                    return false;

                // Intentar obtener como Boolean directamente
                var value = reader.GetValue(columnIndex);
                
                if (value is bool boolValue)
                    return boolValue;
                
                if (value is string stringValue)
                {
                    // Manejar strings como "true", "false", "1", "0"
                    return stringValue.ToLower() == "true" || stringValue == "1";
                }
                
                if (value is int intValue)
                {
                    return intValue != 0;
                }
                
                if (value is byte byteValue)
                {
                    return byteValue != 0;
                }

                // Intentar convertir a string y luego a bool
                return Convert.ToBoolean(value);
            }
            catch
            {
                // Si falla todo, devolver false por defecto
                return false;
            }
        }
    }
}