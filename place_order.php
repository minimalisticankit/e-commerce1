<?php
session_start();
include 'connection.php';

function log_error($message) {
    error_log($message);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

error_log('Session Save Path: ' . ini_get('session.save_path'));
error_log('Session Cookie Params: ' . print_r(session_get_cookie_params(), true));
error_log('Session ID: ' . session_id());
error_log('Session Data on Start: ' . print_r($_SESSION, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('Session Data on POST: ' . print_r($_SESSION, true));

    if (!isset($_SESSION['user_id'])) {
        log_error('You must login first. Session ID: ' . session_id());
    }

    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $number = mysqli_real_escape_string($conn, trim($_POST['number']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $status = 'pending';

    mysqli_begin_transaction($conn);

    try {
        $product_query = "SELECT quantity FROM products WHERE id = '$product_id'";
        $product_result = mysqli_query($conn, $product_query);
        if (!$product_result) {
            throw new Exception('Query failed: ' . mysqli_error($conn));
        }
        $product = mysqli_fetch_assoc($product_result);
        if (!$product) {
            throw new Exception('Product not found');
        }

        $current_quantity = $product['quantity'];

        if ($current_quantity < $quantity) {
            throw new Exception('Not enough stock available');
        }

        $orders_query = "INSERT INTO `orders` (user_id, name, number, email, product_name, total_price, quantity, address, method, payment_status) VALUES ('$user_id', '$name', '$number', '$email', '$product_name', '$total_price', '$quantity', '$address', '$method', '$status')";
        $orders_result = mysqli_query($conn, $orders_query);
        if (!$orders_result) {
            throw new Exception('Query failed: ' . mysqli_error($conn));
        }

        $update_query = "UPDATE products SET quantity = quantity - '$quantity' WHERE id = '$product_id'";
        $update_result = mysqli_query($conn, $update_query);
        if (!$update_result) {
            throw new Exception('Query failed: ' . mysqli_error($conn));
        }

        mysqli_commit($conn);

        echo json_encode(['status' => 'success', 'message' => 'Orders placed successfully']);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        log_error($e->getMessage());
    }
}
?>
