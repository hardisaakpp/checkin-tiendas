using System;
using System.Collections.Generic;
using Store.Models;
using Store.Services;

namespace Store.Controllers
{
    public class StoreController
    {
        private readonly StoreService _storeService;

        public StoreController()
        {
            _storeService = new StoreService();
        }

        public void ShowProductMenu()
        {
            bool continuar = true;
            
            while (continuar)
            {
                Console.WriteLine("\n=== GESTIÓN DE PRODUCTOS ===");
                Console.WriteLine("1. Ver productos");
                Console.WriteLine("2. Agregar producto");
                Console.WriteLine("3. Volver al menú principal");
                Console.Write("Opción: ");
                
                string option = Console.ReadLine();
                
                switch (option)
                {
                    case "1":
                        ShowProducts();
                        break;
                    case "2":
                        AddProduct();
                        break;
                    case "3":
                        continuar = false;
                        break;
                    default:
                        Console.WriteLine("Opción inválida");
                        break;
                }
                
                if (continuar)
                {
                    Console.WriteLine("\nPresiona cualquier tecla para continuar...");
                    Console.ReadKey();
                    Console.Clear();
                }
            }
        }

        public void ShowProducts()
        {
            var products = _storeService.GetProducts();
            
            if (products.Count == 0)
            {
                Console.WriteLine("No hay productos registrados.");
                return;
            }
            
            Console.WriteLine("\n=== LISTA DE PRODUCTOS ===");
            foreach (var product in products)
            {
                Console.WriteLine(product.ToString());
            }
        }

        public void AddProduct()
        {
            Console.WriteLine("\n=== AGREGAR PRODUCTO ===");
            
            Console.Write("ID del producto: ");
            if (!int.TryParse(Console.ReadLine(), out int id))
            {
                Console.WriteLine("ID inválido");
                return;
            }
            
            Console.Write("Nombre: ");
            string name = Console.ReadLine();
            
            Console.Write("Precio: ");
            if (!decimal.TryParse(Console.ReadLine(), out decimal price))
            {
                Console.WriteLine("Precio inválido");
                return;
            }
            
            Console.Write("Stock inicial: ");
            if (!int.TryParse(Console.ReadLine(), out int stock))
            {
                Console.WriteLine("Stock inválido");
                return;
            }
            
            var product = new Product(id, name, price, stock);
            _storeService.AddProduct(product);
            
            Console.WriteLine("✅ Producto agregado exitosamente!");
        }

        public void ProcessSale()
        {
            Console.WriteLine("\n=== PROCESAR VENTA ===");
            
            Console.Write("ID del producto: ");
            if (!int.TryParse(Console.ReadLine(), out int productId))
            {
                Console.WriteLine("ID inválido");
                return;
            }
            
            Console.Write("Cantidad a vender: ");
            if (!int.TryParse(Console.ReadLine(), out int quantity))
            {
                Console.WriteLine("Cantidad inválida");
                return;
            }
            
            var sale = new Sale 
            { 
                ProductId = productId, 
                QuantitySold = quantity, 
                SaleDate = DateTime.Now 
            };
            
            _storeService.ProcessSale(sale);
            Console.WriteLine("✅ Venta procesada exitosamente!");
        }
    }
}