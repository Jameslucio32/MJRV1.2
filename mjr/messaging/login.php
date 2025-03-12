<?php
session_start();
if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
    header("Location:./");
    exit;
}
require_once('./DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
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
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        body {
            background: url('./assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-card h3 {
            margin-bottom: 20px;
        }
        .pop_msg {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center">Customers Service</h3>
        <form action="" id="login-form">
            <input type="hidden" name="type" value="2">
            <center><small>Please enter your Credentials.</small></center>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <input type="email" id="email" name="email" class="form-control form-control-sm rounded-0" required>
            </div>
            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0" required>
            </div>
            <div class="form-group d-flex w-100 justify-content-between">
                <a href="./registration.php">Sign Up</a>
                <button class="btn btn-sm btn-primary rounded-0 my-1">Login</button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-sm btn-secondary rounded-0 my-1" onclick="window.history.back();">Back</button>
            </div>
            <div class="pop_msg"></div>
        </form>
    </div>

    <script>
        $(function(){
            $('#login-form').submit(function(e){
                e.preventDefault();
                $('.pop_msg').remove();
                var _this = $(this);
                var _el = $('<div>').addClass('pop_msg');
                _this.find('button').attr('disabled', true);
                _this.find('button[type="submit"]').text('Logging in...');
                $.ajax({
                    url: './Actions.php?a=login',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'JSON',
                    error: err => {
                        console.log(err);
                        _el.addClass('alert alert-danger');
                        _el.text("An error occurred.");
                        _this.prepend(_el);
                        _el.show('slow');
                        _this.find('button').attr('disabled', false);
                        _this.find('button[type="submit"]').text('Login');
                    },
                    success: function(resp) {
                        if (resp.status == 'success') {
                            _el.addClass('alert alert-success');
                            setTimeout(() => {
                                location.replace('./');
                            }, 2000);
                        } else {
                            _el.addClass('alert alert-danger');
                        }
                        _el.text(resp.msg);
                        _el.hide();
                        _this.prepend(_el);
                        _el.show('slow');
                        _this.find('button').attr('disabled', false);
                        _this.find('button[type="submit"]').text('Login');
                    }
                });
            });
        });
    </script>
</body>
</html>