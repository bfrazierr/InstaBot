<?php
$servername = "localhost";
$username = "webuser";
$password = "Robotb9b!";

// Create connection
$conn = new mysqli($servername, $username, $password, "insta");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>