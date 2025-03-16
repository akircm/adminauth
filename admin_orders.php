<?php
session_start();
include('database/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Redirect non-admin users to home page
    header('Location: welcome.php');
    exit;
}

// Fetch all orders with user ID
$query = "SELECT o.order_id, u.id AS user_id, u.username, p.pt_name, o.quantity, o.order_date, p.pt_type, p.pt_img
          FROM order_tbl o
          JOIN users u ON o.id = u.id
          JOIN product_tbl p ON o.pt_id = p.pt_id
          ORDER BY o.id ASC";

$stmt = $conn->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/order.css">
</head>
<body>

<div class="navbar">
    <a href="admin_page.php">Dashboard</a>
    <a href="php/logout.php">Logout</a>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">All Orders</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover order-table">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th> <!-- Added User ID column -->
                    <th>Username</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Product Type</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td> <!-- Displaying User ID -->
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td><?php echo htmlspecialchars($order['pt_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['pt_type']); ?></td>
                        <td><img src="product/product_img/<?php echo htmlspecialchars($order['pt_img']); ?>" alt="Product Image" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
