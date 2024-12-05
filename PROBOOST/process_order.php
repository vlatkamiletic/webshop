<?php
session_start();
include 'db.php'; // Include your database connection file

$response = ['status' => 'error', 'message' => 'Nepoznata greška.'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Morate biti prijavljeni kako biste nastavili.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the cart items from the request
$cart_items = json_decode(file_get_contents('php://input'), true);

// Check if the cart is empty
if (empty($cart_items)) {
    $response['message'] = 'Košarica je prazna.';
    echo json_encode($response);
    exit;
}

try {
    // Prepare the SQL statement for inserting cart items
    $stmt = $conn->prepare('INSERT INTO cart (user_id, product_id) VALUES (?, ?)');

    // Loop through each item in the cart and insert it into the database
    foreach ($cart_items as $item) {
        // Ensure that the item has an 'id' key
        if (isset($item['id'])) {
            $stmt->bind_param('ii', $user_id, $item['id']);
            if (!$stmt->execute()) {
                throw new Exception('SQL Error: ' . $stmt->error);
            }
        }
    }

    $response['status'] = 'success';
    $response['message'] = 'Narudžba je uspješno obrađena.';
} catch (Exception $e) {
    $response['message'] = 'Došlo je do greške: ' . $e->getMessage();
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the response
echo json_encode($response);
?>