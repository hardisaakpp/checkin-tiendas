# Active Directory Console Application

This is a simple console application that interacts with Active Directory to import users and allow user login using a username and password.

## Project Structure

```
ad-console-app
├── src
│   ├── Program.cs          # Entry point of the application
│   ├── Services
│   │   ├── ActiveDirectoryService.cs  # Service for Active Directory interactions
│   │   └── UserImportService.cs       # Service for importing users into Active Directory
│   ├── Models
│   │   └── User.cs          # User model representing user information
│   └── Utils
│       └── ConfigHelper.cs  # Utility for reading configuration settings
├── AdConsoleApp.csproj      # Project file containing configuration and dependencies
├── app.config               # Configuration file for application settings
└── README.md                # Documentation for the project
```

## Setup Instructions

1. Clone the repository to your local machine.
2. Open the project in your preferred C# development environment.
3. Restore the project dependencies.
4. Update the `app.config` file with your Active Directory connection settings.
5. Build the project.

## Usage Guidelines

- Run the application from the console.
- Follow the prompts to log in with your Active Directory credentials.
- Use the user import functionality to add users to Active Directory from a specified source.

## Contributing

Feel free to submit issues or pull requests for improvements or bug fixes.