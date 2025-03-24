<?php
session_start();
include('database/db.php');

// Check if user is logged in
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_id = $user['id'];
} else {
    // Redirect to login if session is not set
    header('Location: index.php');
    exit;
}

// Fetch orders for the current user
$query = "SELECT o.order_id, p.pt_name, o.quantity, o.order_date, p.pt_img, p.pt_type
          FROM order_tbl o
          JOIN product_tbl p ON o.pt_id = p.pt_id
          WHERE o.id = :user_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/order.css">

    
</head>
<body>
<div class="navbar">
    <a href="welcome.php">Home</a>
    <span class="username">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
    <a href="php/logout.php" class="btn btn-danger">Logout</a>
</div>

<div class="container mt-5">
    <h2 style="color:rgbfont-size: 30px; font-weight: 700; text-align: center;">My Orders</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover order-table">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Product Type</th>
                    <th>Product Image</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['pt_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['pt_type']); ?></td>
                            <td>
                                <img src="product/product_img/<?php echo htmlspecialchars($order['pt_img']); ?>" alt="Product Image" class="img-fluid">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
