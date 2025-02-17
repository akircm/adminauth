<?php
include('../database/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the image filename
    $query = "SELECT pt_img FROM product_tbl WHERE pt_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $image_path = "product_img/" . $product['pt_img'];
        
        // Delete image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete product from database
        $deleteQuery = "DELETE FROM product_tbl WHERE pt_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([$id]);
    }

    // Redirect to products page
    header("Location: ../products_page.php");
    exit();
}
?>

