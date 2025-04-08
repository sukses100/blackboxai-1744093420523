<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Get total members count
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $totalMembers = $stmt->fetchColumn();
    
    // Get total transactions amount
    $stmt = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions");
    $totalTransactions = $stmt->fetchColumn();
    
    // Get today's registrations
    $stmt = $db->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()");
    $todayRegistrations = $stmt->fetchColumn();
    
    // Get today's transactions
    $stmt = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE DATE(created_at) = CURDATE()");
    $todayTransactions = $stmt->fetchColumn();
    
    // Get recent members
    $stmt = $db->query("
        SELECT username, full_name, package_type, created_at 
        FROM users 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recentMembers = $stmt->fetchAll();
    
    // Get recent transactions
    $stmt = $db->query("
        SELECT t.*, u.username 
        FROM transactions t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC 
        LIMIT 10
    ");
    $recentTransactions = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Terjadi kesalahan sistem");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MLM Binary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 1rem;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
            transition: transform .3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <div class="p-3 text-center">
                        <h4>MLM Binary</h4>
                        <p class="mb-0">Admin Panel</p>
                    </div>
                    <hr class="mx-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="members.php">
                                <i class="fas fa-users me-2"></i>Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="transactions.php">
                                <i class="fas fa-money-bill-wave me-2"></i>Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="genealogy.php">
                                <i class="fas fa-sitemap me-2"></i>Genealogy
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li class="nav-item mt-auto">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <span class="text-muted">Welcome, Admin</span>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2 text-muted">Total Members</h6>
                                        <h3 class="card-title mb-0"><?php echo number_format($totalMembers); ?></h3>
                                    </div>
                                    <div class="text-primary">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2 text-muted">Total Transactions</h6>
                                        <h3 class="card-title mb-0">Rp <?php echo number_format($totalTransactions); ?></h3>
                                    </div>
                                    <div class="text-success">
                                        <i class="fas fa-money-bill-wave fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2 text-muted">Today's Registrations</h6>
                                        <h3 class="card-title mb-0"><?php echo number_format($todayRegistrations); ?></h3>
                                    </div>
                                    <div class="text-info">
                                        <i class="fas fa-user-plus fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2 text-muted">Today's Transactions</h6>
                                        <h3 class="card-title mb-0">Rp <?php echo number_format($todayTransactions); ?></h3>
                                    </div>
                                    <div class="text-warning">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Members -->
                    <div class="col-md-6 mb-4">
                        <div class="table-container">
                            <h5 class="mb-4">Recent Members</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Package</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentMembers as $member): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($member['username']); ?></td>
                                            <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?php echo ucfirst($member['package_type']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($member['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <a href="members.php" class="btn btn-sm btn-primary">View All Members</a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="col-md-6 mb-4">
                        <div class="table-container">
                            <h5 class="mb-4">Recent Transactions</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentTransactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                                            <td>
                                                <?php
                                                    switch ($transaction['type']) {
                                                        case 'bonus_sponsor':
                                                            echo '<span class="badge bg-success">Sponsor</span>';
                                                            break;
                                                        case 'bonus_level':
                                                            echo '<span class="badge bg-info">Level</span>';
                                                            break;
                                                        case 'bonus_pairing':
                                                            echo '<span class="badge bg-warning">Pairing</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge bg-secondary">Other</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>Rp <?php echo number_format($transaction['amount']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($transaction['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <a href="transactions.php" class="btn btn-sm btn-primary">View All Transactions</a>
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