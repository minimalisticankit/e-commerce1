<?php
session_start();
include 'connection.php';  // Ensure this file correctly sets up $conn with mysqli

function log_error($message) {
    error_log($message);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must login first']);
        exit();
    }

    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $number = mysqli_real_escape_string($conn, trim($_POST['number']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $status = 'pending';

    // Construct the query with escaped values
    $query = "INSERT INTO `order` (user_id, name, number, email, product_name, total_price, quantity, address, method, payment_status) VALUES ('$user_id', '$name', '$number', '$email', '$product_name', '$total_price', '$quantity', '$address', '$method', '$status')";
    // Execute the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        log_error('Query failed: ' . mysqli_error($conn));
    }

    echo json_encode(['status' => 'success', 'message' => 'Order placed successfully']);
}
?>
