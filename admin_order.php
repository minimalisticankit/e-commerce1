<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_name'])) {
    header('location: index.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
    exit();
}

$messages = '';
$error_message = '';

// Handle payment status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['payment_status'];

    $update_sql = "UPDATE `order` SET payment_status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $new_status, $order_id);

    if ($stmt->execute()) {
        $messages = "Order status updated successfully.";
    } else {
        $error_message = "Failed to update order status.";
    }

    $stmt->close();
}

// Fetch orders from the database
$orders = [];
$sql = "SELECT o.id, o.name AS customer_name, s.name AS storage_name, o.quantity, o.order_date, o.payment_status
        FROM `order` o
        JOIN storages s ON o.storage_id = s.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    $error_message = "No orders found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Management</title>
    <link rel="stylesheet" href="../css/admin_order.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<div class="container">
    <h1>Order Management</h1>
    <?php if ($messages): ?>
        <p class="success"><?php echo $messages; ?></p>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Storage</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['storage_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                        <td>
                            <form action="" method="post" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="payment_status">
                                    <option value="pending" <?php if ($order['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="complete" <?php if ($order['payment_status'] == 'complete') echo 'selected'; ?>>Complete</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
