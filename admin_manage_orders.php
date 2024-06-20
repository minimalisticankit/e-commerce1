<?php
session_start();
include 'connection.php';

// Update orders payment status
if (isset($_POST['update_status'])) {
    $orders_id = $_POST['orders_id'];
    $payment_status = $_POST['payment_status'];
    $user_id = $_POST['user_id'];

    $sql = "UPDATE `orders` SET payment_status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $payment_status, $orders_id);
    $stmt->execute();
    header("Location: admin_manage_orders.php?user_id=" . $user_id);
    exit();
}

// Delete an orders
if (isset($_GET['delete_orders'])) {
    $orders_id = $_GET['delete_orders'];
    $user_id = $_GET['user_id'];
    
    // Fetch product name and quantity from the orders
    $sql = "SELECT product_name, quantity FROM `orders` WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orders_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_assoc();

    if ($orders) {
        $product_name = $orders['product_name'];
        $quantity = $orders['quantity'];
        
        // Update the product quantity back to the inventory
        $sql = "UPDATE `products` SET quantity = quantity + ? WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quantity, $product_name);
        $stmt->execute();
        
        // Delete the orders
        $sql = "DELETE FROM `orders` WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orders_id);
        $stmt->execute();
    }
    
    header("Location: admin_manage_orders.php?user_id=" . $user_id);
    exit();
}

// Fetch orders for a specific user
function fetchOrders($conn, $user_id) {
    $sql = "SELECT * FROM `orders` WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$user_id = $_GET['user_id'];
$orders = fetchOrders($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" type="text/css" href="admin_manage_orders.css">
    <script>
        function updateStatus(ordersId, userId, status) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'admin_manage_orders.php?user_id=' + userId;

            var ordersIdInput = document.createElement('input');
            ordersIdInput.type = 'hidden';
            ordersIdInput.name = 'orders_id';
            ordersIdInput.value = ordersId;
            form.appendChild(ordersIdInput);

            var userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = userId;
            form.appendChild(userIdInput);

            var statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'payment_status';
            statusInput.value = status;
            form.appendChild(statusInput);

            var updateStatusInput = document.createElement('input');
            updateStatusInput.type = 'hidden';
            updateStatusInput.name = 'update_status';
            updateStatusInput.value = true;
            form.appendChild(updateStatusInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
<?php include 'admin_header.php'; ?>
    <div class="orders-management-container">
        <h1>Manage Orders for User ID: <?php echo htmlspecialchars($user_id); ?></h1>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Orders ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $orders): ?>
                <tr>
                    <td><?php echo htmlspecialchars($orders['id']); ?></td>
                    <td><?php echo htmlspecialchars($orders['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($orders['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($orders['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($orders['email']); ?></td>
                    <td><?php echo htmlspecialchars($orders['number']); ?></td>
                    <td><?php echo htmlspecialchars($orders['name']); ?></td>
                    <td><?php echo htmlspecialchars($orders['address']); ?></td>
                    <td>
                        <select name="payment_status" onchange="updateStatus(<?php echo $orders['id']; ?>, <?php echo htmlspecialchars($user_id); ?>, this.value)">
                            <option value="pending" <?php if ($orders['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="complete" <?php if ($orders['payment_status'] == 'complete') echo 'selected'; ?>>Complete</option>
                        </select>
                    </td>
                    <td>
                        <a href="admin_manage_orders.php?delete_orders=<?php echo htmlspecialchars($orders['id']); ?>&user_id=<?php echo htmlspecialchars($user_id); ?>" onclick="return confirm('Are you sure?')">Cancel Orders</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
