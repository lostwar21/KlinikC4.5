<div class="row mb-5 py-3 border-bottom">
    <div class="col-12">
        <h2 class="fw-black text-dark-teal text-uppercase">Pengaturan & Profil Akun</h2>
        <p class="text-muted mb-0">Informasi kredensial dan hak akses Anda di dalam sistem.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="panel border-0 shadow-sm bg-white overflow-hidden" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-teal p-5 text-center text-white position-relative">
                <i class="fas fa-user-circle fs-1 mb-3 opacity-75" style="font-size: 5rem !important;"></i>
                <h4 class="mb-1 fw-bold"><?php echo $user['nama_lengkap']; ?></h4>
                <div class="badge bg-white text-teal px-3 py-2 text-uppercase fw-bold" style="font-size: 0.75rem;">
                    <?php echo $user['level']; ?>
                </div>
                
                <!-- Deco wave -->
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 20px; background: white; border-radius: 100% 100% 0 0;"></div>
            </div>
            
            <div class="p-5 pt-4">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-secondary fw-bold text-uppercase pb-1" style="font-size: 0.75rem;">Username</td>
                        <td><span class="fs-5 fw-bold text-dark"><?php echo $user['username']; ?></span></td>
                    </tr>
                    <tr>
                         <td class="text-secondary fw-bold text-uppercase pb-1" style="font-size: 0.75rem;">ID Pengguna</td>
                        <td><span class="fw-semibold text-muted">ID-<?php echo str_pad($user['id_pengguna'], 3, '0', STR_PAD_LEFT); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-secondary fw-bold text-uppercase pb-1" style="font-size: 0.75rem;">Status Akun</td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1">
                                <i class="fas fa-check-circle me-1"></i> AKTIF
                            </span>
                        </td>
                    </tr>
                     <tr>
                        <td class="text-secondary fw-bold text-uppercase pb-1" style="font-size: 0.75rem;">Dibuat Pada</td>
                        <td><span class="text-muted"><?php echo date('d F Y, H:i', strtotime($user['created_at'])); ?></span></td>
                    </tr>
                </table>

                <hr class="my-4">
                
                <div class="alert alert-info border-0 rounded-0" style="background-color: #f0f9ff; color: #0369a1;">
                    <i class="fas fa-info-circle me-2"></i> Password Anda dienkripsi secara aman menggunakan *Bcrypt Hash*. Hubungi Administrator untuk reset password.
                </div>

                <div class="mt-5 d-flex gap-2">
                    <button class="btn btn-secondary w-100 fw-bold py-3" onclick="history.back()" style="border-radius:0;">
                        KEMBALI KE DASHBOARD
                    </button>
                    <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-outline-danger w-100 fw-bold py-3" style="border-radius:0;">
                        <i class="fas fa-sign-out-alt me-2"></i> KELUAR SISTEM
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-teal { background-color: var(--primary-teal); }
.text-teal { color: var(--primary-teal); }
.fw-black { font-weight: 900; }
</style>
