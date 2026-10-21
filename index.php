<?php
// 1. Session መጀመሩን ማረጋገጥ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ተጠቃሚው ቀድሞ ገብቶ ከሆነ ወደ ራሱ ገጽ እንዲሄድ ማድረግ
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $role = trim($_SESSION['role']);
    $dashboard = $role . "_dashboard.php";
    header("Location: " . $dashboard);
    exit();
}
?>
<!DOCTYPE html>
<html lang="am">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Factory - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); 
            background: #ffffff;
        }
        .login-header { 
            background: #2563eb; 
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
            border-radius: 20px 20px 0 0;
        }
        .form-control { 
            border-radius: 10px; 
            padding: 12px; 
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .btn-login { 
            background: #2563eb; 
            border: none; 
            border-radius: 10px; 
            padding: 12px; 
            font-weight: bold; 
            font-size: 1.1rem;
            transition: 0.3s; 
        }
        .btn-login:hover { 
            background: #1d4ed8; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="login-header">
                    <i class="fas fa-industry fa-3x mb-3"></i>
                    <h3 class="fw-bold mb-0">SMART FACTORY</h3>
                    <p class="small opacity-75 mb-0 text-uppercase">Management System</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger d-flex align-items-center small py-2" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <?php 
                                    if($_GET['error'] == 'password_mismatch') echo "የተሳሳተ የይለፍ ቃል!";
                                    elseif($_GET['error'] == 'user_not_found') echo "ተጠቃሚው አልተገኘም!";
                                    else echo "የተሳሳተ የተጠቃሚ ስም ወይም የይለፍ ቃል!";
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="auth_process.php" method="POST" autocomplete="off">
                        <!-- Dummy fields to block browser autofill -->
                        <input type="text" style="display:none">
                        <input type="password" style="display:none">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">የተጠቃሚ ስም (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                <!-- readonly እና onfocus ተጨምረዋል -->
                                <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">የይለፍ ቃል (Password)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <!-- readonly እና onfocus ተጨምረዋል -->
                                <input type="password" name="password" class="form-control" placeholder="********" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-login w-100 shadow-sm">
                            ይግቡ <i class="fas fa-sign-in-alt ms-2"></i>
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <small class="text-muted">&copy; 2026 Smart Factory Management</small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>