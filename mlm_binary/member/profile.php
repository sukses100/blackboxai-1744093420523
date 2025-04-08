<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

try {
    $db = Database::getInstance()->getConnection();
    
    // Get user data
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        
        if (empty($full_name) || empty($email)) {
            $error_message = "Nama lengkap dan email harus diisi";
        } else {
            // Update basic info
            $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $_SESSION['user_id']]);
            
            // Update password if provided
            if (!empty($current_password) && !empty($new_password)) {
                if (password_verify($current_password, $user['password'])) {
                    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $_SESSION['user_id']]);
                    $success_message = "Profil dan password berhasil diperbarui";
                } else {
                    $error_message = "Password saat ini tidak valid";
                }
            } else {
                $success_message = "Profil berhasil diperbarui";
            }
            
            // Refresh user data
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        }
    }
} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan sistem";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Profil Member</h4>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <!-- Member Info -->
                        <div class="mb-4">
                            <h5>Informasi Member</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kode Referral</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['referral_code']); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Info -->
                        <div class="mb-4">
                            <h5>Informasi Pribadi</h5>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="mb-4">
                            <h5>Ubah Password</h5>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>