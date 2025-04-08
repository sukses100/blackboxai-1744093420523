<?php
require_once '../includes/header.php';

// Get referral code from URL
$referral_code = isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : '';
$referral_link = 'http://' . $_SERVER['HTTP_HOST'] . '/mlm_binary/member/register.php?ref=' . $referral_code;
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Pendaftaran Berhasil!</h2>
                    <p class="card-text lead mb-4">
                        Selamat bergabung di MLM Binary System. Akun Anda telah berhasil dibuat.
                    </p>
                    
                    <?php if ($referral_code): ?>
                    <div class="mb-4">
                        <h4>Kode Referral Anda</h4>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="<?php echo $referral_code; ?>" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('<?php echo $referral_code; ?>')" data-bs-toggle="tooltip" data-bs-title="Salin Kode">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4>Link Referral Anda</h4>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="<?php echo $referral_link; ?>" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('<?php echo $referral_link; ?>')" data-bs-toggle="tooltip" data-bs-title="Salin Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Bagikan link ini untuk mengundang member baru</small>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-3">
                        <a href="login.php" class="btn btn-primary btn-lg">Masuk ke Akun</a>
                        <a href="../index.php" class="btn btn-outline-primary">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Start Guide -->
            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">Panduan Cepat Memulai</h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                    <span>1</span>
                                </div>
                                <div>
                                    <h5>Masuk ke Akun</h5>
                                    <p class="mb-0">Login menggunakan username dan password yang telah didaftarkan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                    <span>2</span>
                                </div>
                                <div>
                                    <h5>Lengkapi Profil</h5>
                                    <p class="mb-0">Lengkapi informasi profil Anda di dashboard member</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                    <span>3</span>
                                </div>
                                <div>
                                    <h5>Bagikan Link Referral</h5>
                                    <p class="mb-0">Mulai bagikan link referral Anda untuk mengundang member baru</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                    <span>4</span>
                                </div>
                                <div>
                                    <h5>Pantau Perkembangan</h5>
                                    <p class="mb-0">Pantau perkembangan jaringan dan bonus Anda di dashboard</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    
    // Show tooltip
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        const instance = bootstrap.Tooltip.getInstance(tooltip);
        if (instance) {
            tooltip.setAttribute('data-bs-original-title', 'Tersalin!');
            instance.show();
            
            setTimeout(() => {
                tooltip.setAttribute('data-bs-original-title', 'Salin');
                instance.hide();
            }, 2000);
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>