<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Get user data
    $stmt = $db->prepare("
        SELECT u.*, bp.left_child_id, bp.right_child_id
        FROM users u
        LEFT JOIN binary_positions bp ON u.id = bp.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    // Get total direct referrals
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE sponsor_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalDirectReferrals = $stmt->fetchColumn();
    
    // Get total network members (all levels)
    $stmt = $db->prepare("
        WITH RECURSIVE network AS (
            SELECT id, sponsor_id, 1 as level
            FROM users
            WHERE sponsor_id = ?
            UNION ALL
            SELECT u.id, u.sponsor_id, n.level + 1
            FROM users u
            INNER JOIN network n ON u.sponsor_id = n.id
            WHERE n.level < 5
        )
        SELECT COUNT(*) FROM network
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $totalNetworkMembers = $stmt->fetchColumn();
    
    // Get total earnings
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM transactions
        WHERE user_id = ? AND type IN ('bonus_sponsor', 'bonus_level', 'bonus_pairing')
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $totalEarnings = $stmt->fetchColumn();
    
    // Get recent transactions
    $stmt = $db->prepare("
        SELECT *
        FROM transactions
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recentTransactions = $stmt->fetchAll();
    
    // Get today's pairing bonus
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(amount), 0) as total
        FROM transactions
        WHERE user_id = ? 
        AND type = 'bonus_pairing'
        AND DATE(created_at) = CURDATE()
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $todayPairingBonus = $stmt->fetchColumn();
    
} catch (PDOException $e) {
    die("Terjadi kesalahan sistem.");
}
?>

<div class="container py-5">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-1">Selamat Datang, <?php echo htmlspecialchars($user['full_name']); ?>!</h4>
                            <p class="text-muted mb-0">
                                ID Member: <?php echo htmlspecialchars($user['username']); ?> | 
                                Paket: <?php echo ucfirst(htmlspecialchars($user['package_type'])); ?>
                            </p>
                        </div>
                        <div class="ms-auto">
                            <a href="genealogy.php" class="btn btn-primary">
                                <i class="fas fa-sitemap me-2"></i>Lihat Genealogi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-stat">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-1"><?php echo number_format($totalDirectReferrals); ?></h3>
                        <p class="mb-0">Referral Langsung</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-stat">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-1"><?php echo number_format($totalNetworkMembers); ?></h3>
                        <p class="mb-0">Total Jaringan</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-stat">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-1">Rp <?php echo number_format($totalEarnings); ?></h3>
                        <p class="mb-0">Total Bonus</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-stat">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-1">Rp <?php echo number_format($todayPairingBonus); ?></h3>
                        <p class="mb-0">Bonus Pasangan Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Link Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Link Referral Anda</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" id="referralLink" 
                               value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/mlm_binary/member/register.php?ref=<?php echo $user['referral_code']; ?>" 
                               readonly>
                        <button class="btn btn-primary" type="button" onclick="copyReferralLink('referralLink')">
                            <i class="fas fa-copy me-2"></i>Salin Link
                        </button>
                    </div>
                    <small class="text-muted">Bagikan link ini untuk mengundang member baru</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Binary Tree Preview -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Struktur Binary</h5>
                    <div class="text-center">
                        <div class="binary-preview mb-3">
                            <!-- Root (Current User) -->
                            <div class="binary-node root">
                                <i class="fas fa-user"></i>
                                <div class="small">Anda</div>
                            </div>
                            
                            <!-- Left and Right Children -->
                            <div class="d-flex justify-content-around mt-4">
                                <div class="binary-node <?php echo $user['left_child_id'] ? 'filled' : 'empty'; ?>">
                                    <i class="fas <?php echo $user['left_child_id'] ? 'fa-user' : 'fa-user-plus'; ?>"></i>
                                    <div class="small">Kiri</div>
                                </div>
                                <div class="binary-node <?php echo $user['right_child_id'] ? 'filled' : 'empty'; ?>">
                                    <i class="fas <?php echo $user['right_child_id'] ? 'fa-user' : 'fa-user-plus'; ?>"></i>
                                    <div class="small">Kanan</div>
                                </div>
                            </div>
                        </div>
                        <a href="genealogy.php" class="btn btn-outline-primary">
                            Lihat Genealogi Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Transaksi Terakhir</h5>
                    <?php if ($recentTransactions): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentTransactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($transaction['created_at'])); ?></td>
                                            <td>
                                                <?php
                                                    switch ($transaction['type']) {
                                                        case 'bonus_sponsor':
                                                            echo 'Bonus Sponsor';
                                                            break;
                                                        case 'bonus_level':
                                                            echo 'Bonus Level';
                                                            break;
                                                        case 'bonus_pairing':
                                                            echo 'Bonus Pasangan';
                                                            break;
                                                        default:
                                                            echo ucfirst($transaction['type']);
                                                    }
                                                ?>
                                            </td>
                                            <td>Rp <?php echo number_format($transaction['amount']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Belum ada transaksi</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.binary-preview {
    position: relative;
    padding: 20px;
}

.binary-node {
    display: inline-block;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}

.binary-node.root {
    background-color: #e3f2fd;
    border-color: #90caf9;
}

.binary-node.filled {
    background-color: #e8f5e9;
    border-color: #a5d6a7;
}

.binary-node.empty {
    background-color: #fff3e0;
    border-color: #ffcc80;
}

.binary-node i {
    font-size: 1.2rem;
    margin-bottom: 2px;
}

.binary-node .small {
    font-size: 0.7rem;
    position: absolute;
    bottom: -20px;
    width: 100%;
    text-align: center;
}
</style>

<?php require_once '../includes/footer.php'; ?>