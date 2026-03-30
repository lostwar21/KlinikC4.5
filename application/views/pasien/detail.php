<div class="row mb-5">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('pasien'); ?>" class="text-decoration-none">Data Pasien</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pasien</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark-teal">PROFIL PASIEN</h3>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel h-100 shadow-sm border-0" style="border-radius: 20px;">
            <div class="text-center mb-4 pt-3">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3 shadow-sm" style="width: 100px; height: 100px; border: 5px solid #fff;">
                    <i class="fas fa-user fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1"><?php echo $p['nama']; ?></h4>
                <span class="badge bg-teal-soft text-teal fw-bold px-3 py-2" style="border-radius: 8px;"><?php echo $p['nomor_rm']; ?></span>
            </div>
            
            <div class="mt-4 pt-3 border-top border-light">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-semibold small">Jenis Kelamin</span>
                    <span class="text-dark fw-bold"><?php echo ($p['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-semibold small">Usia</span>
                    <span class="text-dark fw-bold"><?php echo $p['usia']; ?> Tahun</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-semibold small">Tempat/Tgl Lahir</span>
                    <span class="text-dark fw-bold"><?php echo $p['tempat_lahir'] ?: '-'; ?>, <?php echo date('d/m/Y', strtotime($p['tanggal_lahir'])); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-semibold small">No. Telepon</span>
                    <span class="text-dark fw-bold"><?php echo $p['no_telp'] ?: '-'; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-semibold small">Pekerjaan</span>
                    <span class="text-dark fw-bold"><?php echo $p['pekerjaan'] ?: '-'; ?></span>
                </div>
                <div class="mt-3 pt-3 border-top border-light">
                    <span class="text-muted fw-semibold small d-block mb-1">Alamat</span>
                    <p class="text-dark fw-bold mb-0" style="font-size: 0.9rem; line-height: 1.5;"><?php echo $p['alamat'] ?: '-'; ?></p>
                </div>
            </div>

            <div class="mt-5 d-grid">
                <a href="<?php echo site_url('pasien/ubah/'.$p['id_pasien']); ?>" class="btn btn-outline-primary fw-bold" style="border-radius: 12px; border-width: 2px;">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel h-100 shadow-sm border-0" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark-teal mb-0">RIWAYAT REKAM MEDIS</h4>
                <a href="<?php echo site_url('rekam_medis/tambah?id_pasien='.$p['id_pasien']); ?>" class="btn btn-primary btn-sm px-3 fw-bold" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">
                    <i class="fas fa-plus me-1"></i> Input RM Baru
                </a>
            </div>

            <?php if(empty($histori)): ?>
                <div class="text-center py-5 opacity-50">
                    <i class="fas fa-folder-open fs-1 d-block mb-3"></i>
                    <p class="fw-semibold">Belum ada riwayat kunjungan.</p>
                </div>
            <?php else: ?>
                <div class="activity-list mt-2">
                    <?php foreach($histori as $h): ?>
                        <div class="activity-item pb-4 mb-3 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="date fs-6"><?php echo date('d M Y', strtotime($h['tanggal_kunjungan'])); ?></span>
                                <span class="badge bg-light text-primary border border-primary-subtle"><?php echo $h['tindakan'] ?: 'Pemeriksaan'; ?></span>
                            </div>
                            <div class="ps-1">
                                <div class="mb-2">
                                    <span class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 0.65rem;">Keluhan</span>
                                    <p class="text-dark fw-bold mb-0"><?php echo $h['keluhan_utama']; ?></p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <span class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 0.65rem;">Diagnosis</span>
                                        <p class="text-dark mb-0 pe-3" style="font-size: 0.85rem;"><?php echo $h['diagnosis'] ?: '-'; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted fw-bold d-block text-uppercase mb-1" style="font-size: 0.65rem;">Pemeriksaan</span>
                                        <p class="text-dark mb-0" style="font-size: 0.85rem;"><?php echo $h['hasil_pemeriksaan'] ?: '-'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.bg-teal-soft { background-color: #f0fdfa; }
.text-teal { color: #0d9488; }
</style>
