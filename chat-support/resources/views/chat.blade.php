<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chat Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 10px;
            color: #e0e0e0;
            overflow: hidden;
        }
        
        .app-container {
            display: flex;
            width: 100%;
            height: 100vh;
            max-width: 1200px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        
        .sidebar {
            width: 280px;
            background-color: #1a1a1a;
            border-right: 1px solid #333;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 10;
        }
        
        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #333;
        }
        
        .new-chat-btn {
            background-color: #7c3aed;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
            width: 100%;
        }
        
        .new-chat-btn:hover {
            background-color: #6d28d9;
        }
        
        .chat-history {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
        }
        
        .chat-history-item {
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chat-history-item:hover {
            background-color: #2d2d2d;
        }
        
        .chat-history-item.active {
            background-color: #2d2d2d;
            border-left: 3px solid #7c3aed;
        }
        
        .chat-history-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            font-size: 14px;
        }
        
        .delete-chat-btn {
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            padding: 5px;
            opacity: 0;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }
        
        .chat-history-item:hover .delete-chat-btn {
            opacity: 1;
        }
        
        .delete-chat-btn:hover {
            color: #ef4444;
        }
        
        .chat-container {
            flex-grow: 1;
            background-color: #1a1a1a;
            display: flex;
            flex-direction: column;
            border: 1px solid #333;
            height: 100%;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #4a0080 0%, #1a0033 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            min-height: 70px;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
        }
        
        .ai-avatar {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .ai-avatar i {
            font-size: 20px;
        }
        
        .chat-header h2 {
            font-size: 18px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .chat-status {
            margin-left: auto;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        #chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: #0f0f0f;
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
        
        .message.ai {
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
            background-color: #7c3aed;
            color: white;
        }
        
        .message.ai .message-avatar {
            background-color: #2d2d2d;
            color: #a78bfa;
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
        
        .message.ai .message-content {
            background-color: #2d2d2d;
            color: #e0e0e0;
            border-bottom-left-radius: 4px;
            border: 1px solid #444;
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
            background-color: rgba(110, 118, 129, 0.4);
            color: #e6edf3;
            padding: 0.2em 0.4em;
            border-radius: 6px;
            font-size: 0.9em;
        }
        
        .message-text pre {
            background-color: #161b22;
            border-radius: 8px;
            padding: 16px;
            overflow-x: auto;
            margin-bottom: 16px;
        }
        
        .message-text pre code {
            background-color: transparent;
            padding: 0;
            font-size: 0.9em;
        }
        
        .message-text blockquote {
            border-left: 4px solid #7c3aed;
            padding-left: 16px;
            margin: 16px 0;
            color: #a0a0a0;
        }
        
        .message-text table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 16px;
        }
        
        .message-text th, .message-text td {
            border: 1px solid #444;
            padding: 8px 12px;
            text-align: left;
        }
        
        .message-text th {
            background-color: #2d2d2d;
            font-weight: 600;
        }
        
        .message-text a {
            color: #a78bfa;
            text-decoration: none;
        }
        
        .message-text a:hover {
            text-decoration: underline;
        }
        
        .message-time {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 4px;
            text-align: right;
        }
        
        .message.ai .message-time {
            color: #888;
        }
        
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 5px;
            padding: 12px 16px;
            background-color: #2d2d2d;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            width: fit-content;
            margin-bottom: 15px;
            border: 1px solid #444;
        }
        
        .typing-indicator.active {
            display: flex;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #a78bfa;
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
        
        .chat-input-container {
            padding: 15px;
            background-color: #1a1a1a;
            border-top: 1px solid #333;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .chat-input {
            flex-grow: 1;
            border: 1px solid #444;
            border-radius: 24px;
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            background-color: #2d2d2d;
            color: #e0e0e0;
            min-height: 44px;
        }
        
        .chat-input:focus {
            border-color: #7c3aed;
        }
        
        .chat-input::placeholder {
            color: #888;
        }
        
        .send-button {
            background-color: #7c3aed;
            color: white;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
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
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #888;
            text-align: center;
            padding: 20px;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: #4a0080;
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #e0e0e0;
        }
        
        .empty-state p {
            font-size: 14px;
            max-width: 300px;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 5;
        }
        
        /* Tablet Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
            }
            
            .chat-history-title {
                max-width: 170px;
            }
        }
        
        /* Mobile Styles */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }
            
            .app-container {
                border-radius: 0;
                max-width: 100%;
                height: 100vh;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
                z-index: 20;
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .chat-header h2 {
                font-size: 16px;
            }
            
            .message {
                max-width: 90%;
            }
            
            .message-text {
                font-size: 13px;
            }
            
            .chat-input {
                font-size: 13px;
            }
            
            .chat-input-container {
                padding: 10px;
            }
            
            #chat-box {
                padding: 10px;
            }
            
            .empty-state i {
                font-size: 36px;
            }
            
            .empty-state h3 {
                font-size: 16px;
            }
            
            .empty-state p {
                font-size: 13px;
            }
        }
        
        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }
            
            .chat-header {
                padding: 10px 15px;
            }
            
            .ai-avatar {
                width: 36px;
                height: 36px;
            }
            
            .ai-avatar i {
                font-size: 18px;
            }
            
            .chat-status {
                font-size: 12px;
            }
            
            .message {
                max-width: 95%;
            }
            
            .message-avatar {
                width: 28px;
                height: 28px;
            }
            
            .message-content {
                padding: 10px 12px;
            }
            
            .message-text {
                font-size: 12px;
            }
            
            .message-time {
                font-size: 10px;
            }
            
            .chat-input {
                padding: 10px 14px;
                font-size: 12px;
                min-height: 40px;
            }
            
            .send-button {
                width: 40px;
                height: 40px;
            }
            
            .send-button i {
                font-size: 14px;
            }
            
            .chat-input-container {
                padding: 8px;
            }
            
            #chat-box {
                padding: 8px;
            }
            
            .empty-state i {
                font-size: 32px;
            }
            
            .empty-state h3 {
                font-size: 14px;
            }
            
            .empty-state p {
                font-size: 12px;
                max-width: 250px;
            }
        }
        
        /* Landscape Mobile Styles */
        @media (max-height: 500px) and (orientation: landscape) {
            .chat-header {
                padding: 8px 15px;
                min-height: 50px;
            }
            
            .ai-avatar {
                width: 32px;
                height: 32px;
            }
            
            .ai-avatar i {
                font-size: 16px;
            }
            
            .chat-header h2 {
                font-size: 14px;
            }
            
            .chat-status {
                font-size: 12px;
            }
            
            .message {
                max-width: 85%;
            }
            
            .message-avatar {
                width: 24px;
                height: 24px;
            }
            
            .message-content {
                padding: 8px 10px;
            }
            
            .message-text {
                font-size: 11px;
            }
            
            .message-time {
                font-size: 9px;
            }
            
            .chat-input {
                padding: 8px 12px;
                font-size: 12px;
                min-height: 36px;
            }
            
            .send-button {
                width: 36px;
                height: 36px;
            }
            
            .send-button i {
                font-size: 12px;
            }
            
            .chat-input-container {
                padding: 8px;
            }
            
            #chat-box {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button class="new-chat-btn" onclick="createNewChat()">
                    <i class="fas fa-plus"></i>
                    New Chat
                </button>
            </div>
            <div class="chat-history" id="chat-history">
                <!-- Chat history items will be added here dynamically -->
            </div>
        </div>
        
        <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
        
        <div class="chat-container">
            <div class="chat-header">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <h2>AI Assistant</h2>
                <div class="chat-status">
                    <div class="status-dot"></div>
                    <span>Online</span>
                </div>
            </div>
            
            <div id="chat-box">
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <h3>No active conversation</h3>
                    <p>Start a new chat by clicking the "New Chat" button or select an existing conversation from the sidebar.</p>
                </div>
            </div>
            
            <div class="typing-indicator" id="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
            
            <div class="chat-input-container">
                <input type="text" id="message" class="chat-input" placeholder="Type your question..." onkeypress="handleKeyPress(event)">
                <button class="send-button" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.2/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        // Chat management
        let currentChatId = null;
        let chats = {};
        let isTyping = false;
        
        // Initialize the app
        document.addEventListener('DOMContentLoaded', function() {
            loadChatsFromStorage();
            renderChatHistory();
            
            // If there's at least one chat, open the most recent one
            if (Object.keys(chats).length > 0) {
                const mostRecentChatId = Object.keys(chats).sort((a, b) => {
                    return new Date(chats[b].createdAt) - new Date(chats[a].createdAt);
                })[0];
                
                openChat(mostRecentChatId);
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                const menuToggle = document.querySelector('.menu-toggle');
                
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !menuToggle.contains(event.target) &&
                    sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });
        
        // Toggle sidebar visibility on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }
        
        // Load chats from localStorage
        function loadChatsFromStorage() {
            const storedChats = localStorage.getItem('ai-chats');
            if (storedChats) {
                chats = JSON.parse(storedChats);
            }
        }
        
        // Save chats to localStorage
        function saveChatsToStorage() {
            localStorage.setItem('ai-chats', JSON.stringify(chats));
        }
        
        // Create a new chat
        function createNewChat() {
            const chatId = 'chat-' + Date.now();
            chats[chatId] = {
                id: chatId,
                title: 'New Chat',
                messages: [],
                createdAt: new Date().toISOString()
            };
            
            saveChatsToStorage();
            renderChatHistory();
            openChat(chatId);
            
            // Close sidebar on mobile after creating a new chat
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        }
        
        // Open a specific chat
        function openChat(chatId) {
            if (!chats[chatId]) return;
            
            currentChatId = chatId;
            renderChatHistory();
            renderMessages();
        }
        
        // Delete a chat
        function deleteChat(chatId, event) {
            event.stopPropagation();
            
            if (!confirm('Are you sure you want to delete this chat?')) return;
            
            delete chats[chatId];
            saveChatsToStorage();
            renderChatHistory();
            
            if (currentChatId === chatId) {
                currentChatId = null;
                renderMessages();
                
                // If there are other chats, open the most recent one
                if (Object.keys(chats).length > 0) {
                    const mostRecentChatId = Object.keys(chats).sort((a, b) => {
                        return new Date(chats[b].createdAt) - new Date(chats[a].createdAt);
                    })[0];
                    
                    openChat(mostRecentChatId);
                }
            }
        }
        
        // Render the chat history in the sidebar
        function renderChatHistory() {
            const chatHistoryElement = document.getElementById('chat-history');
            chatHistoryElement.innerHTML = '';
            
            // Sort chats by creation date (newest first)
            const sortedChatIds = Object.keys(chats).sort((a, b) => {
                return new Date(chats[b].createdAt) - new Date(chats[a].createdAt);
            });
            
            sortedChatIds.forEach(chatId => {
                const chat = chats[chatId];
                const chatItem = document.createElement('div');
                chatItem.className = `chat-history-item ${chatId === currentChatId ? 'active' : ''}`;
                chatItem.onclick = () => {
                    openChat(chatId);
                    // Close sidebar on mobile after selecting a chat
                    if (window.innerWidth <= 768) {
                        toggleSidebar();
                    }
                };
                
                // Generate a title from the first user message if available
                let title = chat.title;
                if (chat.messages.length > 0) {
                    const firstUserMessage = chat.messages.find(m => m.sender === 'user');
                    if (firstUserMessage) {
                        // Truncate the message to a reasonable length for the title
                        title = firstUserMessage.text.substring(0, 30);
                        if (firstUserMessage.text.length > 30) {
                            title += '...';
                        }
                    }
                }
                
                chatItem.innerHTML = `
                    <div class="chat-history-title">${title}</div>
                    <button class="delete-chat-btn" onclick="deleteChat('${chatId}', event)">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                
                chatHistoryElement.appendChild(chatItem);
            });
        }
        
        // Render messages in the current chat
        function renderMessages() {
            const chatBox = document.getElementById('chat-box');
            
            if (!currentChatId || !chats[currentChatId]) {
                chatBox.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <h3>No active conversation</h3>
                        <p>Start a new chat by clicking the "New Chat" button or select an existing conversation from the sidebar.</p>
                    </div>
                `;
                return;
            }
            
            const chat = chats[currentChatId];
            chatBox.innerHTML = '';
            
            // If there are no messages, add a welcome message
            if (chat.messages.length === 0) {
                const welcomeMessage = document.createElement("div");
                welcomeMessage.className = "message ai";
                welcomeMessage.innerHTML = `
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-text">Hello! I'm your AI assistant. How can I help you today?</div>
                        <div class="message-time">Just now</div>
                    </div>
                `;
                chatBox.appendChild(welcomeMessage);
                return;
            }
            
            // Render all messages
            chat.messages.forEach(msg => {
                const messageElement = document.createElement("div");
                messageElement.className = `message ${msg.sender}`;
                
                const avatarIcon = msg.sender === 'user' ? 'fa-user' : 'fa-robot';
                
                // Parse markdown for AI messages
                let messageText = msg.text;
                if (msg.sender === 'ai') {
                    messageText = marked.parse(msg.text);
                }
                
                messageElement.innerHTML = `
                    <div class="message-avatar">
                        <i class="fas ${avatarIcon}"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-text">${messageText}</div>
                        <div class="message-time">${msg.time}</div>
                    </div>
                `;
                
                chatBox.appendChild(messageElement);
            });
            
            // Highlight code blocks
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });
            
            // Scroll to the bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        
        // Handle Enter key in the input field
        function handleKeyPress(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        }
        
        // Send a message
        function sendMessage() {
            if (isTyping) return;
            
            if (!currentChatId) {
                createNewChat();
                // Wait for the chat to be created before sending the message
                setTimeout(sendMessage, 100);
                return;
            }
            
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();
            if (!message) return;
            
            const chatBox = document.getElementById("chat-box");
            const typingIndicator = document.getElementById("typing-indicator");
            
            // Add user message to the current chat
            const userMessage = {
                sender: 'user',
                text: message,
                time: getCurrentTime()
            };
            
            chats[currentChatId].messages.push(userMessage);
            
            // Update the chat title if this is the first user message
            if (chats[currentChatId].messages.filter(m => m.sender === 'user').length === 1) {
                chats[currentChatId].title = message.substring(0, 30);
                if (message.length > 30) {
                    chats[currentChatId].title += '...';
                }
            }
            
            saveChatsToStorage();
            renderChatHistory();
            renderMessages();
            
            // Clear input
            messageInput.value = "";
            
            // Show typing indicator
            typingIndicator.classList.add("active");
            chatBox.scrollTop = chatBox.scrollHeight;
            
            // Send message to server
            fetch("/chat/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message })
            })
            .then(res => res.json())
            .then(data => {
                // Hide typing indicator
                typingIndicator.classList.remove("active");
                
                // Add AI response to the current chat with typing effect
                addAIResponse(data.reply);
            })
            .catch(error => {
                console.error('Error:', error);
                typingIndicator.classList.remove("active");
                
                // Add error message to the current chat
                addAIResponse('Sorry, I encountered an error. Please try again later.');
            });
        }
        
        // Add AI response with typing effect
        function addAIResponse(response) {
            isTyping = true;
            
            // Create AI message element
            const chatBox = document.getElementById("chat-box");
            const aiMessageElement = document.createElement("div");
            aiMessageElement.className = "message ai";
            
            aiMessageElement.innerHTML = `
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="message-text"></div>
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;
            
            chatBox.appendChild(aiMessageElement);
            
            const messageTextElement = aiMessageElement.querySelector('.message-text');
            
            // Parse the markdown response
            const parsedResponse = marked.parse(response);
            
            // Simulate typing effect
            let index = 0;
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = parsedResponse;
            
            // Extract text content for typing effect
            const textContent = tempDiv.textContent || tempDiv.innerText || '';
            
            // Create a function to type the response
            function typeResponse() {
                if (index < textContent.length) {
                    // Add the next character
                    messageTextElement.textContent = textContent.substring(0, index + 1);
                    
                    // Scroll to bottom as we type
                    chatBox.scrollTop = chatBox.scrollHeight;
                    
                    // Continue typing
                    index++;
                    setTimeout(typeResponse, 10); // Adjust typing speed here
                } else {
                    // Typing is complete, render the full markdown
                    messageTextElement.innerHTML = parsedResponse;
                    
                    // Highlight code blocks
                    document.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightBlock(block);
                    });
                    
                    // Save the message to the chat
                    const aiMessage = {
                        sender: 'ai',
                        text: response,
                        time: getCurrentTime()
                    };
                    
                    chats[currentChatId].messages.push(aiMessage);
                    saveChatsToStorage();
                    
                    // Final scroll to bottom
                    chatBox.scrollTop = chatBox.scrollHeight;
                    
                    isTyping = false;
                }
            }
            
            // Start typing effect
            typeResponse();
        }
        
        // Get current time in a readable format
        function getCurrentTime() {
            const now = new Date();
            return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            // Close sidebar on mobile when window is resized
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>