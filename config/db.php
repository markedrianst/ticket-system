<?php
$host = "localhost";
$db_name = "ticket_system";
$username = "root"; 
$password = "";      

$conn = new mysqli($host, $username, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
return $conn;

if($conn->connext_error){
    die("Connection failed:  . $conn->connect_error");
}

?>