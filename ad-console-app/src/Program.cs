using System;
using AdConsoleApp.Services;

namespace AdConsoleApp
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("=== Aplicación de Consola Active Directory ===");
            Console.WriteLine("Dominio: sunsetcorp.local (10.10.100.8)\n");

            // Configuración del dominio
            string domain = "sunsetcorp.local";
            var adService = new ActiveDirectoryService(domain);

            // Clase 1: Conectar e importar usuarios
            Console.WriteLine("1. Probando conexión al Active Directory...");
            adService.ConnectToAD();

            Console.WriteLine("\n2. Importando lista de usuarios...");
            adService.ImportUsers();

            // Clase 2: Autenticación
            Console.WriteLine("\n3. Proceso de login:");
            while (true)
            {
                Console.Write("\nIngrese su usuario: ");
                string username = Console.ReadLine();

                if (string.IsNullOrEmpty(username))
                {
                    Console.WriteLine("Saliendo de la aplicación...");
                    break;
                }

                Console.Write("Ingrese su contraseña: ");
                string password = ReadPassword();

                Console.WriteLine("\nAutenticando usuario...");
                if (adService.AuthenticateUser(username, password))
                {
                    Console.WriteLine("\n¡Login exitoso!");
                    
                    // Consumir datos del usuario autenticado
                    adService.GetUserData(username);
                    
                    Console.WriteLine("\nPresione cualquier tecla para salir...");
                    Console.ReadKey();
                    break;
                }
                else
                {
                    Console.WriteLine("\nCredenciales incorrectas. Intente nuevamente.");
                    Console.WriteLine("(Presione Enter sin usuario para salir)");
                }
            }
        }

        // Método para ocultar la contraseña mientras se escribe
        private static string ReadPassword()
        {
            string password = "";
            ConsoleKey key;
            do
            {
                var keyInfo = Console.ReadKey(intercept: true);
                key = keyInfo.Key;

                if (key == ConsoleKey.Backspace && password.Length > 0)
                {
                    Console.Write("\b \b");
                    password = password[0..^1];
                }
                else if (!char.IsControl(keyInfo.KeyChar))
                {
                    Console.Write("*");
                    password += keyInfo.KeyChar;
                }
            } while (key != ConsoleKey.Enter);

            Console.WriteLine();
            return password;
        }
    }
}