<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
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
    <meta name="description" content="User  Registration for Messaging Web Application">
    <meta name="author" content="MartDevelopers Inc">
    <title>MJR Diagnostic & Medical Supply</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../admin/assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../admin/assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../admin/assets/img/icons/logo.png">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/custom.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            height: 100vh;
        }
        .registration-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        #logo-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #007bff;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="card registration-card col-md-8">
            <div class="card-body">
                <h3 class="text-center mb-4">Register Your Existing Account In MJR Diagnostic & Medical Supply</h3>
                <center><small>Precaution Is Better Than Cure</small></center>
                <form action="" id="register-form">
                    <input type="hidden" name="id" value="0">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="middlename">Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="form-control" placeholder="(optional)">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact">Contact #</label>
                            <input type="text" id="contact" name="contact" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cpassword">Confirm Password</label>
                            <input type="password" id="cpassword" name="cpassword" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="avatar">User  Avatar</label>
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/png,image/jpeg" required onchange="display_img(this)">
                    </div>
                    <div class="form-group text-center">
                        <img src="./images/no-image-available.png" id="logo-img" alt="Avatar">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <a href="./">Already have an Account?</a>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                    <div class="form-group">
                       <button type="button" class="btn btn-sm btn-secondary rounded-0 my-1" onclick="window.history.back();">Back</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function display_img(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#logo-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                $('.pop_msg').remove();
                $('#password, #cpassword').removeClass('border-danger border-success');
                $('.err_msg').remove();
                if ($('#password').val() != $('#cpassword').val()) {
                    $('#password, #cpassword').addClass('border-danger');
                    $('#cpassword').after('<small class="text-danger err_msg">Passwords do not match</small>');
                    return false;
                }
                var _this = $(this);
                var _el = $('<div>').addClass('pop_msg');
                _this.find('button').attr('disabled', true).text('Saving data...');
                $.ajax({
                    url: './Actions.php?a=save_user',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    dataType: 'json',
                    error: function(err) {
                        console.log(err);
                        _el.addClass('alert alert-danger').text("An error occurred.");
                        _this.prepend(_el);
                        _el.show('slow');
                        _this.find('button').attr('disabled', false).text('Register');
                    },
                    success: function(resp) {
                        if (resp.status == 'success') {
                            _el.addClass('alert alert-success').text(resp.msg);
                            setTimeout(() => {
                                location.replace('./');
                            }, 2000);
                        } else {
                            _el.addClass('alert alert-danger').text(resp.msg);
                        }
                        _this.prepend(_el);
                        _el.show('slow');
                        _this.find('button').attr('disabled', false).text('Register');
                    }
                });
            });
        });
    </script>
</body>
</html>