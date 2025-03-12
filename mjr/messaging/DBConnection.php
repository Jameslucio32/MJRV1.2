<?php
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!is_dir(__DIR__ . '/db')) {
    if (!mkdir(__DIR__ . '/db', 0755, true)) {
        die('Failed to create directory: ' . __DIR__ . '/db');
    }
}

if (!defined('host')) define('host', 'localhost');
if (!defined('username')) define('username', 'root');
if (!defined('password')) define('password', '');
if (!defined('database')) define('database', 'rposystem');

class DBConnection {
    public $conn;

    function __construct() {
        $this->conn = new mysqli(host, username, password, database);
        if ($this->conn->connect_error) {
            die("Database Connection Failed. Error: " . $this->conn->connect_error);
        }
    }

    function isMobileDevice() {
        $aMobileUA = array(
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        // Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
                return true;
            }
        }
        // Otherwise return false..
        return false;
    }

    function __destruct() {
        $this->conn->close();
    }
}

$mydb = new DBConnection();
$conn = $mydb->conn;