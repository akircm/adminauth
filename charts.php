<?php
session_start();
include ('database/db.php');

// Product Categories Data
$stmt1 = $conn->prepare("SELECT pt_type, COUNT(*) AS count FROM product_tbl GROUP BY pt_type");
$stmt1->execute();
$product_data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// User Types Data
$stmt2 = $conn->prepare("SELECT role, COUNT(*) AS count FROM users GROUP BY role");
$stmt2->execute();
$user_data = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Orders per User Data
$stmt3 = $conn->prepare("SELECT id, COUNT(*) AS count FROM order_tbl GROUP BY id");
$stmt3->execute();
$order_data = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Total Orders by Product Name
$stmt4 = $conn->prepare("SELECT pt_name, COUNT(order_tbl.pt_id) AS count FROM order_tbl INNER JOIN product_tbl ON order_tbl.pt_id = product_tbl.pt_id GROUP BY order_tbl.pt_id");
$stmt4->execute();
$product_orders_data = $stmt4->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Charts</title>
    <link rel="stylesheet" href="css/productpage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawCharts);
      
      function drawCharts() {
        // Draw Product Categories Chart
        var data1 = new google.visualization.DataTable();
        data1.addColumn('string', 'Product Type');
        data1.addColumn('number', 'Count');
        data1.addRows([
          <?php foreach ($product_data as $row) {
            echo "['" . $row['pt_type'] . "', " . $row['count'] . "],";
          } ?>
        ]);
        var options1 = {
          title: 'Product Categories',
          is3D: true,
          chartArea: { width: '80%', height: '70%' },
        };
        var chart1 = new google.visualization.PieChart(document.getElementById('product_chart'));
        chart1.draw(data1, options1);
        
        // Draw User Types Chart
        var data2 = new google.visualization.DataTable();
        data2.addColumn('string', 'User Type');
        data2.addColumn('number', 'Count');
        data2.addRows([
          <?php foreach ($user_data as $row) {
            echo "['" . $row['role'] . "', " . $row['count'] . "],";
          } ?>
        ]);
        var options2 = {
          title: 'User Types',
          is3D: true,
          chartArea: { width: '80%', height: '70%' },
        };
        var chart2 = new google.visualization.PieChart(document.getElementById('user_chart'));
        chart2.draw(data2, options2);
        
        // Draw Orders per User Chart
        var data3 = new google.visualization.DataTable();
        data3.addColumn('string', 'User ID');
        data3.addColumn('number', 'Orders Count');
        data3.addRows([
          <?php foreach ($order_data as $row) {
            echo "['User " . $row['id'] . "', " . $row['count'] . "],";
          } ?>
        ]);
        var options3 = {
          title: 'Orders per User',
          is3D: true,
          chartArea: { width: '80%', height: '70%' },
        };
        var chart3 = new google.visualization.PieChart(document.getElementById('order_chart'));
        chart3.draw(data3, options3);

        // Draw Total Orders by Product Name Chart
        var data4 = new google.visualization.DataTable();
        data4.addColumn('string', 'Product Name');
        data4.addColumn('number', 'Total Orders');
        data4.addRows([
          <?php foreach ($product_orders_data as $row) {
            echo "['" . $row['pt_name'] . "', " . $row['count'] . "],";
          } ?>
        ]);
        var options4 = {
          title: 'Total Orders by Product Name',
          is3D: true,
          chartArea: { width: '80%', height: '70%' },
        };
        var chart4 = new google.visualization.PieChart(document.getElementById('product_orders_chart'));
        chart4.draw(data4, options4);
      }

      // Redraw charts on window resize to ensure responsiveness
      window.addEventListener('resize', drawCharts);
    </script>
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <div class="nav-left">
      <a href="#">Dashboard</a>
      <a href="#">Users</a>
      <a href="#">Settings</a>
      <a href="Admin_page.php" style="color:#e53935">Home</a>
    </div>
    <div class="nav-right">
      <a href="php/logout.php" class="btn">Logout</a>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="#">Home</a>
    <a href="#">Profile</a>
    <a href="#">Manage User</a>
    <a href="products_page.php">Products</a>
    <a href="charts.php">Analytics</a>
    <a href="admin_orders.php">View All Orders</a>
    <a href="charts.php">View Charts</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2>Business Charts</h2>

    <!-- First Row - 3 Charts -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="chart-wrapper">
            <div id="product_chart" class="chart"></div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="chart-wrapper">
            <div id="user_chart" class="chart"></div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="chart-wrapper">
            <div id="order_chart" class="chart"></div>
          </div>
        </div>
      </div>

      <!-- Second Row - Single Chart -->
      <div class="row">
        <div class="col-md-4 col-sm-6 mb-4">
          <div class="chart-wrapper">
            <div id="product_orders_chart" class="chart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
