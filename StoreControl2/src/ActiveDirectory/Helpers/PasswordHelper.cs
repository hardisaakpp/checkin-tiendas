using System;
using System.Text.RegularExpressions;

namespace ActiveDirectory.Helpers
{
    public static class PasswordHelper
    {
        public static string ReadPassword()
        {
            string password = "";
            ConsoleKeyInfo key;
            
            do
            {
                key = Console.ReadKey(true);
                
                if (key.Key != ConsoleKey.Backspace && key.Key != ConsoleKey.Enter)
                {
                    password += key.KeyChar;
                    Console.Write("*");
                }
                else if (key.Key == ConsoleKey.Backspace && password.Length > 0)
                {
                    password = password.Substring(0, (password.Length - 1));
                    Console.Write("\b \b");
                }
            } while (key.Key != ConsoleKey.Enter);
            
            return password;
        }

        public static bool IsValidPassword(string password)
        {
            // Password must be at least 8 characters long and contain at least one number and one letter
            return password.Length >= 8 && 
                   Regex.IsMatch(password, @"[A-Za-z]") && 
                   Regex.IsMatch(password, @"[0-9]");
        }

        public static string HashPassword(string password)
        {
            // Implement a hashing algorithm for the password (e.g., SHA256)
            using (var sha256 = System.Security.Cryptography.SHA256.Create())
            {
                var bytes = sha256.ComputeHash(System.Text.Encoding.UTF8.GetBytes(password));
                return Convert.ToBase64String(bytes);
            }
        }

        public static bool VerifyPassword(string enteredPassword, string storedHash)
        {
            // Hash the entered password and compare with the stored hash
            var hashOfEnteredPassword = HashPassword(enteredPassword);
            return hashOfEnteredPassword == storedHash;
        }
    }
}