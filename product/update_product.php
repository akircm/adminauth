<?php
include('../database/db.php');

if (isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $type = $_POST['product_type'];
    $price = $_POST['product_price'];

    // Check if new image is uploaded
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "product_img/" . $image;

        // Delete old image
        $query = "SELECT pt_img FROM product_tbl WHERE pt_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product && file_exists("product_img/" . $product['pt_img'])) {
            unlink("product_img/" . $product['pt_img']);
        }

        // Move new image
        move_uploaded_file($image_tmp, $image_path);

        // Update with new image
        $updateQuery = "UPDATE product_tbl SET pt_name=?, pt_type=?, pt_price=?, pt_img=? WHERE pt_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$name, $type, $price, $image, $id]);
    } else {
        // Update without changing image
        $updateQuery = "UPDATE product_tbl SET pt_name=?, pt_type=?, pt_price=? WHERE pt_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$name, $type, $price, $id]);
    }

    // Redirect to products page
    header("Location: ../products_page.php");
    exit();
}
?>
