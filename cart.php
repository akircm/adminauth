<?php
session_start();
include('database/db.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Handle Quantity Update & Remove Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $cart_id = ($_POST['cart_id']);
    $quantity = ($_POST['quantity']);

    if ($quantity > 0) {
        $update_cart = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $update_cart->execute([$quantity, $cart_id, $user_id]);
    } else {
        $delete_cart = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $delete_cart->execute([$cart_id, $user_id]);
    }

    header("Location: cart.php");
    exit();
}

// Handle Checkout of Selected Items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_selected'])) {
    if (!empty($_POST['selected_products'])) {
        $insert_order = $conn->prepare("INSERT INTO order_tbl (id, pt_id, quantity, order_date) VALUES (?, ?, ?, CURDATE())");

        foreach ($_POST['selected_products'] as $cart_id) {
            // Get product and quantity from cart
            $cart_query = $conn->prepare("SELECT product_id, quantity FROM cart WHERE id = ? AND user_id = ?");
            $cart_query->execute([$cart_id, $user_id]);
            $item = $cart_query->fetch(PDO::FETCH_ASSOC);

            if (!empty($item)) {
                // Insert order with the current date
                $insert_order->execute([$user_id, $item['product_id'], $item['quantity']]);

                // Remove checked-out item from cart
                $remove_cart = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
                $remove_cart->execute([$cart_id, $user_id]);
            }
        }

        header("Location: order.php"); // Redirect to orders page
        exit();
    } else {
        echo "<p class='text-center text-danger'>No items selected for checkout!</p>";
    }
}

// Fetch Cart Items
$query = "
    SELECT cart.id, product_tbl.pt_name, product_tbl.pt_price, cart.quantity, product_tbl.pt_img 
    FROM cart 
    JOIN product_tbl ON cart.product_id = product_tbl.pt_id 
    WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { background-color: #00247E; }
        .navbar a, .navbar .username { color: white; }
        .cart-table { width: 100%; }
        .cart-table th, .cart-table td { padding: 10px; text-align: center; }
        .cart-table img { width: 50px; height: 50px; object-fit: cover; }
        .btn.update { background-color: #28a745; color: white; }
        .btn.update:hover { background-color: #1e7e34; }
        .btn.remove { background-color: #dc3545; color: white; }
        .btn.remove:hover { background-color: #c82333; }
        .btn.checkout { background-color: #007bff; color: white; }
        .btn.checkout:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="welcome.php">E-Commerce</a>
            <div class="ms-auto">
                <span class="me-3 text-white">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
                <a href="order.php" class="btn btn-light">Orders</a>
                <a href="php/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">My Cart</h2>

        <?php if (count($cart_items) > 0): ?>
            <form method="POST" id="cartForm">
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_price = 0; ?>
                        <?php foreach ($cart_items as $item): ?>
                            <?php $subtotal = $item['pt_price'] * $item['quantity']; ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_products[]" value="<?php echo $item['id']; ?>" class="product-checkbox">
                                </td>
                                <td><img src="product/product_img/<?php echo htmlspecialchars($item['pt_img']); ?>" alt="Product"></td>
                                <td><?php echo htmlspecialchars($item['pt_name']); ?></td>
                                <td>₱<?php echo number_format($item['pt_price'], 2); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0">
                                        <button type="submit" name="update_cart" class="btn btn-sm update">Update</button>
                                    </form>
                                </td>
                                <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="update_cart" value="0" class="btn btn-sm remove">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Show Total Only If Any Product is Selected -->
                        <tr id="totalRow" style="display: none;">
                            <td colspan="5"><strong>Total:</strong></td>
                            <td colspan="2"><strong id="totalAmount">₱0.00</strong></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Checkout Button -->
                <div class="text-center">
                    <button type="submit" name="checkout_selected" class="btn checkout">Checkout Selected</button>
                </div>
            </form>

        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="welcome.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>

    <!-- JavaScript to Handle Total Calculation -->
    <script>
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const totalRow = document.getElementById('totalRow');
        const totalAmount = document.getElementById('totalAmount');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        function calculateTotal() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest('tr');
                    const price = parseFloat(row.querySelector('td:nth-child(6)').innerText.replace('₱', '').replace(',', ''));
                    total += price;
                }
            });

            if (total > 0) {
                totalRow.style.display = 'table-row';
                totalAmount.innerText = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                totalRow.style.display = 'none';
                totalAmount.innerText = '₱0.00';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
