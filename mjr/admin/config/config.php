
<?php
    $dbuser="root";
    $dbpass="";
    $host="localhost";
    $db="rposystem";
    $mysqli=new mysqli($host,$dbuser, $dbpass, $db);

$db_inventory = "standalone_inventory_db"; 
$mysqli_inventory = new mysqli($host, $dbuser, $dbpass, $db_inventory);


if ($mysqli_inventory->connect_error) {
    die("Connection failed: " . $mysqli_inventory->connect_error);
}
?>