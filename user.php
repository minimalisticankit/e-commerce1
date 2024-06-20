<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'connection.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logout process
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Delete an orders and update product quantity
if (isset($_GET['delete_orders'])) {
    $orders_id = $_GET['delete_orders'];

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
    
    header("Location: user.php");
    exit();
}

// Fetch pending orders
$pendingQuery = "
    SELECT id, product_name, quantity, total_price
    FROM `orders`
    WHERE user_id = ? AND payment_status = 'pending'";
$pendingStmt = $conn->prepare($pendingQuery);
if (!$pendingStmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
$pendingStmt->bind_param('i', $user_id);
$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result();

$pendingOrders = [];
$totalPendingQuantity = 0;
$totalPendingPrice = 0;
while ($row = $pendingResult->fetch_assoc()) {
    $pendingOrders[] = $row;
    $totalPendingQuantity += $row['quantity'];
    $totalPendingPrice += $row['total_price'];
}

// Fetch complete orders
$completeQuery = "
    SELECT id, product_name, quantity, total_price
    FROM `orders`
    WHERE user_id = ? AND payment_status = 'complete'";
$completeStmt = $conn->prepare($completeQuery);
if (!$completeStmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
$completeStmt->bind_param('i', $user_id);
$completeStmt->execute();
$completeResult = $completeStmt->get_result();

$completeOrders = [];
$totalCompleteQuantity = 0;
$totalCompletePrice = 0;
while ($row = $completeResult->fetch_assoc()) {
    $completeOrders[] = $row;
    $totalCompleteQuantity += $row['quantity'];
    $totalCompletePrice += $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="user.css">
</head>

<body id="top">

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Main Container -->
    <div class="main-container">

        <!-- Orders Section -->
        <div class="orders-container column">
            <h1>My Orders</h1>

            <h2>Pending Orders</h2>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                       
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingOrders)): ?>
                        <tr><td colspan="5">No pending orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pendingOrders as $orders): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($orders['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($orders['quantity']); ?></td>
                            <td>Rs. <?php echo htmlspecialchars($orders['total_price']); ?></td>
                           
                            <td>
    <a href="user.php?delete_orders=<?php echo htmlspecialchars($orders['id']); ?>" 
       style="color: red;" 
       onclick="return confirm('Are you sure you want to delete this orders?')">Cancel</a>
</td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="orders-summary">
                <p>Total Quantity: <?php echo htmlspecialchars($totalPendingQuantity); ?></p>
                <p>Total Price: Rs. <?php echo htmlspecialchars($totalPendingPrice); ?></p>
            </div>

            <h2>Complete Orders</h2>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($completeOrders)): ?>
                        <tr><td colspan="4">No complete orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($completeOrders as $orders): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($orders['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($orders['quantity']); ?></td>
                            <td>Rs. <?php echo htmlspecialchars($orders['total_price']); ?></td>
                           
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="orders-summary">
                <p>Total Quantity: <?php echo htmlspecialchars($totalCompleteQuantity); ?></p>
                <p>Total Price: Rs. <?php echo htmlspecialchars($totalCompletePrice); ?></p>
            </div>
        </div>

        <!-- Right Column: User Profile and Side Cart -->
        <div class="right-column column">
            <!-- User Profile -->
            <div class="user-profile-container">
                <h1>User Profile</h1>
                <div class="user-details">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <form method="POST" action="user.php">
                        <button type="submit" name="logout" class="logout-btn">Log Out</button>
                    </form>
                </div>
            </div>
    
            <!-- Side Cart -->
            <?php include 'side_cart.php'; ?>
        </div>
        
    </div>

</body>

</html>
