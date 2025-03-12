<?php
require_once('partials/_head.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>MJR Diagnostic & Medical Supply</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../admin/assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../admin/assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../admin/assets/img/icons/logo.png">
    <link rel="manifest" href="../admin/assets/img/icons/logo.png">
    <link rel="mask-icon" href="../admin/assets/img/icons/logo.png" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
 <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 15px;
            height: 100vh;
            position: fixed;
        }
        .sidebar h2 {
            color: #ffffff;
            text-align: center;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px; /* Space for the sidebar */
            padding: 20px;
            flex: 1;
        }
        #chatbox {
            border: 1px solid #ccc;
            border-radius: 5px;
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }
        .message {
            margin: 5px 0;
            padding: 8px;
            border-radius: 5px;
            max-width: 70%;
            clear: both;
            display: flex;
            align-items: center;
        }
        .customer {
            background-color: #e1f5fe;
            color: #0d47a1;
            float: left;
            margin-right: 10px;
        }
        .staff {
            background-color: #c8e6c9;
            color: #1b5e20;
            float: right;
            margin-left: 10px;
        }
        .icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .input-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-bottom: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #dc3545; /* Bootstrap danger color */
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #c82333; /* Darker shade for hover effect */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Encoder Menu</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="#">Customers Messages</a>
        <a href="#">Chat History</a>
        <a href="#">Settings</a>
        <a href="">Logout</a>
    </div>
    <div class="content">
        <button class="back-button" onclick="goBack()">Back</button>
        <h2>Live Chat</h2>
        <div id="chatbox"></div>
        <div class="input-group">
            <select id="userType">
                <option value="staff">Staff</option>
            </select>
            <input type="text" id="message" placeholder="Type your message..." required>
            <button id="send">Send</button>
        </div>
    </div>

    <script>
        const chatbox = document.getElementById('chatbox');
        const messageInput = document.getElementById ('message');
        const sendButton = document.getElementById('send');
        const userTypeSelect = document.getElementById('userType');

        // Function to fetch messages
        function fetchMessages() {
            fetch('fetch_messages.php')
                .then(response => response.json())
                .then(data => {
                    chatbox.innerHTML = '';
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message ' + msg.user_type;

                        // Create an icon element
                        const icon = document.createElement('img');
                        icon.className = 'icon';
                        icon.src = msg.user_type === 'customer' ? '../admin/assets/img/theme/4.png' : '../admin/assets/img/theme/1.png'; // Replace with actual icon paths

                        messageDiv.appendChild(icon);
                        messageDiv.appendChild(document.createTextNode(`${msg.user_type}: ${msg.message}`));
                        chatbox.appendChild(messageDiv);
                    });
                    chatbox.scrollTop = chatbox.scrollHeight; // Scroll to the bottom
                });
        }

        // Send message
        sendButton.addEventListener('click', () => {
            const message = messageInput.value;
            const userType = userTypeSelect.value; // Get selected user type
            if (message) {
                fetch('send_message.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `message=${encodeURIComponent(message)}&user_type=${userType}`
                })
                .then(() => {
                    messageInput.value = ''; // Clear input
                    fetchMessages(); // Refresh messages
                });
            }
        });

        // Fetch messages every 2 seconds
        setInterval(fetchMessages, 2000);
        function goBack() {
            window.history.back(); // This will take the user back to the previous page
        }
    </script>
</body>
</html>