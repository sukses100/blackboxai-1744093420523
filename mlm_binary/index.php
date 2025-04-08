<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-4 mb-4 fw-bold">Raih Kesuksesan Finansial Bersama Kami</h1>
        <p class="lead mb-4">Bergabunglah dengan sistem MLM Binary terpercaya dan mulai perjalanan sukses Anda</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="member/register.php" class="btn btn-light btn-lg">Daftar Sekarang</a>
            <a href="member/login.php" class="btn btn-outline-light btn-lg">Masuk</a>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5">Keuntungan Bergabung</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-gift fa-3x text-primary mb-3"></i>
                    <h4>Bonus Sponsor 10%</h4>
                    <p>Dapatkan bonus sponsor langsung 10% dari setiap member baru yang Anda rekrut</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-network-wired fa-3x text-primary mb-3"></i>
                    <h4>Bonus Level</h4>
                    <p>Nikmati bonus level hingga 5 tingkat: 7%, 5%, 3%, 2%, dan 1%</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-handshake fa-3x text-primary mb-3"></i>
                    <h4>Bonus Pasangan 5%</h4>
                    <p>Raih bonus pasangan 5% dengan batas omset harian Rp 10.000.000</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Package Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Paket Keanggotaan</h2>
        <div class="row g-4">
            <!-- Basic Package -->
            <div class="col-md-6 col-lg-3">
                <div class="package-card h-100">
                    <div class="text-center">
                        <h3>Basic</h3>
                        <div class="price">Rp 300.000</div>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Akses Member Area</li>
                            <li><i class="fas fa-check text-success me-2"></i>Bonus Sponsor 10%</li>
                            <li><i class="fas fa-check text-success me-2"></i>Bonus Level</li>
                            <li><i class="fas fa-check text-success me-2"></i>Bonus Pasangan</li>
                        </ul>
                        <a href="member/register.php?package=basic" class="btn btn-primary mt-3">Pilih Paket</a>
                    </div>
                </div>
            </div>
            
            <!-- Silver Package -->
            <div class="col-md-6 col-lg-3">
                <div class="package-card h-100">
                    <div class="text-center">
                        <h3>Silver</h3>
                        <div class="price">Rp 600.000</div>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Semua Fitur Basic</li>
                            <li><i class="fas fa-check text-success me-2"></i>Prioritas Support</li>
                            <li><i class="fas fa-check text-success me-2"></i>Training Eksklusif</li>
                            <li><i class="fas fa-check text-success me-2"></i>Materi Premium</li>
                        </ul>
                        <a href="member/register.php?package=silver" class="btn btn-primary mt-3">Pilih Paket</a>
                    </div>
                </div>
            </div>
            
            <!-- Gold Package -->
            <div class="col-md-6 col-lg-3">
                <div class="package-card h-100">
                    <div class="text-center">
                        <h3>Gold</h3>
                        <div class="price">Rp 1.000.000</div>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Semua Fitur Silver</li>
                            <li><i class="fas fa-check text-success me-2"></i>Konsultasi Pribadi</li>
                            <li><i class="fas fa-check text-success me-2"></i>Akses Event VIP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Tools Marketing</li>
                        </ul>
                        <a href="member/register.php?package=gold" class="btn btn-primary mt-3">Pilih Paket</a>
                    </div>
                </div>
            </div>
            
            <!-- Platinum Package -->
            <div class="col-md-6 col-lg-3">
                <div class="package-card h-100">
                    <div class="text-center">
                        <h3>Platinum</h3>
                        <div class="price">Rp 1.500.000</div>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Semua Fitur Gold</li>
                            <li><i class="fas fa-check text-success me-2"></i>Mentoring One-on-One</li>
                            <li><i class="fas fa-check text-success me-2"></i>Prioritas Placement</li>
                            <li><i class="fas fa-check text-success me-2"></i>Rewards Eksklusif</li>
                        </ul>
                        <a href="member/register.php?package=platinum" class="btn btn-primary mt-3">Pilih Paket</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Cara Kerja</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h4>1. Daftar</h4>
                    <p>Pilih paket keanggotaan yang sesuai dengan Anda</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-share-alt fa-2x"></i>
                    </div>
                    <h4>2. Bagikan</h4>
                    <p>Bagikan link referral Anda kepada calon member</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4>3. Bangun Tim</h4>
                    <p>Bangun jaringan dengan menempatkan member di kiri dan kanan</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <h4>4. Terima Bonus</h4>
                    <p>Dapatkan bonus sponsor, level, dan pasangan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Siap Memulai Perjalanan Sukses Anda?</h2>
        <p class="lead mb-4">Bergabunglah sekarang dan raih kesuksesan finansial bersama kami</p>
        <a href="member/register.php" class="btn btn-light btn-lg">Daftar Sekarang</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>