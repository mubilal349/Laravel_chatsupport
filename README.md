####  CHAT OVERVIEW ####

<img width="1920" height="856" alt="image" src="https://github.com/user-attachments/assets/3527e70e-e42f-468e-b7db-3dcf04b7f7fe" />

AI Chat Application
A modern web-based chat application with AI response capabilities, message persistence, and tab management features.

Features
AI-Powered Responses: Get intelligent responses from an AI assistant
Message History: All conversations are automatically saved and restored
Tab Management: Open multiple chat tabs to organize different conversations
Responsive Design: Works seamlessly on desktop and mobile devices
Real-time Updates: Messages appear instantly without page refresh
User Authentication: Secure login system with password encryption
Modern UI: Clean, intuitive interface built with Bootstrap
Screenshots
Chat Interface
Tab Management
Mobile View

Installation
Prerequisites
PHP 7.4 or higher
MySQL 5.7 or higher
Apache/Nginx web server
Composer (for dependency management)
Setup Instructions
Clone the repository
bash

git clone https://github.com/yourusername/ai-chat-app.git
cd ai-chat-app
Install dependencies
bash

## INSTALLING COMPOSER ##
composer install







cp config/config.example.php config/config.php
Edit config/config.php with your database credentials and API keys
Set up the web server
Point your web server's document root to the public directory
Ensure URL rewriting is enabled (for Apache, use the provided .htaccess file)
Set file permissions
bash



Tab Management
Create a new tab: Click the "+" button to open a new chat tab
Switch between tabs: Click on any tab header to switch to that conversation
Close a tab: Click the "×" button on a tab to close it
Rename a tab: Double-click on a tab header to rename it

Chat Features
Send messages: Type your message in the input field and press Enter or click Send
AI responses: The AI will automatically respond to your messages
Message history: All messages are saved and restored when you return
Markdown support: Use Markdown formatting in your messages
Code highlighting: Code blocks are automatically highlighted
Configuration
AI Provider Configuration
The application supports multiple AI providers. Configure your preferred provider in config/config.php:

php

// OpenAI Configuration
'openai' => [
    'api_key' => 'your-openai-api-key',
    'model' => 'gpt-3.5-turbo',
    'max_tokens' => 1000,
    'temperature' => 0.7,
],



Authorization: Bearer session_token_here
Response:

### FOLDER STRUCTURE ###


ai-chat-app/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Middleware/
├── config/
│   └── config.php
├── database/
│   └── schema.sql
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── index.php
│   └── .htaccess
├── src/
│   ├── Chat.php
│   ├── User.php
│   └── AI/
├── storage/
│   ├── logs/
│   └── uploads/
├── tests/
├── vendor/
├── .env.example
├── composer.json
└── README.md
Contributing
We welcome contributions to the AI Chat Application! Please follow these steps:

Fork the repository
Create a feature branch (git checkout -b feature/amazing-feature)
Commit your changes (git commit -m 'Add some amazing feature')
Push to the branch (git push origin feature/amazing-feature)
Open a Pull Request
Coding Standards
Follow PSR-12 coding standards
Write clear, concise comments
Include unit tests for new features
Update documentation as needed
Troubleshooting
Common Issues
Database Connection Errors
Verify your database credentials in config/config.php
Ensure the database server is running
Check if the database exists
API Response Errors
Verify your API keys are correctly configured
Check if you've exceeded your API quota
Ensure your server can make outbound HTTP requests
File Permission Issues
Ensure the storage/ directory is writable
Check that the web server has appropriate permissions
License
This project is licensed under the MIT License - see the LICENSE file for details.

Acknowledgments
Bootstrap for the UI framework
jQuery for JavaScript utilities
OpenAI for AI capabilities
Font Awesome for icons
Support
If you encounter any issues or have questions, please:

Check the FAQ
Search existing Issues
Create a new issue with detailed information


###################### RUN THE PHP ################################

php artisan serve
