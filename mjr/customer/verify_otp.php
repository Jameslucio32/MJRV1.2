<?php
session_start();
include('config/config.php');

if (isset($_POST['verifyOtp'])) {
    $entered_otp = $_POST['otp'];
    if ($entered_otp == $_SESSION['otp']) {
        // Update the user's verification status in the database
        $email = $_SESSION['customer_email'];
        $updateQuery = "UPDATE rpos_customers SET is_verified = 1 WHERE customer_email = ?";
        $updateStmt = $mysqli->prepare($updateQuery);
        $updateStmt->bind_param('s', $email);
        $updateStmt->execute();

        // Clear session variables
        unset($_SESSION['otp']);
        unset($_SESSION['customer_email']);

        $success = "Your account has been verified successfully!";
        header("refresh:2; url=index.php"); // Redirect to login page
    } else {
        $err = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <?php require_once('partials/_head.php'); ?>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .alert {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form method="post" aria-labelledby="otpForm">
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" class="form-control" placeholder="******" required aria-required="true">
            </div>
            <button type="submit" name="verifyOtp" class="btn">Verify</button>
        </form>
        <?php if (isset($err)) echo "<div class='alert alert-danger'>$err</div>"; ?>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    </div>
</body>
</html>