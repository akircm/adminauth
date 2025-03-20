<?php
session_start();
include('database/db.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header('Location: index.php');
    exit;
}

$query = "SELECT * FROM product_tbl";
$stmt = $conn->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Check if data is received
    if (!isset($_POST['pt_id']) || !isset($_POST['user_id']) || !isset($_POST['quantity'])) {
        die("Error: Missing data. Please check your form.");
    }

    $product_id = intval($_POST['pt_id']);
    $user_id = intval($_POST['user_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        die("Error: Invalid quantity. Must be at least 1.");
    }

    if (isset($_POST['add_to_cart'])) {
        // Add to Cart Logic
        $check_cart = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $check_cart->execute([$user_id, $product_id]);
        $cart_item = $check_cart->fetch();

        if ($cart_item) {
            $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            $update_cart->execute([$quantity, $cart_item['id']]);
        } else {
            $insert_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert_cart->execute([$user_id, $product_id, $quantity]);
        }

        // No redirect or alert, just process the request
    } 
    elseif (isset($_POST['order_now'])) {
        // Order Now Logic
        $order_date = date("Y-m-d");

        // Debugging: Print SQL query values
        error_log("ORDER: User ID: $user_id, Product ID: $product_id, Quantity: $quantity, Date: $order_date");

        $query = "INSERT INTO order_tbl (id, pt_id, quantity, order_date) VALUES (:id, :pt_id, :quantity, :order_date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':pt_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->execute();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/welcome.css">
    <style>
        .navbar { background-color: #00247E; }
        .navbar a, .navbar .username { color: white; }
        .product-card { border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s; }
        .product-card:hover { transform: scale(1.05); }
        .product-image { height: auto; object-fit: contain; max-height: 200px; width: 100%; }
        .btn.order { background-color: #00247E; color: white; }
        .btn.order:hover { background-color: #001B5E; }
        .btn.cart { background-color: #28a745; color: white; }
        .btn.cart:hover { background-color: #1e7e34; }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">E-Commerce</a>
            <div class="ms-auto">
                <span class="me-3 text-white">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
                <a href="cart.php" class="btn btn-warning">Cart</a>
                <a href="order.php" class="btn btn-light">Orders</a>
                <a href="php/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <?php foreach ($products as $row): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="product/product_img/<?php echo htmlspecialchars($row['pt_img']); ?>" class="card-img-top product-image" alt="Product Image">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['pt_name']); ?></h5>
                            <p class="card-text">Type: <?php echo htmlspecialchars($row['pt_type']); ?></p>
                            <p class="card-text text-primary fw-bold">Price: $<?php echo htmlspecialchars($row['pt_price']); ?></p>

                            <form action="" method="POST" class="d-grid">
                                <label class="form-label">Quantity</label>
                                <input name="quantity" required type="number" min="1" class="form-control mb-2">
                                
                                <input type="hidden" name="pt_id" value="<?php echo $row['pt_id']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                                <button type="submit" name="add_to_cart" class="btn cart">Add to Cart</button>
                                <button type="submit" name="order_now" class="btn order mt-2">Order Now</button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
