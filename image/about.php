<?php
$title = "About Us";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="image/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/logo.png">
    <link rel="manifest" href="image/logo.png">
    <link rel="mask-icon" href="image/logo.png" color="#5bbad5">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js" defer></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('image/logo.png');
            background-size: cover;
            background-position: center;
            color: #242323;
        }

        header {
            background-color: rgba(228, 103, 235, 0.9);
            padding: 20px;
            text-align: center;
        }

        header h1 {
            color: white;
            margin: 0;
            font-size: 36px;
            transition: transform 0.3s;
        }

        header h1:hover {
            transform: scale(1.05);
        }

        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 0;
            margin-top: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .menu-item {
            position: relative;
            padding: 10px 20px;
            color: #242323;
            text-decoration: none;
            font-weight: bold;
            margin: 0 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .menu-item a {
            color: inherit;
            text-decoration: none;
        }

        .menu-item:hover {
            background-color: rgba(216, 75, 221);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .about img {
            max-width: 100%;
            height: auto;
        }

        footer {
            background-color: rgba(228, 103, 235, 0.9);
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            header h1 {
                font-size: 28px;
            }

            .menu {
                flex-direction: column;
            }

            .menu-item {
                margin: 5px 0;
                padding: 10px;
            }

            .content {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 24px;
            }

            .menu-item {
                padding: 8px;
            }
        }

        /* Chatbot styles */
        .chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hidden */
            background-color: white;
            z-index: 1000;
        }

        .chatbot-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .chatbot-messages {
            height: 200px;
            overflow-y: auto;
            padding : 10px;
            border-bottom: 1px solid #ccc;
        }

        .chatbot-input {
            display: flex;
            padding: 10px;
        }

        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .chatbot-input button {
            padding: 10px;
            margin-left: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* New styles for chatbot messages */
        .chatbot-message {
            color: blue;
            margin: 5px 0;
        }

        .user-message {
            color: black;
        }

        /* Suggested messages styles */
        .suggested-messages {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .suggested-message {
            background-color: #e7f1ff;
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .suggested-message:hover {
            background-color: #d0e7ff;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="about">
            <a href="index.php" class="text-light"><button style="font-size:24px">Back<i class="fas fa-arrow-alt-circle-left"></i></button></a>
            <img src="image/HEADER.png" width="1200" height="310">
            <h2>Company Profile</h2>
            <p>Introduction
                Founded in 2014, MJR Company set out to be a leading distributor of laboratory products and reagents for Laboratory Clinics and Hospitals.
            </p>
            <h2>Our Vision</h2>
            <p>To be the most innovative and forward-thinking distributor of laboratory supplies and equipment in clinics, hospitals, and laboratory healthcare facilities.
            </p>
            <h2>Our Mission</h2>
            <p>To be a reliable and dependable partner of the healthcare industry in providing quality patient care, maximum medical professional safety, quality products with affordable prices, efficient and on-time delivery.
            </p>
            <h2>Address</h2>
            <p>#98 Pama St. Ma. Socorro Subdivision Abangan Norte Marilao Bulacan</p>
            
            <h2>Location</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!4v1734256678087!6m8!1m7!1s9Q7M7UJA1QSiM0ufN3VH5g!2m2!1d14.77293441140193!2d120.9409878293844!3f247.31010451763902!4f-4.8216684178383105!5f0.7820865974627469" width="700" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <h2 >Contact Us</h2>
            <p>If you have any questions, feel free to reach out to us!</p>
            <p>Contact Number: 09175081876/044-913-6691</p>
            <p>Email: mjr2014diagnostic@yahoo.com</p>
            <button id="more-info">More Info</button>
        </div>
    </div>

    <!-- Chatbot Interface -->

    <script>
        // Show the chatbot when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('chatbot').style.display = 'block';
        });

        // Handle sending messages
        document.getElementById('sendButton').addEventListener('click', function() {
            const userInput = document.getElementById('userInput').value;
            if (userInput) {
                addMessage(':User     ' + userInput, 'user-message');
                document.getElementById('userInput').value = '';
                respondToUser (userInput);
            }
        });

        // Function to send suggested messages
        function sendSuggestedMessage(message) {
            addMessage(':User     ' + message, 'user-message');
            document.getElementById('userInput').value = '';
            respondToUser (message);
        }

        // Function to add messages to the chat
        function addMessage(message, className) {
            const messagesContainer = document.getElementById('chatbotMessages');
            const messageElement = document.createElement('div');
            messageElement.textContent = message;
            messageElement.className = className ? className : 'chatbot-message';
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Simple response logic
        function respondToUser (input) {
            let response = "I'm sorry, I didn't understand that.";
            if (input.toLowerCase().includes('hello')) {
                response = "Hello! How can I assist you today?";
            } else if (input.toLowerCase().includes('about')) {
                response = "We are MJR Company, a leading distributor of laboratory products and reagents.";
            } else if (input.toLowerCase().includes('contact')) {
                response = "You can reach us at 09175081876 or email us at mjr2014diagnostic@yahoo.com.";
            }
            addMessage('Chatbot: ' + response, 'chatbot-message');
        }
    </script>
</body>
</html>