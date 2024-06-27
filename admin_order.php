<?php
session_start();
include 'connection.php';

// Fetch all users from the database
function fetchUsers($conn) {
    $sql = "SELECT user.id, user.name, user.email, user.user_type, COUNT(orders.id) AS pending_orders_count 
            FROM user 
            LEFT JOIN orders ON user.id = orders.user_id AND orders.payment_status = 'pending' 
            GROUP BY user.id, user.name, user.email, user.user_type";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Search for users
$search_results = [];
if (isset($_POST['search_user'])) {
    $search_query = '%' . $_POST['search_query'] . '%';
    $sql = "SELECT user.id, user.name, user.email, user.user_type, COUNT(orders.id) AS pending_orders_count 
            FROM user 
            LEFT JOIN orders ON user.id = orders.user_id AND orders.payment_status = 'pending' 
            WHERE user.name LIKE ? OR user.email LIKE ? 
            GROUP BY user.id, user.name, user.email, user.user_type";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    $search_results = $result->fetch_all(MYSQLI_ASSOC);
}

$users = fetchUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="admin_user_management.css">
</head>
<body>
<?php include 'admin_header.php'; ?>
    <div class="user-management-container">
        <h1>User Management</h1>
        <form method="POST" action="admin_order.php" class="search-form">
            <input type="text" name="search_query" placeholder="Search users">
            <button type="submit" name="search_user">Search</button>
        </form>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Pending orders</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($search_results ?: $users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                    <td><?php echo htmlspecialchars($user['pending_orders_count']); ?></td>
                    <td>
                        <?php if ($user['user_type'] !== 'admin'): ?>
                            <a href="admin_manage_orders.php?user_id=<?php echo htmlspecialchars($user['id']); ?>">Manage Orders</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
