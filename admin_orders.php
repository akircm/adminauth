<?php
session_start();
include('database/db.php'); // Ensure correct connection to DB

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Redirect non-admin users to home page
    header('Location: welcome.php');
    exit;
}

// Fetch all orders with user ID and product info
$query = "SELECT o.order_id, u.id AS user_id, u.username, p.pt_name, o.quantity, o.order_date, p.pt_type, p.pt_img
          FROM order_tbl o
          JOIN users u ON o.id = u.id
          JOIN product_tbl p ON o.pt_id = p.pt_id
          ORDER BY o.id ASC";

$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adminorders.css">
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <div>
        <a href="admin_page.php">Dashboard</a>
        <a href="admin_page.php?page=users">Users</a>
        <a href="admin_page.php?page=settings">Settings</a>
        <a href="Admin_page.php" style="color:#e53935">Home</a>
    </div>
    <a href="php/logout.php" class="btn-logout">Logout</a>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <a href="admin_page.php">Home</a>
    <a href="admin_page.php?page=profile">Profile</a>
    <a href="admin_page.php?page=manage_users">Manage Users</a>
    <a href="products_page.php">Products</a>
    <a href="charts.php">Analytics</a>
    <a href="admin_orders.php">View All Orders</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2 class="text-center mb-4">All Orders</h2>
        <div class="table-responsive">
            <table class="order-table table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Order Date</th>
                        <th>Product Type</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['username']); ?></td>
                                <td><?php echo htmlspecialchars($order['pt_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td><?php echo htmlspecialchars($order['pt_type']); ?></td>
                                <td>
                                    <div style="width: 70px; height: 70px; overflow: hidden;">
                                        <img src="product/product_img/<?php echo htmlspecialchars($order['pt_img']); ?>"
                                             alt="Product Image" class="img-fluid">
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>
