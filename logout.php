<?php
session_start();

// ሁሉንም ሴሽኖች ባዶ ማድረግ
$_SESSION = array();

// የሴሽን ኩኪዎችን ማጥፋት
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ሴሽኑን ሙሉ በሙሉ መዝጋት/ማጥፋት
session_destroy();

// ብራውዘር ከეሽ እንዳይይዝ በማድረግ ወደ ሎጊን ገጽ መመለስ
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Location: index.php");
exit();
?>