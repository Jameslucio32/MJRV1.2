<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    $stmt = $mysqli->prepare("SELECT * FROM rpos_customers WHERE customer_id = ?");
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if customer exists
    if ($result->num_rows > 0) {
        $customer = $result->fetch_object();
        $address = htmlspecialchars($customer->street_address . ', ' . $customer->barangay . ', ' . $customer->city . ', ' . $customer->province . ', ' . $customer->country);
        
        // Check if latitude and longitude exist
        $latitude = isset($customer->latitude) ? $customer->latitude : null;
        $longitude = isset($customer->longitude) ? $customer->longitude : null;
    } else {
        die("Customer not found.");
    }
} else {
    die("Customer ID not provided.");
}

require_once('partials/_head.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Location</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGeIBEGAqOpUuMzX-PA9--RDvavLp8Bc"></script> <!-- Replace with your API key -->
    <style>
        #map {
            height: 500px; /* Set the height of the map */
            width: 100%; /* Set the width of the map */
        }
        .back-button {
            margin: 20px 0;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Location of <?php echo htmlspecialchars($customer->customer_name); ?></h1>
        
        <!-- Back Button -->
        <button class="back-button" onclick="window.history.back();">Back</button>
        
        <div id="map"></div>
    </div>

    <script>
        function initMap() {
            // Check if latitude and longitude are available
            var customerLocation = { lat: <?php echo $latitude ? $latitude : '0'; ?>, lng: <?php echo $longitude ? $longitude : '0'; ?> };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: customerLocation
            });
            var marker = new google.maps.Marker({
                position: customerLocation,
                map: map,
                title: '<?php echo $address; ?>'
            });
        }

        // Initialize the map
        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
</body>
</html>