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
    
    // Get current user's data with binary positions
    $stmt = $db->prepare("
        SELECT u.*, bp.left_child_id, bp.right_child_id
        FROM users u
        LEFT JOIN binary_positions bp ON u.id = bp.user_id
        WHERE u.id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();
    
    // Get left child data
    $leftChild = null;
    if ($currentUser['left_child_id']) {
        $stmt->execute([$currentUser['left_child_id']]);
        $leftChild = $stmt->fetch();
    }
    
    // Get right child data
    $rightChild = null;
    if ($currentUser['right_child_id']) {
        $stmt->execute([$currentUser['right_child_id']]);
        $rightChild = $stmt->fetch();
    }
    
    // Get total downlines count
    function getDownlineCount($db, $userId, $position) {
        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM users 
            WHERE sponsor_id = ? AND position = ?
        ");
        $stmt->execute([$userId, $position]);
        return $stmt->fetchColumn();
    }
    
    $leftDownlineCount = getDownlineCount($db, $_SESSION['user_id'], 'left');
    $rightDownlineCount = getDownlineCount($db, $_SESSION['user_id'], 'right');
    
} catch (PDOException $e) {
    die("Terjadi kesalahan sistem.");
}
?>

<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Struktur Jaringan Binary</h4>
                        <div class="d-flex gap-3">
                            <div class="text-center">
                                <small class="d-block text-muted">Total Kiri</small>
                                <strong><?php echo $leftDownlineCount; ?></strong>
                            </div>
                            <div class="text-center">
                                <small class="d-block text-muted">Total Kanan</small>
                                <strong><?php echo $rightDownlineCount; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Binary Tree Structure -->
    <div class="binary-tree">
        <!-- Level 1 (Current User) -->
        <div class="level-1 text-center mb-5">
            <div class="user-node current-user">
                <div class="node-content">
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong>
                        <span class="badge bg-primary"><?php echo ucfirst($currentUser['package_type']); ?></span>
                        <small class="d-block text-muted"><?php echo htmlspecialchars($currentUser['full_name']); ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connector Lines -->
        <div class="tree-lines">
            <div class="v-line"></div>
            <div class="h-line"></div>
        </div>

        <!-- Level 2 (Children) -->
        <div class="level-2">
            <div class="row">
                <!-- Left Child -->
                <div class="col-md-6 text-center">
                    <div class="user-node <?php echo $leftChild ? 'filled' : 'empty'; ?>">
                        <div class="node-content">
                            <?php if ($leftChild): ?>
                                <div class="avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-info">
                                    <strong><?php echo htmlspecialchars($leftChild['username']); ?></strong>
                                    <span class="badge bg-primary"><?php echo ucfirst($leftChild['package_type']); ?></span>
                                    <small class="d-block text-muted"><?php echo htmlspecialchars($leftChild['full_name']); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="empty-node">
                                    <i class="fas fa-user-plus"></i>
                                    <p class="mb-0">Posisi Kosong</p>
                                    <small class="text-muted">Kiri</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Child -->
                <div class="col-md-6 text-center">
                    <div class="user-node <?php echo $rightChild ? 'filled' : 'empty'; ?>">
                        <div class="node-content">
                            <?php if ($rightChild): ?>
                                <div class="avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-info">
                                    <strong><?php echo htmlspecialchars($rightChild['username']); ?></strong>
                                    <span class="badge bg-primary"><?php echo ucfirst($rightChild['package_type']); ?></span>
                                    <small class="d-block text-muted"><?php echo htmlspecialchars($rightChild['full_name']); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="empty-node">
                                    <i class="fas fa-user-plus"></i>
                                    <p class="mb-0">Posisi Kosong</p>
                                    <small class="text-muted">Kanan</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.binary-tree {
    position: relative;
    padding: 40px 0;
}

.user-node {
    display: inline-block;
    padding: 15px;
    border-radius: 10px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.user-node:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.user-node.current-user {
    background: #e3f2fd;
    border: 2px solid #90caf9;
}

.user-node.empty {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
}

.user-node.filled {
    border: 2px solid #a5d6a7;
}

.avatar {
    width: 50px;
    height: 50px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}

.avatar i {
    font-size: 1.5rem;
    color: #6c757d;
}

.user-info {
    text-align: center;
}

.empty-node {
    text-align: center;
    padding: 20px;
}

.empty-node i {
    font-size: 2rem;
    color: #adb5bd;
    margin-bottom: 10px;
}

.tree-lines {
    position: absolute;
    top: 150px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 100px;
}

.v-line {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 50px;
    background: #dee2e6;
}

.h-line {
    position: absolute;
    top: 50px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #dee2e6;
}

.badge {
    margin: 5px 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tree-lines {
        display: none;
    }
    
    .level-2 .col-md-6 {
        margin-bottom: 20px;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>