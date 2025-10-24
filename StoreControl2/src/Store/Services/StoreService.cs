using System;
using System.Collections.Generic;
using Store.Models;

namespace Store.Services
{
    public class StoreService
    {
        private List<Product> _products;
        private List<Sale> _sales;
        private int _nextSaleId;

        public StoreService()
        {
            _products = new List<Product>();
            _sales = new List<Sale>();
            _nextSaleId = 1;
            
            // Agregar algunos productos de ejemplo
            InitializeSampleData();
        }

        private void InitializeSampleData()
        {
            _products.Add(new Product(1, "Laptop Dell", 1200.00m, 10));
            _products.Add(new Product(2, "Mouse Inalámbrico", 25.50m, 50));
            _products.Add(new Product(3, "Teclado Mecánico", 85.00m, 20));
        }

        public void AddProduct(Product product)
        {
            _products.Add(product);
        }

        public void ProcessSale(Sale sale)
        {
            sale.SaleId = _nextSaleId++;
            
            var product = _products.Find(p => p.ProductId == sale.ProductId);
            if (product != null && product.Stock >= sale.QuantitySold)
            {
                product.Stock -= sale.QuantitySold;
                _sales.Add(sale);
            }
            else
            {
                throw new InvalidOperationException("Stock insuficiente para la venta");
            }
        }

        public List<Product> GetProducts()
        {
            return new List<Product>(_products);
        }

        public List<Sale> GetSales()
        {
            return new List<Sale>(_sales);
        }

        public List<Inventory> GetInventory()
        {
            var inventory = new List<Inventory>();
            
            foreach (var product in _products)
            {
                inventory.Add(new Inventory 
                { 
                    ProductId = product.ProductId, 
                    QuantityAvailable = product.Stock 
                });
            }
            
            return inventory;
        }

        public Product GetProductById(int productId)
        {
            return _products.Find(p => p.ProductId == productId);
        }
    }
}