<?php
include 'config.php';

// Check if the form was submitted / ፎርሙ መላኩን ማረጋገጥ
if (isset($_POST['dispatch'])) {
    $order_id = $_POST['order_id'];
    $vehicle_id = $_POST['vehicle_id'];

    try {
        // Start a Transaction / ስራውን በአንድ ላይ ማያያዝ (Transaction)
        // ይህ የሆነው አንዱ ተሳክቶ ሌላኛው እንዳይቀር ነው
        $conn->beginTransaction();

        // 1. Update Order Status and assign vehicle
        // የትዕዛዙን ሁኔታ መቀየር እና መኪናውን መመደብ
        $sql1 = "UPDATE orders SET status = 'Dispatched', assigned_vehicle_id = ? WHERE id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$vehicle_id, $order_id]);

        // 2. Update Vehicle Status to 'On Delivery'
        // የመኪናውን ሁኔታ 'ስራ ላይ' (On Delivery) ማድረግ
        $sql2 = "UPDATE vehicles SET status = 'On Delivery' WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$vehicle_id]);

        // If both successful, save the changes / ሁለቱም ከተሳኩ መረጃውን አጽና
        $conn->commit();

        // Redirect back to logistics dashboard / ወደ ሎጅስቲክስ ገጽ ይመለሳል
        header("Location: logistics_dashboard.php?msg=DispatchedSuccessfully");
        exit();

    } catch (Exception $e) {
        // If something goes wrong, undo everything / ስህተት ካለ ሁሉንም ወደነበረበት መልስ
        $conn->rollBack();
        echo "Error in dispatching: " . $e->getMessage();
    }
} else {
    // If accessed directly, go back / በቀጥታ ገጹን ለመክፈት ከሞከሩ ይመለሱ
    header("Location: logistics_dashboard.php");
    exit();
}
?>