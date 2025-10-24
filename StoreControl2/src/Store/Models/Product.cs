using System;

namespace Store.Models
{
    public class Product
    {
        public int ProductId { get; set; }
        public string Name { get; set; }
        public decimal Price { get; set; }
        public int Stock { get; set; }

        public Product(int productId, string name, decimal price, int stock)
        {
            ProductId = productId;
            Name = name;
            Price = price;
            Stock = stock;
        }

        public override string ToString()
        {
            return $"{Name} (ID: {ProductId}) - Price: {Price:C}, Stock: {Stock}";
        }
    }
}