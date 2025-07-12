<?php
$servername = "localhost";
$username = "root";    
$password = "";    
$dbname = "psht_rayo";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . " [Kode Error: " . $conn->connect_errno . "]");
}

$conn->set_charset("utf8mb4");

function db_query($sql, $params = []) {
    global $conn;
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error preparing query: " . $conn->error);
        return false;
    }
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); 
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        error_log("Error executing query: " . $stmt->error);
        return false;
    }
    
    return $stmt;
}
?>