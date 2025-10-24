using System;

namespace ActiveDirectory.Models
{
    public class UserDetails
    {
        public string? Guid { get; set; }
        public string? Username { get; set; }
        public string? DisplayName { get; set; }
        public string? Email { get; set; }
        public string? Phone { get; set; }
        public string? Department { get; set; }
        public string? Title { get; set; }
        public DateTime? LastLogon { get; set; }
        public bool? Enabled { get; set; }
    }
}