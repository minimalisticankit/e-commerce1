<?php

include 'connection.php';


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit();
}

$product_ids = array_map('intval', array_keys($_SESSION['cart']));
$ids = implode(',', $product_ids);

$query = "SELECT cart.quantity, products.name, products.price, products.image, products.id, products.quantity as available_quantity
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
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): 
                            $first_image = explode(',', $product['image'])[0];
                        ?>
                        <tr data-product-id="<?php echo $product['id']; ?>">
                            <td><img src="img/<?php echo htmlspecialchars($first_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                            <td>
                                <a href='product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>' class='product-link'>
                                    <?php echo htmlspecialchars($product['name']); ?> (<?php echo $product['available_quantity']; ?> available)
                                </a>
                            </td>
                            <td>
                                <input type="number" class="quantity-input" value="<?php echo htmlspecialchars($product['quantity']); ?>" min="1" max="<?php echo $product['available_quantity']; ?>">
                            </td>
                            <td>Rs. <?php echo htmlspecialchars($product['price']); ?></td>
                            <td>
                                <button class="cart-btn remove-btn">Remove</button>
                                <button class="cart-btn order-now-btn" onclick="showOrderForm(<?php echo $product['id']; ?>, <?php echo $product['price']; ?>)">Order Now</button>
                            </td>
                        </tr>
                        
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="orderFormModal" style="display:none;">
        <div class="modal-content">
            <form id="orderForm" onsubmit="submitOrder(event)">
                <h2>Order Form</h2>
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="productName" readonly>
                <label for="total_price">Price:</label>
                <input type="text" name="total_price" id="ordersPrice" readonly>
                <label for="quantity">Quantity:</label>
                <input type="text" name="quantity" id="ordersQuantity" value="1" readonly>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="number">Phone Number:</label>
                <input type="text" name="number" required>
                <label for="email">Email:</label>
                <input type="email" name="email">
                <label for="address">Address:</label>
                <input type="text" name="address" required>
                <label for="method">Payment Method:</label>
                <select name="method" required>
                    <option value="credit_card">Credit Card</option>
                </select>
                <button type="submit" class="submit-btn">Place Order</button>
                <button type="button" class="cancel-btn" onclick="closeOrderForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const productId = this.closest('tr').dataset.productId;
            let quantity = parseInt(this.value);
            const maxQuantity = parseInt(this.max);

            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                this.value = maxQuantity;
            }

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

    function showOrderForm(productId, price) {
        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
        const quantity = row.querySelector('.quantity-input').value;
        const productName = row.querySelector('.product-link').innerText;

        document.getElementById('productName').value = productName;
        document.getElementById('ordersPrice').value = price * quantity;
        document.getElementById('ordersQuantity').value = quantity;

        document.getElementById('orderFormModal').style.display = 'block';
    }

    function closeOrderForm() {
        document.getElementById('orderFormModal').style.display = 'none';
    }

    function submitOrder(event) {
        event.preventDefault();

        const formData = new FormData(document.getElementById('orderForm'));
        formData.append('product_id', document.querySelector(`tr[data-product-id]`).dataset.productId);
        formData.append('quantity', document.getElementById('ordersQuantity').value);

        fetch('place_order.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Order Placed Successfully');
                location.reload(); // Refresh the page after placing the order
            } else {
                alert('Failed to place order: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('You must login first');
        });
    }
    </script>
</body>
</html>
