<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['action'])) {
    if ($input['action'] == 'update') {
        $product_id = intval($input['product_id']);
        $quantity = intval($input['quantity']);

        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND pid = ?");
        $stmt->bind_param('iii', $quantity, $_SESSION['user_id'], $product_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } elseif ($input['action'] == 'remove') {
        $product_id = intval($input['product_id']);

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND pid = ?");
        $stmt->bind_param('ii', $_SESSION['user_id'], $product_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}
?>
