<?php
session_start();
include('../database/db.php');
 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['pt_id']) && !empty($_POST['user_id'])) {
       
        $pt_id = $_POST['pt_id'];
        $id = $_POST['user_id'];
        $quantity = $_POST['quantity'];  // Ensure it's an integer
        $order_date = date("Y-m-d"); // Use proper date format for SQL
 
       
        // Corrected SQL query with proper syntax
        $query = "INSERT INTO order_tbl (id, pt_id, quantity, order_date)
                  VALUES (:id, :pt_id, :quantity, :order_date)";
 
        // Prepare the statement
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':pt_id', $pt_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':order_date', $order_date);
 
        // Execute the query and check for success
        if ($stmt->execute()) {
            echo "<script>alert('Order placed successfully!'); window.location.href='welcome.php';</script>";
        } else {
            echo "<script>alert('Order failed. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid request!'); window.history.back();</script>";
    }
    header("Location: ../welcome.php");
}
 
?>