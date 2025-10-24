using System;

namespace Store.Models
{
    public class Sale
    {
        public int SaleId { get; set; }
        public int ProductId { get; set; }
        public int QuantitySold { get; set; }
        public DateTime SaleDate { get; set; }
        public decimal TotalAmount { get; set; }

        public Sale()
        {
            SaleDate = DateTime.Now;
        }

        public override string ToString()
        {
            return $"Venta ID: {SaleId} - Producto: {ProductId} - Cantidad: {QuantitySold} - Fecha: {SaleDate:dd/MM/yyyy} - Total: ${TotalAmount:F2}";
        }
    }
}