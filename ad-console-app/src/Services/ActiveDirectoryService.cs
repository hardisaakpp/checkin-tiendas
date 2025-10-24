using System;
using System.DirectoryServices;
using System.DirectoryServices.AccountManagement;

namespace AdConsoleApp.Services
{
    public class ActiveDirectoryService
    {
        private readonly string _domain;

        public ActiveDirectoryService(string domain)
        {
            _domain = domain;
        }

        // Clase 1: Conectarse al AD e importar usuarios
        public void ConnectToAD()
        {
            try
            {
                Console.WriteLine($"Conectando al dominio: {_domain}...");
                using (var context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    Console.WriteLine("Conexión exitosa al Active Directory");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error conectando al AD: {ex.Message}");
            }
        }

        public void ImportUsers()
        {
            try
            {
                using (var context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    var userPrincipal = new UserPrincipal(context);
                    var searcher = new PrincipalSearcher(userPrincipal);
                    
                    Console.WriteLine("Usuarios encontrados en el AD:");
                    foreach (var result in searcher.FindAll())
                    {
                        if (result is UserPrincipal user)
                        {
                            Console.WriteLine($"- {user.SamAccountName} ({user.DisplayName})");
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error importando usuarios: {ex.Message}");
            }
        }

        // Clase 2: Autenticación de usuario
        public bool AuthenticateUser(string username, string password)
        {
            try
            {
                using (var context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    bool isValid = context.ValidateCredentials(username, password);
                    
                    if (isValid)
                    {
                        Console.WriteLine($"Usuario {username} autenticado correctamente");
                        
                        // Capturar mensaje adicional del AD
                        var user = UserPrincipal.FindByIdentity(context, username);
                        if (user != null)
                        {
                            Console.WriteLine($"Nombre completo: {user.DisplayName}");
                            Console.WriteLine($"Email: {user.EmailAddress}");
                            Console.WriteLine($"Último login: {user.LastLogon}");
                        }
                    }
                    else
                    {
                        Console.WriteLine("Credenciales inválidas");
                    }
                    
                    return isValid;
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error en autenticación: {ex.Message}");
                return false;
            }
        }

        // Consumir datos del AD
        public void GetUserData(string username)
        {
            try
            {
                using (var context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    var user = UserPrincipal.FindByIdentity(context, username);
                    if (user != null)
                    {
                        Console.WriteLine("\n--- Datos del usuario ---");
                        Console.WriteLine($"Usuario: {user.SamAccountName}");
                        Console.WriteLine($"Nombre: {user.DisplayName}");
                        Console.WriteLine($"Email: {user.EmailAddress}");
                        Console.WriteLine($"Descripción: {user.Description}");
                        Console.WriteLine($"Habilitado: {user.Enabled}");
                        Console.WriteLine($"Último login: {user.LastLogon}");
                    }
                    else
                    {
                        Console.WriteLine("Usuario no encontrado");
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error obteniendo datos del usuario: {ex.Message}");
            }
        }
    }
}