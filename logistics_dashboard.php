<?php
include 'check_auth.php'; 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Dashboard - Smart Factory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --primary-color: #2563eb;
            --bg-color: #f8fafc;
        }
        body { 
            background-color: var(--bg-color); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            color: #334155;
        }
        .sidebar { 
            min-height: 100vh; 
            background: var(--sidebar-bg); 
            color: white; 
            padding: 25px 15px; 
            position: sticky; 
            top: 0; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05); 
        }
        .sidebar .brand { 
            font-size: 1.2rem; 
            font-weight: bold; 
            color: #f8fafc; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            text-align: center;
        }
        .sidebar a { 
            color: #94a3b8; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 12px 16px; 
            border-radius: 10px; 
            font-weight: 500; 
            transition: all 0.3s ease; 
            margin-bottom: 8px; 
        }
        .sidebar a:hover, .sidebar a.active { 
            background: var(--primary-color); 
            color: white; 
            transform: translateX(4px); 
        }
        .sidebar a.text-danger:hover { 
            background: #dc2626; 
            color: white; 
        }
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04), 0 4px 6px -4px rgba(0,0,0,0.02); 
            background: #ffffff; 
            transition: transform 0.2s; 
        }
        .form-control, .form-select { 
            border-radius: 10px; 
            padding: 11px 14px; 
            border: 1px solid #cbd5e1; 
            background-color: #f8fafc; 
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus { 
            border-color: var(--primary-color); 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); 
            background-color: #fff;
        }
        .table th { 
            font-weight: 600; 
            color: #64748b; 
            font-size: 0.85rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            background-color: #f8fafc !important;
            border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            vertical-align: middle;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .badge { 
            padding: 6px 12px; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 0.75rem; 
        }
        .page-header {
            background: white;
            padding: 20px 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-2 sidebar d-none d-md-block">
            <div class="brand">
                <i class="fas fa-shipping-fast text-primary me-2"></i> Logistics
            </div>
            <nav class="nav flex-column">
                <a href="#" class="active"><i class="fas fa-chart-pie"></i> ዳሽቦርድ</a>
                <a href="#"><i class="fas fa-boxes"></i> ስርጭት</a>
                <a href="logout.php" class="text-danger mt-5"><i class="fas fa-sign-out-alt"></i> ውጣ (Logout)</a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-10 ms-sm-auto px-4 py-4">
            <!-- Top Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold text-dark mb-1">የተሽከርካሪዎች እና የስርጭት ቁጥጥር</h4>
                    <p class="text-muted small mb-0">Smart Factory Logistics & Fleet Management System</p>
                </div>
                <div>
                    <span class="badge bg-primary px-3 py-2"><i class="fas fa-user-shield me-1"></i> Logistics Officer</span>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- Vehicle Registration Card -->
                <div class="col-lg-4">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-primary mb-4"><i class="fas fa-truck-pickup me-2"></i>አዲስ መኪና መዝግብ</h5>
                        <form action="process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">የሰሌዳ ቁጥር (Plate No)</label>
                                <input type="text" name="plate_no" class="form-control" placeholder="ለምሳሌ፦ AA 2 A12345" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">የሹፌር ስም (Driver)</label>
                                <input type="text" name="driver" class="form-control" placeholder="የሹፌር ሙሉ ስም" required>
                            </div>
                            <button type="submit" name="save_vehicle" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i> መኪናውን መዝግብ
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Ready Orders Dispatch Card -->
                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-success mb-4"><i class="fas fa-clipboard-check me-2"></i>ለመጫን ዝግጁ የሆኑ ትዕዛዞች (Ready to Dispatch)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th> 
                                        <th>ደንበኛ</th> 
                                        <th>ምርት</th> 
                                        <th colspan="2">መኪና መድብ እና አሳፍር</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $ready_orders = $conn->query("SELECT o.id, o.customer_name, p.product_name 
                                                                    FROM orders o 
                                                                    JOIN products p ON o.product_id = p.id 
                                                                    WHERE o.status = 'Ready'");
                                        
                                        if($ready_orders->rowCount() > 0) {
                                            while($order = $ready_orders->fetch()) { ?>
                                                <tr>
                                                    <td class="fw-bold text-primary">#<?= $order['id'] ?></td>
                                                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($order['product_name']) ?></span></td>
                                                    
                                                    <td colspan="2">
                                                        <form action="dispatch_order.php" method="POST" class="d-flex gap-2">
                                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                            <select name="vehicle_id" class="form-select form-select-sm" required>
                                                                <option value="">-- መኪና ይምረጡ --</option>
                                                                <?php
                                                                $trucks = $conn->query("SELECT * FROM vehicles WHERE status = 'Available'");
                                                                while($t = $trucks->fetch()) {
                                                                    echo "<option value='{$t['id']}'>{$t['plate_number']} ({$t['driver_name']})</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <button type="submit" name="dispatch" class="btn btn-success btn-sm px-3 fw-bold text-nowrap">
                                                                <i class="fas fa-paper-plane me-1"></i> አሳፍር
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php } 
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>ምንም ለመጫን ዝግጁ የሆነ ትዕዛዝ የለም።</td></tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "<tr><td colspan='4' class='text-danger'>ዳታቤዝ ስህተት፡ " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Fleet Status Table -->
            <div class="card p-4 mt-4">
                <h5 class="fw-bold text-dark mb-4"><i class="fas fa-truck-moving me-2 text-primary"></i>የመኪናዎች አጠቃላይ ሁኔታ (Vehicle Fleet Status)</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>የሰሌዳ ቁጥር</th> 
                                <th>የሹፌር ስም</th> 
                                <th>ሁኔታ (Status)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_vehicles = $conn->query("SELECT * FROM vehicles");
                            if($all_vehicles->rowCount() > 0) {
                                while($v = $all_vehicles->fetch()) {
                                    $status_class = ($v['status'] == 'Available') ? 'bg-success' : 'bg-warning text-dark';
                                    $status_text = ($v['status'] == 'Available') ? 'ዝግጁ (Available)' : 'በጉዞ ላይ (On Trip)';
                                    echo "<tr>
                                            <td class='fw-bold'>{$v['plate_number']}</td>
                                            <td>{$v['driver_name']}</td>
                                            <td><span class='badge {$status_class}'>{$status_text}</span></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center text-muted py-3'>ምንም መኪና አልተመዘገበም።</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>