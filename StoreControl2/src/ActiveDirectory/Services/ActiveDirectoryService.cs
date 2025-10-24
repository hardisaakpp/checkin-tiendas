using System;
using System.Collections.Generic;
using System.DirectoryServices;
using System.DirectoryServices.AccountManagement;
using ActiveDirectory.Models;

namespace ActiveDirectory.Services
{
    public class ActiveDirectoryService
    {
        private readonly string _domain;
        private readonly string _serverIp;

        public ActiveDirectoryService(string domain, string serverIp)
        {
            _domain = domain;
            _serverIp = serverIp;
        }

        public bool TestConnection()
        {
            try
            {
                using (PrincipalContext context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    return !string.IsNullOrEmpty(context.Name);
                }
            }
            catch
            {
                return false;
            }
        }

        public List<UserDetails> GetAllUsers()
        {
            var usuarios = new List<UserDetails>();

            try
            {
                DirectoryEntry entry = new DirectoryEntry($"LDAP://{_serverIp}");
                DirectorySearcher searcher = new DirectorySearcher(entry);
                searcher.Filter = "(&(objectClass=user)(objectCategory=person))";
                
                // Propiedades que queremos obtener
                searcher.PropertiesToLoad.Add("objectGUID");
                searcher.PropertiesToLoad.Add("sAMAccountName");
                searcher.PropertiesToLoad.Add("cn");
                searcher.PropertiesToLoad.Add("displayName");
                searcher.PropertiesToLoad.Add("mail");
                searcher.PropertiesToLoad.Add("telephoneNumber");
                searcher.PropertiesToLoad.Add("department");
                searcher.PropertiesToLoad.Add("title");
                searcher.PropertiesToLoad.Add("lastLogon");
                searcher.PropertiesToLoad.Add("userAccountControl");

                SearchResultCollection results = searcher.FindAll();

                foreach (SearchResult result in results)
                {
                    var user = new UserDetails
                    {
                        Guid = GetPropertyValue(result, "objectGUID"),
                        Username = GetPropertyValue(result, "sAMAccountName"),
                        DisplayName = GetPropertyValue(result, "displayName") ?? GetPropertyValue(result, "cn"),
                        Email = GetPropertyValue(result, "mail"),
                        Phone = GetPropertyValue(result, "telephoneNumber"),
                        Department = GetPropertyValue(result, "department"),
                        Title = GetPropertyValue(result, "title"),
                        LastLogon = GetLastLogonDate(result),
                        Enabled = IsAccountEnabled(result)
                    };

                    usuarios.Add(user);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error al listar usuarios: {ex.Message}");
            }

            return usuarios;
        }

        public UserDetails GetUserDetails(string username)
        {
            try
            {
                using (PrincipalContext context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    UserPrincipal userPrincipal = UserPrincipal.FindByIdentity(context, username);
                    if (userPrincipal != null)
                    {
                        // Obtener información adicional del DirectoryEntry
                        DirectoryEntry de = (DirectoryEntry)userPrincipal.GetUnderlyingObject();

                        return new UserDetails
                        {
                            Guid = userPrincipal.Guid?.ToString(),
                            Username = userPrincipal.SamAccountName,
                            DisplayName = userPrincipal.DisplayName,
                            Email = userPrincipal.EmailAddress,
                            LastLogon = userPrincipal.LastLogon,
                            Enabled = userPrincipal.Enabled,
                            Department = GetDirectoryProperty(de, "department"),
                            Title = GetDirectoryProperty(de, "title"),
                            Phone = GetDirectoryProperty(de, "telephoneNumber")
                        };
                    }
                }
            }
            catch (Exception)
            {
                // Silenciar errores para mantener compatibilidad
            }

            return null;
        }

        public void ListUsers()
        {
            try
            {
                var usuarios = GetAllUsers();
                Console.WriteLine("Usuarios encontrados:");

                foreach (var user in usuarios)
                {
                    Console.WriteLine($"- {user.DisplayName} ({user.Username}) - {user.Email}");
                }

                Console.WriteLine($"\nTotal: {usuarios.Count} usuarios");
            }
            catch (Exception ex)
            {
                Console.WriteLine("❌ Error al listar usuarios: " + ex.Message);
            }
        }

        public bool ValidateUserCredentials(string username, string password)
        {
            try
            {
                using (PrincipalContext context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    return context.ValidateCredentials(username, password);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error de validación: {ex.Message}");
                return false;
            }
        }

        private string GetPropertyValue(SearchResult result, string propertyName)
        {
            if (result.Properties[propertyName].Count > 0)
            {
                if (propertyName == "objectGUID")
                {
                    byte[] guidBytes = (byte[])result.Properties[propertyName][0];
                    return new Guid(guidBytes).ToString();
                }
                return result.Properties[propertyName][0].ToString();
            }
            return null;
        }

        private DateTime? GetLastLogonDate(SearchResult result)
        {
            try
            {
                if (result.Properties["lastLogon"].Count > 0)
                {
                    long lastLogon = (long)result.Properties["lastLogon"][0];
                    if (lastLogon > 0)
                    {
                        return DateTime.FromFileTime(lastLogon);
                    }
                }
            }
            catch { }
            return null;
        }

        private bool? IsAccountEnabled(SearchResult result)
        {
            try
            {
                if (result.Properties["userAccountControl"].Count > 0)
                {
                    int userAccountControl = (int)result.Properties["userAccountControl"][0];
                    return (userAccountControl & 0x2) == 0; // 0x2 = ACCOUNTDISABLE
                }
            }
            catch { }
            return null;
        }

        private string GetProperty(SearchResult result, string propertyName)
        {
            return result.Properties[propertyName].Count > 0
                ? result.Properties[propertyName][0].ToString()
                : "N/A";
        }

        private string GetDirectoryProperty(DirectoryEntry de, string propertyName)
        {
            try
            {
                if (de.Properties[propertyName].Value != null)
                {
                    return de.Properties[propertyName].Value.ToString();
                }
            }
            catch (Exception)
            {
                // Silenciar errores
            }
            return null;
        }
    }
}