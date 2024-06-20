<?php

include 'connection.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit();
}

$product_ids = array_map('intval', array_keys($_SESSION['cart']));
$ids = implode(',', $product_ids);

$query = "SELECT cart.quantity, products.name, products.price, products.image, products.id 
          FROM cart 
          INNER JOIN products ON cart.pid = products.id 
          WHERE cart.user_id = ? AND products.id IN ($ids)";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cart.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div>
    <div class="main-cart-page">
        <div class="cart-page">
            <div class="cart-container">
                <h1>Your Cart</h1>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): 
                            $first_image = explode(',', $product['image'])[0];
                            $available_stock = isset($product['quantity']) ? $product['quantity'] : 0;
                        ?>
                        <tr data-product-id="<?php echo $product['id']; ?>">
                            <td><img src="img/<?php echo htmlspecialchars($first_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                            <td><a href='product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>' class='product-link'><?php echo htmlspecialchars($product['name']); ?></a></td>
                            <!-- <td>
                                <input type="number" class="quantity-input" value="<?php echo htmlspecialchars($available_stock); ?>" min="1">
                            </td> -->
                            <td>Rs. <?php echo htmlspecialchars($product['price']); ?></td>
                            <td>
                                <button class="cart-btn remove-btn">Remove</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const productId = this.closest('tr').dataset.productId;
            const quantity = this.value;

            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'update',
                    product_id: productId,
                    quantity: quantity
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showModal('Quantity updated successfully');
                } else {
                    showModal('Error updating quantity');
                }
            });
        });
    });

    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.closest('tr').dataset.productId;

            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'remove',
                    product_id: productId
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('tr').remove();
                } else {
                    alert('Error removing product');
                }
            });
        });
    });

    function showModal(message) {
        alert(message); // Simple alert for demonstration, can be replaced with a modal.
    }
    </script>
</body>
</html>
