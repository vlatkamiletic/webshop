<?php
session_start(); // Start the session

// Include your database connection file
include 'db.php'; // Adjust the path as necessary

// Check if the user is logged in
$response = array('loggedIn' => isset($_SESSION['user_id'])); // Assuming user_id is stored in session

header('Content-Type: application/json');
echo json_encode($response);
?>