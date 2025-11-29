<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Support Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 10px;
            color: #333;
            overflow: hidden;
        }
        
        /* Chat Support Icon */
        .chat-support-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #7c3aed;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .chat-support-icon:hover {
            transform: scale(1.1);
            background-color: #6d28d9;
        }
        
        /* Chat Container */
        .chat-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 380px;
            height: 500px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 999;
            animation: slideUp 0.3s ease-out;
        }
        
        .chat-container.active {
            display: flex;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Chat Header */
        .chat-header {
            background-color: #7c3aed;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        
        .chat-avatar {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .chat-avatar i {
            font-size: 20px;
        }
        
        .chat-header-info {
            flex-grow: 1;
        }
        
        .chat-header h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .chat-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .close-chat {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }
        
        .close-chat:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Chat Messages */
        #chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: #f9f9f9;
        }
        
        .message {
            display: flex;
            gap: 10px;
            max-width: 80%;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message.user {
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        
        .message.bot {
            align-self: flex-start;
        }
        
        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .message.user .message-avatar {
            background-color: #e0e0e0;
            color: #7c3aed;
        }
        
        .message.bot .message-avatar {
            background-color: #7c3aed;
            color: white;
        }
        
        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            max-width: 100%;
        }
        
        .message.user .message-content {
            background-color: #7c3aed;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.bot .message-content {
            background-color: white;
            color: #333;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .message-text {
            word-wrap: break-word;
            line-height: 1.5;
            font-size: 14px;
        }
        
        /* Markdown styling */
        .message-text h1, .message-text h2, .message-text h3, .message-text h4, .message-text h5, .message-text h6 {
            margin-top: 16px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .message-text h1 { font-size: 1.5em; }
        .message-text h2 { font-size: 1.3em; }
        .message-text h3 { font-size: 1.2em; }
        
        .message-text p {
            margin-bottom: 12px;
        }
        
        .message-text ul, .message-text ol {
            margin-bottom: 12px;
            padding-left: 24px;
        }
        
        .message-text li {
            margin-bottom: 4px;
        }
        
        .message-text code {
            background-color: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
            padding: 0.2em 0.4em;
            border-radius: 6px;
            font-size: 0.9em;
        }
        
        .message-text pre {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 16px;
            overflow-x: auto;
            margin-bottom: 16px;
            border: 1px solid #e0e0e0;
        }
        
        .message-text pre code {
            background-color: transparent;
            padding: 0;
            font-size: 0.9em;
            color: #333;
        }
        
        .message-text blockquote {
            border-left: 4px solid #7c3aed;
            padding-left: 16px;
            margin: 16px 0;
            color: #666;
        }
        
        .message-text table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 16px;
        }
        
        .message-text th, .message-text td {
            border: 1px solid #e0e0e0;
            padding: 8px 12px;
            text-align: left;
        }
        
        .message-text th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        
        .message-text a {
            color: #7c3aed;
            text-decoration: none;
        }
        
        .message-text a:hover {
            text-decoration: underline;
        }
        
        .message-time {
            font-size: 11px;
            margin-top: 4px;
            text-align: right;
        }
        
        .message.bot .message-time {
            color: rgba(0, 0, 0, 0.5);
        }
        
        /* Typing Indicator */
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 5px;
            padding: 12px 16px;
            background-color: white;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            width: fit-content;
            margin-bottom: 15px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .typing-indicator.active {
            display: flex;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #7c3aed;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }
        
        /* Chat Input */
        .chat-input-container {
            padding: 15px;
            background-color: white;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-shrink: 0;
        }
        
        .chat-input {
            flex-grow: 1;
            border: 1px solid #e0e0e0;
            border-radius: 24px;
            padding: 10px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            background-color: #f9f9f9;
        }
        
        .chat-input:focus {
            border-color: #7c3aed;
        }
        
        .chat-input::placeholder {
            color: #999;
        }
        
        .send-button {
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
            flex-shrink: 0;
        }
        
        .send-button:hover {
            background-color: #6d28d9;
        }
        
        .send-button:active {
            transform: scale(0.95);
        }
        
        /* File Upload */
        .file-upload {
            display: none;
        }
        
        .file-attachment {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background-color: #f5f5f5;
            border-radius: 8px;
            margin-top: 8px;
        }
        
        .file-icon {
            font-size: 20px;
            color: #7c3aed;
        }
        
        .file-info {
            flex-grow: 1;
            overflow: hidden;
        }
        
        .file-name {
            font-weight: 500;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 14px;
        }
        
        .file-size {
            font-size: 12px;
            color: #666;
        }
        
        /* Responsive Design */
        @media (max-width: 480px) {
            .chat-container {
                width: calc(100vw - 40px);
                right: 20px;
                left: 20px;
                height: 70vh;
                bottom: 80px;
            }
            
            .chat-support-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .message {
                max-width: 90%;
            }
            
            .message-text {
                font-size: 13px;
            }
            
            .chat-input {
                font-size: 13px;
                padding: 8px 14px;
            }
            
            .send-button {
                width: 36px;
                height: 36px;
            }
        }
        
        @media (max-height: 600px) and (orientation: landscape) {
            .chat-container {
                height: 80vh;
            }
        }
    </style>
</head>
<body>
    <!-- Chat Support Icon -->
    <div class="chat-support-icon" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </div>
    
    <!-- Chat Container -->
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <div class="chat-avatar">
                <i class="fas fa-headset"></i>
            </div>
            <div class="chat-header-info">
                <h2>FORTEZZA</h2>
                <p>Welcome to contact us</p>
            </div>
            <button class="close-chat" onclick="toggleChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="chat-box">
            <div class="message bot">
                <div class="message-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="message-content">
                    <div class="message-text">Hello! Welcome to Fortezza customer support. How can I help you today?</div>
                    <div class="message-time">Just now</div>
                </div>
            </div>
        </div>
        
        <div class="typing-indicator" id="typing-indicator">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
        
        <div class="chat-input-container">
            <input type="text" id="message" class="chat-input" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <input type="file" id="file-upload" class="file-upload" onchange="handleFileSelect(event)">
            <label for="file-upload" class="file-upload-btn" title="Attach file">
                <i class="fas fa-paperclip"></i>
            </label>
            <button class="send-button" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.2/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        // Chat management
        let messages = [];
        let isTyping = false;
        let selectedFile = null;
        
        // Toggle chat visibility
        function toggleChat() {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.classList.toggle('active');
            
            // Focus input when chat is opened
            if (chatContainer.classList.contains('active')) {
                document.getElementById('message').focus();
            }
        }
        
        // Handle Enter key in the input field
        function handleKeyPress(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        }
        
        // Handle file selection
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            selectedFile = file;
            
            // Show file preview in input
            const inputContainer = document.querySelector('.chat-input-container');
            const existingFilePreview = inputContainer.querySelector('.file-attachment');
            
            if (existingFilePreview) {
                existingFilePreview.remove();
            }
            
            const filePreview = document.createElement('div');
            filePreview.className = 'file-attachment';
            filePreview.innerHTML = `
                <i class="fas fa-file file-icon"></i>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${formatFileSize(file.size)}</div>
                </div>
                <button onclick="removeFile()" style="background: none; border: none; color: #999; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            inputContainer.insertBefore(filePreview, inputContainer.firstChild);
        }
        
        // Remove selected file
        function removeFile() {
            selectedFile = null;
            document.getElementById('file-upload').value = '';
            const filePreview = document.querySelector('.file-attachment');
            if (filePreview) {
                filePreview.remove();
            }
        }
        
        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Send a message
        function sendMessage() {
            if (isTyping) return;
            
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();
            
            // Check if either message or file is provided
            if (!message && !selectedFile) return;
            
            const chatBox = document.getElementById("chat-box");
            const typingIndicator = document.getElementById("typing-indicator");
            
            // Create user message element
            const userMessageElement = document.createElement("div");
            userMessageElement.className = "message user";
            
            let messageContent = `
                <div class="message-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="message-content">
                    <div class="message-text">${message}</div>
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;
            
            // Add file attachment if present
            if (selectedFile) {
                messageContent = `
                    <div class="message-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-text">${message}</div>
                        <div class="file-attachment">
                            <i class="fas fa-file file-icon"></i>
                            <div class="file-info">
                                <div class="file-name">${selectedFile.name}</div>
                                <div class="file-size">${formatFileSize(selectedFile.size)}</div>
                            </div>
                        </div>
                        <div class="message-time">${getCurrentTime()}</div>
                    </div>
                `;
            }
            
            userMessageElement.innerHTML = messageContent;
            chatBox.appendChild(userMessageElement);
            
            // Clear input and file selection
            messageInput.value = "";
            removeFile();
            
            // Scroll to bottom
            chatBox.scrollTop = chatBox.scrollHeight;
            
            // Show typing indicator
            typingIndicator.classList.add("active");
            chatBox.scrollTop = chatBox.scrollHeight;
            
            // Simulate bot response
            setTimeout(() => {
                typingIndicator.classList.remove("active");
                addBotResponse();
            }, 1500);
        }
        
        // Add bot response
        function addBotResponse() {
            const chatBox = document.getElementById("chat-box");
            
            // Create bot message element
            const botMessageElement = document.createElement("div");
            botMessageElement.className = "message bot";
            
            // Sample responses
            const responses = [
                "Thank you for your message. Our team will get back to you shortly.",
                "I understand your concern. Let me help you with that.",
                "Thank you for reaching out to OPPEIN Home. How can I assist you today?",
                "I'm here to help. Could you provide more details about your inquiry?",
                "Our customer service team is available Monday to Friday, 9am to 5pm. Is there anything specific I can help you with?"
            ];
            
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            
            botMessageElement.innerHTML = `
                <div class="message-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="message-content">
                    <div class="message-text">${randomResponse}</div>
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;
            
            chatBox.appendChild(botMessageElement);
            
            // Scroll to bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        
        // Get current time in a readable format
        function getCurrentTime() {
            const now = new Date();
            return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        // Close chat when clicking outside
        document.addEventListener('click', function(event) {
            const chatContainer = document.getElementById('chat-container');
            const chatIcon = document.querySelector('.chat-support-icon');
            
            if (chatContainer.classList.contains('active') && 
                !chatContainer.contains(event.target) && 
                !chatIcon.contains(event.target)) {
                chatContainer.classList.remove('active');
            }
        });
    </script>
</body>
</html>