<?php
require_once '../includes/header.php';

// Check if registration data exists in session
if (!isset($_SESSION['registration_success']) || !isset($_SESSION['user_data'])) {
    header("Location: register.php");
    exit;
}

$user_data = $_SESSION['user_data'];

// Clear registration session data after displaying
unset($_SESSION['registration_success']);
unset($_SESSION['user_data']);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    
                    <h2 class="mb-4">Pendaftaran Berhasil!</h2>
                    
                    <p class="lead mb-4">
                        Selamat bergabung di MLM Binary System. Akun Anda telah berhasil dibuat.
                    </p>

                    <div class="alert alert-info mb-4">
                        <h5 class="mb-3">Informasi Akun:</h5>
                        <p class="mb-2">
                            <strong>Username:</strong> <?php echo htmlspecialchars($user_data['username']); ?>
                        </p>
                        <p class="mb-2">
                            <strong>Paket:</strong> <?php echo ucfirst(htmlspecialchars($user_data['package_type'])); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Kode Referral:</strong> 
                            <span class="user-select-all"><?php echo htmlspecialchars($user_data['referral_code']); ?></span>
                            <button class="btn btn-sm btn-outline-primary ms-2" 
                                    onclick="copyToClipboard('<?php echo htmlspecialchars($user_data['referral_code']); ?>')"
                                    data-bs-toggle="tooltip" 
                                    title="Salin Kode">
                                <i class="fas fa-copy"></i>
                            </button>
                        </p>
                    </div>

                    <div class="alert alert-warning mb-4">
                        <h5 class="mb-3">Langkah Selanjutnya:</h5>
                        <ol class="text-start mb-0">
                            <li class="mb-2">Login ke akun Anda</li>
                            <li class="mb-2">Lengkapi profil Anda</li>
                            <li class="mb-2">Mulai bangun jaringan Anda</li>
                            <li>Bagikan kode referral Anda</li>
                        </ol>
                    </div>

                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="login.php" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                        </a>
                        <a href="../index.php" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="card border-0 shadow mt-4">
                <div class="card-body text-center p-4">
                    <h4 class="mb-4">Bagikan Link Referral Anda</h4>
                    
                    <?php 
                    $referral_link = SITE_URL . '/member/register.php?ref=' . $user_data['referral_code'];
                    ?>
                    
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="<?php echo $referral_link; ?>" readonly>
                        <button class="btn btn-primary" type="button" 
                                onclick="copyToClipboard('<?php echo $referral_link; ?>')"
                                data-bs-toggle="tooltip" 
                                title="Salin Link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="https://wa.me/?text=<?php echo urlencode('Gabung MLM Binary System sekarang! Daftar melalui link: ' . $referral_link); ?>" 
                           class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($referral_link); ?>" 
                           class="btn btn-primary" target="_blank">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Gabung MLM Binary System sekarang! Daftar melalui link: ' . $referral_link); ?>" 
                           class="btn btn-info" target="_blank">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fa-check-circle {
    animation: scale-up 0.5s ease;
}

@keyframes scale-up {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>