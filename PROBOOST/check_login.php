<?php
session_start();

include 'db.php'; 

// Check if the user is logged in
$response = array('loggedIn' => isset($_SESSION['user_id'])); 

header('Content-Type: application/json');
echo json_encode($response);
?>