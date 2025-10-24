using System;

namespace ActiveDirectory.Models
{
    public class UserAD
    {
        public int IdUsuario { get; set; }
        public string? AdGuid { get; set; }
        public string? UsuarioAd { get; set; }
        public string? NombreCompleto { get; set; }
        public string? CorreoElectronico { get; set; }
        public string? Telefono { get; set; }
        public string? Departamento { get; set; }
        public string? Puesto { get; set; }
        public bool EstadoCuenta { get; set; }
        public DateTime FechaCreacion { get; set; }
        public string? UsuarioCreacion { get; set; }
        public DateTime? FechaModificacion { get; set; }
        public string? UsuarioModificacion { get; set; }
        public bool Activo { get; set; }
    }
}