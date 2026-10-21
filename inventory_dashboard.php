<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'check_auth.php'; 
include 'config.php'; 

$userName = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "ተጠቃሚ";
?>
<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard - Smart Factory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --dark-blue: #0f172a; --accent-gold: #f59e0b; }
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .navbar { background: var(--dark-blue); border-bottom: 4px solid var(--accent-gold); }
        .brand-text { font-size: 1.5rem; letter-spacing: 1px; }
        .user-profile { text-align: left; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 20px; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); transition: 0.3s; margin-bottom: 20px; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1); }
        .form-control, .form-select { border-radius: 12px; padding: 12px; border: 2px solid #e2e8f0; background-color: #f1f5f9; }
        .btn-primary { background: #3b82f6; border: none; border-radius: 12px; padding: 12px; font-weight: 600; }
        .table-container { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg py-3 shadow">
    <div class="container px-4">
        <a class="navbar-brand text-white fw-bold brand-text" href="#"><i class="fas fa-warehouse me-2 text-warning"></i> SMART FACTORY</a>
        <div class="ms-auto d-flex align-items-center">
            <div class="user-profile me-3 d-none d-md-block">
                <small class="text-white-50 d-block" style="font-size: 0.75rem;">እንኳን ደህና መጡ</small>
                <span class="text-white fw-bold"> <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($userName) ?></span>
            </div>
            <a href="logout.php" class="btn btn-danger rounded-pill px-4 btn-sm fw-bold"><i class="fas fa-power-off me-1"></i> ውጣ</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>አዲስ ምርት መዝግብ</h5>
                <form action="process.php" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">የምርት ስም</label>
                        <input type="text" name="p_name" class="form-control" placeholder="ለምሳሌ፡ ስኳር" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">መመጠኛ (Unit)</label>
                        <select name="p_unit" class="form-select">
                            <option value="ኩንታል">ኩንታል (Quintal)</option>
                            <option value="ኪሎግራም">ኪሎግራም (KG)</option>
                            <option value="ሊትር">ሊትር (Litre)</option>
                            <option value="ካርቶን">ካርቶን</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">ብዛት (Quantity)</label>
                        <input type="number" name="p_quantity" class="form-control" min="0" placeholder="0" required autocomplete="off">
                    </div>
                    <button type="submit" name="save_product" class="btn btn-primary w-100 shadow-sm">ምርቱን መዝግብ</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-list text-warning me-2"></i>በመጋዘን ያሉ ምርቶች ዝርዝር</h5>
                <div class="table-responsive table-container">
                    <table class="table table-hover align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>የምርት ስም</th>
                                <th>መመጠኛ</th>
                                <th class="text-center">ያለ ብዛት</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stock_sql = "SELECT * FROM products ORDER BY id DESC";
                            $stock_stmt = $conn->query($stock_sql);
                            while($p_row = $stock_stmt->fetch()) { ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($p_row['product_name']) ?></td>
                                    <td><?= htmlspecialchars($p_row['unit'] ?? '---') ?></td>
                                    <td class="text-center fw-bold text-primary"><?= $p_row['quantity'] ?? 0 ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card p-4">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-shopping-bag text-success me-2"></i>ከማናጀሩ የመጡ ትዕዛዞች</h5>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="text-muted small border-bottom">
                            <tr>
                                <th class="pb-3">ORDER ID</th>
                                <th class="pb-3">ደንበኛ</th>
                                <th class="pb-3">ምርት</th>
                                <th class="pb-3 text-center">ብዛት</th>
                                <th class="pb-3 text-end">ተግባር</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $sql = "SELECT o.*, p.product_name FROM orders o 
                                        JOIN products p ON o.product_id = p.id 
                                        WHERE o.status = 'Pending'";
                                $stmt = $conn->query($sql);
                                if($stmt->rowCount() == 0) {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>አሁን ላይ ምንም ትዕዛዝ የለም።</td></tr>";
                                }
                                while($row = $stmt->fetch()) { ?>
                                    <tr class="border-bottom" style="height: 70px;">
                                        <td class="fw-bold text-primary">#<?= $row['id'] ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['customer_name']) ?></td>
                                        <td><span class="badge bg-light text-dark border px-3 py-2"><?= htmlspecialchars($row['product_name']) ?></span></td>
                                        <td class="text-center fw-bold"><?= $row['quantity'] ?></td>
                                        <td class="text-end">
                                            <a href="update_status.php?id=<?= $row['id'] ?>&to=Ready" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-check me-1"></i> ተዘጋጅቷል
                                            </a>
                                        </td>
                                    </tr>
                                <?php } 
                            } catch (PDOException $e) { echo "<tr><td colspan='5' class='text-center text-danger'>የዳታቤዝ ግንኙነት ስህተት!</td></tr>"; }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="text-center mt-5 mb-4 text-muted">
    <small>&copy; 2026 Smart Factory | Professional Inventory Management</small>
</footer>

</body>
</html>