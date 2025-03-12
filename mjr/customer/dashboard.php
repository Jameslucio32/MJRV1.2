<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

require_once('partials/_head.php');
require_once('partials/_analytics.php');
$customer_id = $_SESSION['customer_id'];


$total_query = "
    SELECT c.customer_name, SUM(CAST(REPLACE(p.pay_amt, ',', '') AS DECIMAL(10, 2))) AS total_amount 
    FROM rpos_payments p
    JOIN rpos_customers c ON p.customer_id = c.customer_id
    WHERE c.customer_id = ?
    GROUP BY c.customer_name
";
$total_stmt = $mysqli->prepare($total_query);
$total_stmt->bind_param("s", $customer_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$customer_totals = $total_result->fetch_assoc();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .header {
    background-color: gray;
    color: white;
    padding: 40px 0;
    text-align: center;
  }

  .header h1 {
    margin: 0;
    font-size: 2.5rem;
  }

  .card {
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background-color: white;
  }

  .card-header {
    background-color: #007bff;
    color: white;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 15px;
  }

  .card-body {
    padding: 20px;
  }

  .card-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
  }

  .table {
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
  }

  .table th {
    background-color: #f1f1f1;
    color: #495057;
  }

  .table td {
    vertical-align: middle;
  }

  .text-success {
    color: #28a745 !important;
  }

  .badge {
    border-radius: 5px;
    padding: 5px 10px;
  }

  .badge-danger {
    background-color: #dc3545;
    color: white;
  }

  .badge-success {
    background-color: #28a745;
    color: white;
  }

  .btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    transition: background-color 0.3s;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  .icon {
    width: 50px;
    height: 50px;
  }

  .stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-radius: 10px;
    background-color: #e9ecef;
    margin-bottom: 20px;
  }

  .stat-card img {
    width: 60px;
    height: 60px;
  }

  .stat-card h5 {
    margin: 0;
    font-size: 1.2rem;
  }

  .stat-card span {
    font-size: 1.5rem;
    font-weight: bold;
  }


  .chatbot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 500px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: none;
    background-color: white;
    z-index: 1000;
  }

  .chatbot-header {
    background-color: rgb(180, 6, 255);
    color: white;
    padding: 10px;
    text-align: center;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
  }

  .chatbot-messages {
    height: 200px;
    overflow-y: auto;
    padding: 10px;
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


  .chatbot-message {
    color: blue;
    margin: 5px 0;
  }

  .user-message {
    color: black;
  }


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

  .chatbot-icon {
    display: inline-block;
    margin-right: 8px;
    vertical-align: middle;

  }
</style>

<body>

  <?php require_once('partials/_sidebar.php'); ?>


  <div class="main-content">

    <?php require_once('partials/_topnav.php'); ?>


    <div class="header">
      <h1>MJR Diagnostic & Medical Supply</h1>
    </div>


    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-body">
              <h3 class="mb-4">Dashboard Overview</h3>
              <div class="row">
                <div class="col-md-4">
                  <div class="stat-card">
                    <div>
                      <h5>Available Items</h5>
                      <span><?php echo $products; ?></span>
                    </div>
                    <img src="assets/img/1.png" class="icon">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="stat-card">
                    <div>
                      <h5>Total Orders</h5>
                      <span><?php echo $orders; ?></span>
                    </div>
                    <img src="assets/img/2.png" class="icon">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="stat-card">
                    <div>
                      <h5>Total Amount Spent</h5>
                      <span>₱<?php echo number_format($customer_totals['total_amount'], 2); ?></span>
                    </div>
                    <img src="assets/img/3.png" class="icon">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-xl-12 mb-5 mb-xl-0">
        <div class="card shadow">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="mb-0">Recent Orders</h3>
              </div>
              <div class="col text-right">
                <a href="orders_reports.php" class="btn btn-sm btn-primary">See all</a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th class="text-success" scope="col">Code</th>
                  <th scope="col">Customer</th>
                  <th class="text-success" scope="col">Product</th>
                  <th scope="col">Unit Price</th>
                  <th class="text-success" scope="col">Quantity</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Status</th>
                  <th class="text-success" scope="col">Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $customer_id = $_SESSION['customer_id'];
                $ret = "SELECT * FROM rpos_orders WHERE customer_id = '$customer_id' ORDER BY created_at DESC LIMIT 10";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($order = $res->fetch_object()) {
                  $prod_price = (float) str_replace(',', '', $order->prod_price);
                  $prod_qty = (int) $order->prod_qty;
                  $total = $prod_price * $prod_qty;
                ?>
                  <tr>
                    <th class="text-success" scope="row"><?php echo $order->order_code; ?></th>
                    <td><?php echo $order->customer_name; ?></td>
                    <td class="text-success"><?php echo $order->prod_name; ?></td>
                    <td>₱<?php echo number_format($prod_price, 2); ?></td>
                    <td class="text-success"><?php echo $prod_qty; ?></td>
                    <td>₱<?php echo number_format($total, 2); ?></td>
                    <td><?php echo $order->order_status == '' ? "<span class='badge badge-danger'>Not Paid</span>" : "<span class='badge badge-success'>$order->order_status</span>"; ?></td>
                    <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-xl-12">
        <div class="card shadow">
          <div class="card-header border-0">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="mb-0">My Recent Payments</h3>
              </div>
              <div class="col text-right">
                <a href="payments_reports.php" class="btn btn-sm btn-primary">See all</a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th class="text-success" scope="col">Payment Code</th>
                  <th scope="col">Payment Method</th>
                  <th class="text-success" scope="col">Order Code</th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Amount Paid</th>
                  <th class="text-success" scope="col">Date Paid</th>
                  <th class="text-success" scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $customer_id = $_SESSION['customer_id'];
                $ret = "SELECT p.*, o.order_status, o.prod_name FROM rpos_payments p JOIN rpos_orders o ON p.order_code = o.order_code WHERE p.customer_id = ? ORDER BY p.created_at DESC";
                $stmt = $mysqli->prepare($ret);
                $stmt->bind_param('s', $customer_id);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($payment = $res->fetch_object()) {
                  $pay_amt = preg_replace("/[^0-9.]/", "", $payment->pay_amt);
                ?>
                  <tr>
                    <th class="text-success" scope="row">
                      <?php echo htmlspecialchars($payment->pay_code); ?>
                    </th>
                    <th scope="row">
                      <?php echo htmlspecialchars($payment->pay_method); ?>
                    </th>
                    <td class="text-success">
                      <?php echo htmlspecialchars($payment->order_code); ?>
                    </td>
                    <td class="text-success">
                      <?php echo htmlspecialchars($payment->prod_name); ?>
                    </td>
                    <td>
                      ₱<?php echo number_format((float)$pay_amt, 2); ?>
                    </td>
                    <td class="text-success">
                      <?php echo date('d/M/Y g:i', strtotime($payment->created_at)); ?>
                    </td>
                    <td class="text-success">
                      <?php echo ucfirst($payment->order_status); ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <button id="toggleChatbot" style="position: fixed; bottom: 20px; right: 20px; background-color:rgb(217, 63, 244); color: white; border: none; border-radius: 15px; padding: 10px; cursor: pointer;">ChatBot</button>


    <div class="chatbot" id="chatbot" style="display: none;">
      <div class="chatbot-header">
        Chatbot
        <button id="backButton" style="float: right; background-color: transparent; border: none; color: white; cursor: pointer;">Back</button>
      </div>
      <div class="chatbot-messages" id="chatbotMessages"></div>
      <div class="typing-indicator" id="typingIndicator" style="display: none;">
        <span class="chatbot-icon"><i class="fas fa-robot"></i> Chatbot is typing...</span>
      </div>
      <div class="chatbot-input">
        <input type="text" id="userInput" placeholder="Type your message..." />
        <button id="sendButton">Send</button>
      </div>
      <div class="suggested-messages" id="suggestedMessages">
        <div class="suggested-message" onclick="sendSuggestedMessage('Hello')">Hello</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('About your company')">About your company</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('Contact information')">Contact information</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('What products do you offer?')">What products do you offer?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('What services do you provide?')">What services do you provide?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('What are your business hours?')">What are your business hours?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('location')">Where are you located?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('What is your return policy?')">What is your return policy?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('What payment methods do you accept?')">What payment methods do you accept?</div>
        <div class="suggested-message" onclick="sendSuggestedMessage('Do you offer shipping?')">Do you offer shipping?</div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('chatbot').style.display = 'none';
      });

      document.getElementById('toggleChatbot').addEventListener('click', function() {
        const chatbot = document.getElementById('chatbot');
        if (chatbot.style.display === 'none' || chatbot.style.display === '') {
          chatbot.style.display = 'block';
        } else {
          chatbot.style.display = 'none';
        }
      });

      document.getElementById('backButton').addEventListener('click', function() {
        const chatbot = document.getElementById('chatbot');
        chatbot.style.display = 'none';
      });

      document.getElementById('sendButton').addEventListener('click', function() {
        const userInput = document.getElementById('userInput').value;
        if (userInput) {
          addMessage('          ' + userInput, 'user-message');
          document.getElementById('userInput').value = '';
          respondToUser(userInput);
        }
      });

      function sendSuggestedMessage(message) {
        addMessage(message, 'user-message');
        document.getElementById('userInput').value = '';
        respondToUser(message);
      }

      function addMessage(message, className) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageElement = document.createElement('div');

        if (className === 'chatbot-message') {
          messageElement.innerHTML = `
                <span class="chatbot-icon"><i class="fas fa-robot"></i>Chatbot:</span> ${message}
            `;
        } else if (className === 'user-message') {
          messageElement.innerHTML = `
                <span class="user-icon"><i class="fas fa-user"></i>Me:</span> ${message}
            `;
        } else {
          messageElement.textContent = message;
        }

        messageElement.className = className ? className : 'chatbot-message';
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      }

      function respondToUser(input) {
        let response = "I'm sorry, I didn't understand that.";
        const userInput = input.toLowerCase();

        if (userInput.includes('hello')) {
          response = "Hello! How can I assist you today?";
        } else if (userInput.includes('about')) {
          response = "We are MJR Company, a leading distributor of laboratory products and medicine.";
        } else if (userInput.includes('contact')) {
          response = "You can reach us at 091750818 76 or email us at mjr2014diagnostic@yahoo.com.";
        } else if (userInput.includes('products')) {
          response = "We offer a wide range of laboratory products including diagnostic kits, medical supplies, and more. Please specify what you are looking for.";
        } else if (userInput.includes('services')) {
          response = "Our services include product distribution, customer support, and consultation for laboratory setups.";
        } else if (userInput.includes('hours')) {
          response = "Our business hours are Monday to Friday, 9 AM to 5 PM.";
        } else if (userInput.includes('location')) {
          response = "We are located at #98 Pama St. Ma. Socorro Subdivision Abangan Norte Marilao Bulacan. You can visit us during our business hours.";
        } else if (userInput.includes('return policy')) {
          response = "Our return policy allows returns within 30 days of purchase. Please ensure the product is unopened and in its original packaging.";
        } else if (userInput.includes('pricing')) {
          response = "For pricing information, please visit our website or contact our sales team.";
        } else if (userInput.includes('thank you')) {
          response = "You're welcome! If you have any more questions, feel free to ask.";
        } else if (userInput.includes('help')) {
          response = "I'm here to help! Please let me know what you need assistance with.";
        } else if (userInput.includes('payment')) {
          response = "We accept various payment methods including Credit Cards, Gcash.";
        } else if (userInput.includes('shipping')) {
          response = "We offer shipping services to ensure timely delivery of our products.";
        } else if (userInput.includes('warranty')) {
          response = "Our products come with a warranty. Please contact us for more information.";
        } else if (userInput.includes('career')) {
          response = "We are always looking for talented individuals to join our team. Please visit our website for available positions.";
        } else if (userInput.includes('feedback')) {
          response = "Your feedback is valuable to us. Please let us know how we can improve our services.";
        } else if (userInput.includes('social media')) {
          response = "You can find us on social media platforms including Facebook, Twitter, and LinkedIn.";
        }


        const typingIndicator = document.getElementById('typingIndicator');
        typingIndicator.style.display = 'block';


        setTimeout(() => {
          typingIndicator.style.display = 'none';
          addMessage(response, 'chatbot-message');
          if (response === "I'm sorry, I didn't understand that.") {
            addMessage('Please wait while our staff clarifies your message. They will assist you shortly.', 'chatbot-message notification');
          }
        }, 2000);
      }
    </script>

    <style>
      .typing-indicator {
        margin: 10px 0;
        font-style: italic;
        color: gray;
      }
    </style>