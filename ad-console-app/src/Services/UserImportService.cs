using System;
using System.Collections.Generic;
using System.DirectoryServices;
using System.IO;

namespace AdConsoleApp.Services
{
    public class UserImportService
    {
        private readonly string _sourceFilePath;

        public UserImportService(string sourceFilePath)
        {
            _sourceFilePath = sourceFilePath;
        }

        public List<User> ImportUsers()
        {
            var users = new List<User>();

            if (!File.Exists(_sourceFilePath))
            {
                throw new FileNotFoundException("Source file not found.", _sourceFilePath);
            }

            var lines = File.ReadAllLines(_sourceFilePath);
            foreach (var line in lines)
            {
                var parts = line.Split(',');
                if (parts.Length >= 2)
                {
                    var user = new User
                    {
                        Username = parts[0].Trim(),
                        Password = parts[1].Trim()
                    };
                    users.Add(user);
                }
            }

            return users;
        }
    }
}