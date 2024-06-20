<?php
session_start();

if (!isset($_POST['product_id']) || !isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit();
}

$product_id = intval($_POST['product_id']);
$action = $_POST['action'];

if (!isset($_SESSION['cart'][$product_id])) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found in cart.']);
    exit();
}

if ($action === 'update') {
    if (!isset($_POST['change'])) {
        echo json_encode(['status' => 'error', 'message' => 'Change value not provided.']);
        exit();
    }
    $change = intval($_POST['change']);
    $_SESSION['cart'][$product_id] += $change;

    if ($_SESSION['cart'][$product_id] <= 0) {
        unset($_SESSION['cart'][$product_id]);
    }
} elseif ($action === 'remove') {
    unset($_SESSION['cart'][$product_id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    exit();
}

echo json_encode(['status' => 'success']);
exit();
?>
