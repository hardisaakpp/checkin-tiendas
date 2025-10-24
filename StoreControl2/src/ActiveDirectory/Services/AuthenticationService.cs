using System;
using System.DirectoryServices.AccountManagement;

namespace ActiveDirectory.Services
{
    public class AuthenticationService
    {
        private readonly string _domain;

        public AuthenticationService(string domain)
        {
            _domain = domain;
        }

        public bool AuthenticateUser(string username, string password)
        {
            try
            {
                using (PrincipalContext context = new PrincipalContext(ContextType.Domain, _domain))
                {
                    return context.ValidateCredentials(username, password);
                }
            }
            catch (Exception)
            {
                return false;
            }
        }
    }
}