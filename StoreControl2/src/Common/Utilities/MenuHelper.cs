using System;

namespace StoreControl2.Common.Utilities
{
    public static class MenuHelper
    {
        public static void DisplayMenu(string title, string[] options)
        {
            Console.WriteLine($"=== {title} ===");
            for (int i = 0; i < options.Length; i++)
            {
                Console.WriteLine($"{i + 1}. {options[i]}");
            }
            Console.WriteLine("=====================");
        }

        public static int GetUserChoice(int maxOption)
        {
            int choice;
            while (true)
            {
                Console.Write("Selecciona una opción: ");
                if (int.TryParse(Console.ReadLine(), out choice) && choice > 0 && choice <= maxOption)
                {
                    break;
                }
                Console.WriteLine("Opción inválida, intenta de nuevo.");
            }
            return choice;
        }
    }
}