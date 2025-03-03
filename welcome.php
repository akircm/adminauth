<?php
session_start();
include('database/db.php');
 
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
 
// Check if the 'user' session variable is set
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    // If the user session variable is not set, redirect to the login page
    header('Location: index.php');
    exit;
}

$query = "SELECT * FROM product_tbl";
$stmt = $conn ->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    .navbar {
      background-color: #00247E;
    }
    .navbar a, .navbar .username {
      color: white;
    }
    .product-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }
    .product-card:hover {
      transform: scale(1.05);
    }
    .product-image {
      height: 200px;
      object-fit: cover;
    }
    .btn.order {
      background-color: #00247E;
      color: white;
    }
    .btn.order:hover {
      background-color: #001B5E;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">E-Commerce</a>
      <div class="ms-auto">
        <span class="me-3 text-white">Welcome, <?php echo $user['username']; ?>!</span>
        <a href="order.php" class="btn btn-light">Orders</a>
        <a href="php/logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </nav>
 
  <div class="container mt-4">
    <div class="row">
      <?php foreach($products as $row): ?>
        <div class="col-md-4 mb-4">
          <div class="card product-card">
            <img style="" src="product/product_img/<?php echo ($row['pt_img']); ?>" class="card-img-top product-image" alt="Product Image">
            <div class="card-body text-center">
              <h5 class="card-title"> <?php echo ($row['pt_name']); ?> </h5>
              <p class="card-text">Type: <?php echo ($row['pt_type']); ?></p>
              <p class="card-text text-primary fw-bold">Price: $<?php echo ($row['pt_price']); ?></p>

              <form action="order/create_order.php" method="POST" class="d-grid">
                <label class="form-label">Quantity</label>

                <input name="quantity" required type="number" min="1" class="form-control mb-2">

                <input type="hidden" name="pt_id" value="<?php echo ($row['pt_id']); ?>">

                <input type="hidden" name="user_id" value="<?php echo ($user['id']); ?>">

                <button type="submit" class="btn order">Order Now</button>

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
 