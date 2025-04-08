<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Initialize variables
$errors = [];

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username)) {
        $errors[] = "Username harus diisi";
    }
    if (empty($password)) {
        $errors[] = "Password harus diisi";
    }
    
    // If no errors, proceed with login
    if (empty($errors)) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Check user credentials
            $stmt = $db->prepare("SELECT id, username, password, full_name FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Username atau password salah";
            }
        } catch (PDOException $e) {
            $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h2 class="text-center mb-4">Masuk Member</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'passwordIcon')">
                                <i id="passwordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">
                            Belum punya akun? <a href="register.php">Daftar di sini</a>
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- Benefits Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="text-center mb-4">Keuntungan Member</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-gift text-primary me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">Bonus Sponsor 10%</h6>
                                    <small class="text-muted">Dari setiap member baru</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-network-wired text-primary me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">Bonus Level</h6>
                                    <small class="text-muted">Hingga 5 tingkat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-handshake text-primary me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">Bonus Pasangan 5%</h6>
                                    <small class="text-muted">Dari omset pasangan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line text-primary me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">Monitoring Real-time</h6>
                                    <small class="text-muted">Pantau perkembangan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>