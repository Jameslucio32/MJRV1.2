<?php
session_start();
include('config/config.php');
require '../../vendor/autoload.php'; // Adjust the path as needed

require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Email verification function
function sendVerificationEmail($email, $otp) {
    $mail = new PHPMailer(true); // Create a new PHPMailer instance
    try {
        // Server settings
        $mail->isSMTP();                                          // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = 'mjrdiagnosticmedicalsupply@gmail.com'; // Your SMTP username (email)
        $mail->Password   = 'vspzoovsnlpupcgl';                 // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
        $mail->Port       = 587;                                // TCP port to connect to

        // Recipients
        $mail->setFrom('mjrdiagnosticmedicalsupply@gmail.com', 'MJR'); // Set sender's email and name
        $mail->addAddress($email);                              // Add a recipient

        // Content
        $mail->isHTML(true);                                   // Set email format to HTML
        $mail->Subject = 'OTP Verification';
        $mail->Body    = "Your OTP is: <b>$otp</b>";

        $mail->send();                                         // Send the emailhttp://localhost/MJRV1.2/mjr/customer/change_profile.php
        return true;                                          // Return true if email sent successfully
    } catch (Exception $e) {
        return false;                                         // Return false if email not sent
    }
}

// Registration
if (isset($_POST['addCustomer'])) {
    // Prevent Posting Blank Values
    if (empty($_POST["customer_phoneno"]) || empty($_POST["customer_name"]) || empty($_POST['customer_email']) || empty($_POST['customer_password'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $customer_name = $_POST['customer_name'];
        
        // Sanitize and validate phone number
        $customer_phoneno = preg_replace('/\D/', '', $_POST['customer_phoneno']); // Remove non-digit characters
        if (strlen($customer_phoneno) < 10 || strlen($customer_phoneno) > 15) {
            $err = "Phone number must be between 10 to 15 digits.";
        } else {
            // Proceed only if the phone number is valid
            $customer_email = $_POST['customer_email'];
            $customer_password = password_hash($_POST['customer_password'], PASSWORD_DEFAULT); // Hash the password securely

            // Generate a unique customer_id
            $customer_id = uniqid('cust_', true); // Example of generating a unique ID

            // Check if customer_id already exists
            $checkQuery = "SELECT * FROM rpos_customers WHERE customer_email = ?"; // Check by email instead of customer_id
            $checkStmt = $mysqli->prepare($checkQuery);
            $checkStmt->bind_param('s', $customer_email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                $err = "Email already exists. Please choose a different email.";
            } else {
                // Generate OTP
                $otp = rand(100000, 999999); // Generate a 6-digit OTP

                // Insert Captured information to a database table
                $postQuery = "INSERT INTO rpos_customers (customer_id, customer_name, customer_phoneno, customer_email, customer_password, otp, is_verified) VALUES(?,?,?,?,?,?,0)";
                $postStmt = $mysqli->prepare($postQuery);
                
                if ($postStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
                }

                // Bind parameters
                $rc = $postStmt->bind_param('ssssss', $customer_id, $customer_name, $customer_phoneno, $customer_email, $customer_password, $otp);
                
                if ($postStmt->execute()) {
                    if (sendVerificationEmail($customer_email, $otp)) {
                        $_SESSION['customer_email'] = $customer_email; // Store email in session for OTP verification
                        $_SESSION['otp'] = $otp; // Store OTP in session
                        $success = "Customer Account Created. Please check your email for the OTP to verify your account.";
                        header("refresh:1; url=verify_otp.php"); // Redirect to OTP verification page
                    } else {
                        $err = "Failed to send verification email.";
                    }
                } else {
                    // Log the error for debugging
                    error_log("Database insertion failed: " . htmlspecialchars($postStmt->error));
                    $err = "Database insertion failed. Please try again.";
                }
            }
        }
    }
}

require_once('partials/_head.php');
require_once('config/code-generator.php');
?>
<style>
    body {
        background-color: #f8f9fa; /* Light background for contrast */
    }

    .main-content {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: blanchedalmond; /* White background for the form */
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .card-body {
        padding: 30px;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da; /* Light border */
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #007bff; /* Blue border on focus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Light blue shadow */
    }

    .input-group-text {
        background-color: #007bff; /* Blue background for icons */
        color: white; /* White text for icons */
        border: none; /* Remove border */
    }

    .btn-primary {
        background-color: #007bff; /* Primary button color */
        border: none; /* Remove border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px 20px; /* Padding for buttons */
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .text-light {
        color: #6c757d; /* Light text color */
    }

    .text-black {
        color: #343a40; /* Dark text color */
    }

    .form-check-label {
        margin-left: 5px; /* Space between checkbox and label */
    }

    .form-group {
        margin-bottom: 20px; /* Space between form groups */
    }

    h1 {
        font-size: 24px; /* Heading size */
        margin-bottom: 20px; /* Space below heading */
    }
</style>
<body class="bg-dark">
    <div class="main-content">
        <div class="header bg-gradient-primary py-7">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-6">
                            <h1 class="text-white">MJR Diagnostic & Medical Supply</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <h1 class="text-black">--------Register Account--------</h1>
                            <img src="assets/img/logo.png" width="350" height="300" class="mb-4">
                            <form method="post" role="form">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_name" placeholder="Full Name" type="text">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_phoneno" placeholder="Phone Number" type="tel" pattern="[0-9]{10,15}" title="Please enter a valid phone number (10 to 15 digits)">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group -alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_email" placeholder="Email" type="email">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input class="form-control" required name="street_address" placeholder="Street Address" type="text">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input class="form-control" required name="postal_code" placeholder="Postal Code" type="text">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                   <div class="input-group input-group-alternative">
                                       <div class="input-group-prepend">
                                           <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                       </div>
                                       <select class="form-control" id="province" name="province" required onchange="populateCities(this.value)">
                                           <option value="">Select Province</option>
                                           <option value="Metro Manila">Metro Manila</option>
                                           <option value="Cebu">Cebu</option>
                                           <option value="Davao">Davao</option>
                                           <option value="Aurora">Aurora</option>
                                           <option value="Bataan">Bataan</option>
                                           <option value="Bulacan">Bulacan</option>
                                           <option value="Nueva Ecija">Nueva Ecija</option>
                                           <option value="Pampanga">Pampanga</option>
                                           <option value="Tarlac">Tarlac</option>
                                           <option value="Zambales">Zambales</option>
                                       </select>
                                   </div>
                               </div>
                               <div class="form-group mb-3">
                                   <div class="input-group input-group-alternative">
                                       <div class="input-group-prepend">
                                           <span class="input-group-text"><i class="fas fa-city"></i></span>
                                       </div>
                                       <select class="form-control" id="city" name="city" required onchange="populateBarangays(this.value)">
                                           <option value="">Select City</option>
                                       </select>
                                   </div>
                               </div>
                               <div class="form-group mb-3">
                                   <div class="input-group input-group-alternative">
                                       <div class="input-group-prepend">
                                           <span class="input-group-text"><i class="fas fa-home"></i></span>
                                       </div>
                                       <select class="form-control" id="barangay" name="barangay" required>
                                           <option value="">Select Barangay</option>
                                       </select>
                                   </div>
                               </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input id="customer_password" class="form-control" required name="customer_password" placeholder="Password" type="password">
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                        <label class="form-check-label" for="showPassword">Show Password</label>
                                    </div>
                                </div>

                                <div class="text-center">
                                </div>
                                <div class="form-group">
                                    <div class="text-left">
                                        <button type="submit" name="addCustomer" class="btn btn-primary my-4">Create Account</button>
                                        <a href="index.php" class="btn btn-success pull-right">Log In</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <a href="forgot_pwd.php" target="_blank" class="text-light"><small>Forgot password?</small></a>
                            <a href="index.php" class="text-light">
                                <button style="font-size:24px">Back<i class="fas fa-arrow-alt-circle-left"></i></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    require_once('partials/_footer.php');
    ?>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
    <script>
    function togglePassword() {
        var passwordInput = document.getElementById("customer_password");
        var showPasswordCheckbox = document.getElementById("showPassword");
        
        if (showPasswordCheckbox.checked) {
            passwordInput.type = "text"; // Change to text to show password
        } else {
            passwordInput.type = "password"; // Change back to password to hide it
        }
    }

    const addressData = {
        "Metro Manila": { // Metro Manila
            cities: {
                "Quezon City": { barangays: ['Barangay 1', 'Barangay 2', 'Barangay 3'] },
                "Makati": { barangays: ['Barangay A', 'Barangay B', 'Barangay C'] },
            }
        },
        "Cebu": { // Cebu
            cities: {
                "Cebu City": { barangays: ['Barangay 1 ', 'Barangay 2', 'Barangay 3'] },
                "Mandaue City": { barangays: ['Barangay A', 'Barangay B', 'Barangay C'] },
            }
        },
        "Davao": { // Davao
            cities: {
                "Davao City": { barangays: ['Barangay 1', 'Barangay 2', 'Barangay 3'] },
                "Tagum City": { barangays: ['Barangay A', 'Barangay B', 'Barangay C'] },
            }
        },
        "Aurora": { // Aurora
            cities: {
                "Baler": { barangays: ['Barangay Sabang', 'Barangay Longos', 'Barangay Buhangin'] },
            }
        },
        "Bataan": { // Bataan
            cities: {
                "Balanga City": { barangays: ['Barangay San Jose', 'Barangay Tenejero', 'Barangay Bagumbayan'] },
            }
        },
        "Bulacan": { // Bulacan
            cities: {
                "Malolos": { barangays: ['Barangay Barangka', 'Barangay San Gabriel', 'Barangay Santo Rosario'] },
                "San Jose del Monte": { barangays: ['Barangay Gaya-Gaya', 'Barangay San Isidro', 'Barangay Bagong Buhay'] },
                "Santa Maria": { barangays: ['Barangay San Vicente', 'Barangay Santo Niño', 'Barangay San Roque'] },
                "Baliuag": { barangays: ['Barangay Longos', 'Barangay Taal', 'Barangay San Jose'] },
                "Guiguinto": { barangays: ['Barangay Bunga', 'Barangay Bañga', 'Barangay Santisima Trinidad'] },
                "Plaridel": { barangays: ['Barangay Banga', 'Barangay Bagumbayan', 'Barangay San Mateo'] },
                "Norzagaray": { barangays: ['Barangay San Jose', 'Barangay San Juan', 'Barangay San Rafael'] },
                "Pandi": { barangays: ['Barangay Poblacion', 'Barangay Pandi', 'Barangay San Luis'] },
                "Hagonoy": { barangays: ['Barangay San Pedro', 'Barangay Poblacion', 'Barangay Banga'] },
                "Bocaue": { barangays: ['Barangay Taal', 'Barangay Poblacion', 'Barangay Banga'] },
                "Marilao": { barangays: ['Barangay Loma De Gato', 'Barangay Lias', 'Barangay Manggahan', 'Barangay San Jose', 'Barangay Sarmiento', 'Barangay Ibayo', 'Barangay Patubig', 'Barangay Sta. Rosa I', 'Barangay Sta. Rosa II', 'Barangay Bunga', 'Barangay Bagumbayan', 'Barangay Poblacion', 'Barangay Baño', 'Barangay Longos', 'Barangay San Miguel', 'Barangay San Pedro', 'Barangay San Vicente'] },
                "San Ildefonso": { barangays: ['Barangay San Jose', 'Barangay San Juan', 'Barangay San Miguel'] },
                "Angat": { barangays: ['Barangay San Isidro', 'Barangay San Antonio', 'Barangay San Vicente '] },
                "San Rafael": { barangays: ['Barangay San Isidro', 'Barangay San Juan', 'Barangay San Roque'] },
            }
        },
        "Nueva Ecija": { // Nueva Ecija
            cities: {
                "Cabanatuan City": { barangays: ['Barangay San Roque', 'Barangay San Isidro', 'Barangay Santo Cristo'] },
            }
        },
        "Pampanga": { // Pampanga
            cities: {
                "San Fernando": { barangays: ['Barangay San Jose', 'Barangay Del Pilar', 'Barangay Santo Tomas'] },
            }
        },
        "Tarlac": { // Tarlac
            cities: {
                "Tarlac City": { barangays: ['Barangay San Vicente', 'Barangay San Rafael', 'Barangay San Manuel'] },
            }
        },
        "Zambales": { // Zambales
            cities: {
                "Olongapo City": { barangays: ['Barangay East Tapinac', 'Barangay West Tapinac', 'Barangay New Cabalan'] },
            }
        }
    };

    // Populate the cities drop-down based on the selected province
    function populateCities(provinceName) {
        const citySelect = document.getElementById("city");
        citySelect.innerHTML = '<option value=""> Select City</option>'; // Reset city options

        const cities = addressData[provinceName]?.cities;
        if (cities) {
            for (const [cityName, city] of Object.entries(cities)) {
                const option = document.createElement("option");
                option.value = cityName;
                option.textContent = cityName;
                citySelect.appendChild(option);
            }
        }
    }

    // Function to populate the barangays drop-down based on the selected city
    function populateBarangays(cityName) {
        const barangaySelect = document.getElementById("barangay");
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>'; // Reset barangay options

        const provinceName = document.getElementById("province").value;
        const barangays = addressData[provinceName]?.cities[cityName]?.barangays;
        if (barangays) {
            barangays.forEach(barangay => {
                const option = document.createElement("option");
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        }
    }
    </script>
</body>

</html>