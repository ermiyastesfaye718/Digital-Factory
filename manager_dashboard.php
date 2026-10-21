<?php
// 1. የሴሽን እና የደህንነት ማረጋገጫ (Session & Security Check)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ተጠቃሚው ሎጊን አድርጎ መሆኑን ማረጋገጥ
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=access_denied");
    exit();
}

// ብራውዘር ከእሽ (Cache) እንዳይይዝ እና Back ሲሉ ዳታ እንዳይቀመጥ መግታት
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

include 'config.php'; 

// 2. አዲስ ሰራተኛ ለመመዝገብ
if (isset($_POST['add_user'])) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    try {
        $sql = "INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $username, $password, $role]);
        
        header("Location: manager_dashboard.php?status=success");
        exit();
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            $error_msg = "ይህ የተጠቃሚ ስም (Username) አስቀድሞ ተይዟል!";
        } else {
            $error_msg = "ስህተት ተፈጥሯል: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Smart Factory</title>
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
        .navbar { 
            background: var(--sidebar-bg); 
            color: white; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04), 0 4px 6px -4px rgba(0,0,0,0.02); 
            background: #ffffff;
            transition: transform 0.2s;
        }
        .sidebar-link { 
            text-decoration: none; 
            color: #94a3b8; 
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px; 
            border-radius: 10px; 
            font-weight: 500;
            transition: all 0.3s ease; 
            margin-bottom: 8px;
        }
        .sidebar-link:hover, .sidebar-link.active { 
            background: var(--primary-color); 
            color: white; 
            transform: translateX(4px); 
        }
        /* የተስተካከለው የ Logout/Danger Hover ዲዛይን (ለጥቁር ዳራ በጣም የሚያምር እና ግልጽ የሆነ) */
        .sidebar-link.text-danger {
            color: #f87171 !important;
        }
        .sidebar-link.text-danger:hover {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #fca5a5 !important;
            transform: translateX(4px);
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
        .badge-role { font-size: 0.75rem; text-transform: uppercase; padding: 6px 12px; border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg py-3 px-4 mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="#"><i class="fas fa-industry me-2 text-info"></i> SMART FACTORY</a>
        <div class="d-flex align-items-center">
            <span class="text-white-50 me-3">ማናጀር: <strong class="text-white"><?= htmlspecialchars($_SESSION['full_name'] ?? 'ማናጀር') ?></strong></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold"><i class="fas fa-sign-out-alt me-1"></i> ውጣ</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-4 shadow-sm">
                <h6 class="fw-bold text-muted mb-3 text-uppercase small">ዋና ማውጫ</h6>
                <a href="manager_dashboard.php" class="sidebar-link active mb-2"><i class="fas fa-chart-line"></i> አጠቃላይ መረጃ</a>
                <a href="#" class="sidebar-link mb-2"><i class="fas fa-users"></i> ሰራተኞች</a>
                <a href="#" class="sidebar-link mb-2"><i class="fas fa-boxes"></i> ምርቶች</a>
                <hr class="text-muted">
                <a href="logout.php" class="sidebar-link text-danger"><i class="fas fa-power-off"></i> ውጣ</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> ሰራተኛው በተሳካ ሁኔታ ተመዝግቧል!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= $error_msg ?>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Registration Form Card -->
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-primary mb-4 border-bottom pb-3"><i class="fas fa-user-plus me-2"></i>አዲስ ሰራተኛ መዝግብ</h5>
                        <form action="" method="POST" autocomplete="off">
                            <input type="text" style="display:none">
                            <input type="password" style="display:none">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">ሙሉ ስም</label>
                                <input type="text" name="full_name" class="form-control" placeholder="ሙሉ ስም ያስገቡ" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">የተጠቃሚ ስም (Username)</label>
                                <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">የይለፍ ቃል (Password)</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">የስራ ድርሻ (Role)</label>
                                <select name="role" class="form-select" required>
                                    <option value="inventory">ኢንቬንተሪ (Inventory)</option>
                                    <option value="logistics">ሎጂስቲክስ (Logistics)</option>
                                    <option value="manager">ማናጀር (Manager)</option>
                                </select>
                            </div>
                            <button type="submit" name="add_user" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> መዝግብ
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Existing Employees Table Card -->
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-success mb-4 border-bottom pb-3"><i class="fas fa-users-cog me-2"></i>ነባር ሰራተኞች</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="small">ስም</th>
                                        <th class="small">ድርሻ</th>
                                        <th class="small text-center">ድርጊት</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 6");
                                    if($users->rowCount() > 0) {
                                        while($u = $users->fetch()) { ?>
                                            <tr>
                                                <td class="small fw-bold"><?= htmlspecialchars($u['full_name']) ?></td>
                                                <td><span class="badge bg-light text-dark border badge-role"><?= htmlspecialchars($u['role']) ?></span></td>
                                                <td class="text-center">
                                                    <a href="#" class="text-danger small" title="ሰርዝ"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center text-muted py-3'>ምንም ሰራተኛ የለም።</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>