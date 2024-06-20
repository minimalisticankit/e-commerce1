 <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the raw POST data
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (!isset($data['product_id']) || !isset($data['quantity']) || !is_numeric($data['quantity'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit();
}

$product_id = (int)$data['product_id'];
$quantity = (int)$data['quantity'];

if ($quantity <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Quantity must be at least 1.']);
    exit();
}

// Check if product exists in the database
include 'connection.php';
$query = "SELECT quantity FROM products WHERE id = $product_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    exit();
}

$product = $result->fetch_assoc();
if ($quantity > $product['quantity']) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough stock.']);
    exit();
}

// Add product to cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = $quantity;
}

$iquery = "INSERT INTO cart(user_id, pid, quantity) VALUES ({$_SESSION['user_id']}, {$data['product_id']}, {$data['quantity']})";
$iresult = $conn->query($iquery);
if ($iresult) {
    echo json_encode(['status' => 'success']);
}



