<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Initialize variables
$errors = [];
$success = false;

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $package_type = $_POST['package_type'] ?? '';
    $referral_code = trim($_POST['referral_code'] ?? '');
    
    // Validate input
    if (empty($username)) {
        $errors[] = "Username harus diisi";
    }
    if (empty($email)) {
        $errors[] = "Email harus diisi";
    }
    if (empty($password)) {
        $errors[] = "Password harus diisi";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Password tidak cocok";
    }
    if (empty($full_name)) {
        $errors[] = "Nama lengkap harus diisi";
    }
    if (empty($package_type)) {
        $errors[] = "Pilih paket keanggotaan";
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Check if username exists
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "Username sudah digunakan";
            } else {
                // Generate unique referral code
                $referral_code = strtoupper(substr($username, 0, 3) . rand(1000, 9999));
                
                // Insert new user
                $stmt = $db->prepare("
                    INSERT INTO users (username, password, email, full_name, phone, referral_code, package_type, registration_ticket_type)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'regular')
                ");
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt->execute([
                    $username,
                    $hashed_password,
                    $email,
                    $full_name,
                    $phone,
                    $referral_code,
                    $package_type
                ]);
                
                $success = true;
                
                // Redirect to success page
                header("Location: registration_success.php?ref=" . $referral_code);
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi.";
        }
    }
}

// Get package type from URL if provided
$selected_package = $_GET['package'] ?? '';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="text-center mb-4">Pendaftaran Member Baru</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" onsubmit="return validateRegistrationForm()">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'passwordIcon')">
                                    <i id="passwordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', 'confirmPasswordIcon')">
                                    <i id="confirmPasswordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Pilih Paket</label>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="form-check package-option">
                                    <input class="form-check-input" type="radio" name="package_type" id="package_basic" value="basic" <?php echo $selected_package === 'basic' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="package_basic">
                                        Basic<br>
                                        <small>Rp 300.000</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-check package-option">
                                    <input class="form-check-input" type="radio" name="package_type" id="package_silver" value="silver" <?php echo $selected_package === 'silver' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="package_silver">
                                        Silver<br>
                                        <small>Rp 600.000</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-check package-option">
                                    <input class="form-check-input" type="radio" name="package_type" id="package_gold" value="gold" <?php echo $selected_package === 'gold' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="package_gold">
                                        Gold<br>
                                        <small>Rp 1.000.000</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-check package-option">
                                    <input class="form-check-input" type="radio" name="package_type" id="package_platinum" value="platinum" <?php echo $selected_package === 'platinum' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="package_platinum">
                                        Platinum<br>
                                        <small>Rp 1.500.000</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="referral_code" class="form-label">Kode Referral (Opsional)</label>
                        <input type="text" class="form-control" id="referral_code" name="referral_code" value="<?php echo isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : ''; ?>">
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Daftar Sekarang</button>
                        <p class="mt-3">
                            Sudah punya akun? <a href="login.php">Masuk di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>