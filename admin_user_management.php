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

// Add a new user to the database
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];

    $sql = "INSERT INTO user (name, email, password, user_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $user_type);
    $stmt->execute();
    header("Location: admin_user_management.php");
    exit();
}



// Delete a user from the database
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $sql = "DELETE FROM user WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_user_management.php");
    exit();
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
        <h2>Add User</h2>
        <form method="POST" action="admin_user_management.php" class="add-user-form">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add_user">Add User</button>
        </form>
        <form method="POST" action="admin_user_management.php" class="search-form">
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
                        <a href="admin_user_management.php?delete_user=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                      
                        
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
