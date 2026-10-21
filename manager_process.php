<?php
include 'config.php';
session_start();

// ሰራተኛ ለመመዝገብ
if (isset($_POST['register_staff'])) {
    $fullname = $_POST['full_name'];
    $user     = $_POST['username'];
    $raw_pass = $_POST['password'];
    $role     = $_POST['role'];

    // ፓስወርዱን Hash ማድረግ
    $secure_pass = password_hash($raw_pass, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fullname, $user, $secure_pass, $role]);
        header("Location: manager_dashboard.php?msg=success");
    } catch (PDOException $e) {
        header("Location: manager_dashboard.php?msg=error");
    }
}
?>