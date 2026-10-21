<?php
include 'config.php';

// 1. የምርት መመዝገቢያ (ከ Inventory Dashboard የሚመጣ)
if (isset($_POST['save_product'])) {
    $name = trim($_POST['p_name']);
    $unit = $_POST['p_unit'];
    // አዲስ የተጨመረ፡ ብዛትን መቀበል
    $quantity = isset($_POST['p_quantity']) ? (int)$_POST['p_quantity'] : 0;

    // ብዛቱ ከዜሮ በታች እንዳይሆን እዚህም ጋር እናረጋግጣለን
    if ($quantity < 0) { $quantity = 0; }

    if (!empty($name) && !empty($unit)) {
        try {
            // SQL ጥያቄው ብዛትን (quantity) እንዲጨምር ተስተካክሏል
            $sql = "INSERT INTO products (product_name, unit, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if($stmt->execute([$name, $unit, $quantity])) {
                header("Location: inventory_dashboard.php?status=success");
                exit();
            }
        } catch (PDOException $e) {
            echo "ስህተት አጋጥሟል: " . $e->getMessage();
        }
    } else {
        header("Location: inventory_dashboard.php?status=error");
        exit();
    }
}

// 2. የተሽከርካሪ መመዝገቢያ (ከ Logistics Dashboard የሚመጣ)
if (isset($_POST['save_vehicle'])) {
    $plate = trim($_POST['plate_no']);
    $driver = trim($_POST['driver']);

    if (!empty($plate) && !empty($driver)) {
        try {
            $sql = "INSERT INTO vehicles (plate_number, driver_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            
            if($stmt->execute([$plate, $driver])) {
                header("Location: logistics_dashboard.php?status=success");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: logistics_dashboard.php?status=duplicate");
            exit();
        }
    } else {
        header("Location: logistics_dashboard.php?status=error");
        exit();
    }
}
?>