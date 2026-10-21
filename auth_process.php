<?php
// የደህንነት ማስተካከያ፡ ከሁሉም በላይ ከეሽ እንዳይይዝ ማድረጊያ ሄደሮች
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_start();
include 'config.php'; 

if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = $_POST['password']; // ተጠቃሚው ያስገባው 123456

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $row = $stmt->fetch();

    if ($row) {
        // በቀጥታ ማነጻጸር (ያለ password_verify)
        if ($pass === $row['password']) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role']      = trim($row['role']); 

            // ወደየራሳቸው ዳሽቦርድ መምራት
            header("Location: " . $_SESSION['role'] . "_dashboard.php");
            exit();
        } else {
            header("Location: index.php?error=password_mismatch");
            exit();
        }
    } else {
        header("Location: index.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>