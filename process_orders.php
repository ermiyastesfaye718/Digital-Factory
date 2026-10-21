<?php
include 'config.php';

if (isset($_POST['post_order'])) {
    $cust = $_POST['customer'];
    $p_id = $_POST['product_id'];
    $qty = $_POST['qty'];

    try {
        // መረጃውን ወደ orders table መላክ (ሁኔታው በዲፎልት 'Pending' ይሆናል)
        $sql = "INSERT INTO orders (customer_name, product_id, quantity, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        
        if($stmt->execute([$cust, $p_id, $qty])) {
            header("Location: process_orders.php?msg=OrderSent");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>