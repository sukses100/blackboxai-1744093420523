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
    
    // Filter parameters
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    
    // Build query conditions
    $conditions = [];
    $params = [];
    
    if ($type) {
        $conditions[] = "t.type = ?";
        $params[] = $type;
    }
    
    if ($dateFrom) {
        $conditions[] = "DATE(t.created_at) >= ?";
        $params[] = $dateFrom;
    }
    
    if ($dateTo) {
        $conditions[] = "DATE(t.created_at) <= ?";
        $params[] = $dateTo;
    }
    
    $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
    
    // Get transactions
    $query = "
        SELECT t.*, u.username, u.full_name
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        $whereClause
        ORDER BY t.created_at DESC
        LIMIT 100
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll();
    
    // Get summary statistics
    $statsQuery = "
        SELECT 
            COUNT(*) as total_count,
            COALESCE(SUM(amount), 0) as total_amount,
            COUNT(DISTINCT user_id) as unique_users
        FROM transactions t
        $whereClause
    ";
    
    $stmt = $db->prepare($statsQuery);
    $stmt->execute($params);
    $stats = $stmt->fetch();
    
} catch (PDOException $e) {
    die("Terjadi kesalahan sistem");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - MLM Binary Admin</title>
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
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4>MLM Binary</h4>
                    <p class="mb-0">Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">
                            <i class="fas fa-users me-2"></i>Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="transactions.php">
                            <i class="fas fa-money-bill-wave me-2"></i>Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Transaction Management</h2>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stats-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Transactions</h6>
                                    <h3 class="mb-0"><?php echo number_format($stats['total_count']); ?></h3>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-file-invoice fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Amount</h6>
                                    <h3 class="mb-0">Rp <?php echo number_format($stats['total_amount']); ?></h3>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Unique Users</h6>
                                    <h3 class="mb-0"><?php echo number_format($stats['unique_users']); ?></h3>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Transaction Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="bonus_sponsor" <?php echo $type === 'bonus_sponsor' ? 'selected' : ''; ?>>
                                        Sponsor Bonus
                                    </option>
                                    <option value="bonus_level" <?php echo $type === 'bonus_level' ? 'selected' : ''; ?>>
                                        Level Bonus
                                    </option>
                                    <option value="bonus_pairing" <?php echo $type === 'bonus_pairing' ? 'selected' : ''; ?>>
                                        Pairing Bonus
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $dateFrom; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $dateTo; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Username</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($transaction['username']); ?>
                                            <small class="d-block text-muted">
                                                <?php echo htmlspecialchars($transaction['full_name']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php
                                                $badgeClass = '';
                                                $typeLabel = '';
                                                switch ($transaction['type']) {
                                                    case 'bonus_sponsor':
                                                        $badgeClass = 'bg-success';
                                                        $typeLabel = 'Sponsor Bonus';
                                                        break;
                                                    case 'bonus_level':
                                                        $badgeClass = 'bg-info';
                                                        $typeLabel = 'Level Bonus';
                                                        break;
                                                    case 'bonus_pairing':
                                                        $badgeClass = 'bg-warning';
                                                        $typeLabel = 'Pairing Bonus';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-secondary';
                                                        $typeLabel = ucfirst($transaction['type']);
                                                }
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>">
                                                <?php echo $typeLabel; ?>
                                            </span>
                                        </td>
                                        <td>Rp <?php echo number_format($transaction['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($transaction['description'] ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>