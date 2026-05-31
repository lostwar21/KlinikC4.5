<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-dark-teal mb-0">HISTORI REKAM MEDIS</h3>
        <a href="<?php echo site_url('rekam_medis/tambah'); ?>" class="btn btn-primary d-flex align-items-center px-4 py-2" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">
            <i class="fas fa-plus me-2"></i> Input RM Baru
        </a>
    </div>
</div>

<!-- Filter Panel -->
<div class="panel mb-4">
    <div class="d-flex align-items-center mb-3">
        <div class="p-2 rounded-3 me-2" style="background-color: rgba(13, 148, 136, 0.1); color: var(--primary-teal);">
            <i class="fas fa-filter"></i>
        </div>
        <h5 class="fw-bold text-dark-teal mb-0" style="font-size: 1rem;">Filter Tanggal Berobat</h5>
    </div>
    <form method="GET" action="<?php echo site_url('rekam_medis'); ?>" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="tanggal_mulai" class="form-label small fw-semibold text-secondary mb-1">Tanggal Mulai</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary border-end-0" style="border-radius: 8px 0 0 8px;"><i class="far fa-calendar-alt"></i></span>
                <input type="date" class="form-control border-start-0" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo isset($tanggal_mulai) ? $tanggal_mulai : ''; ?>" style="border-radius: 0 8px 8px 0;">
            </div>
        </div>
        <div class="col-md-4">
            <label for="tanggal_selesai" class="form-label small fw-semibold text-secondary mb-1">Tanggal Selesai</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary border-end-0" style="border-radius: 8px 0 0 8px;"><i class="far fa-calendar-alt"></i></span>
                <input type="date" class="form-control border-start-0" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo isset($tanggal_selesai) ? $tanggal_selesai : ''; ?>" style="border-radius: 0 8px 8px 0;">
            </div>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-semibold d-flex align-items-center justify-content-center" style="border-radius: 8px; background-color: var(--primary-teal); border: none; height: 38px;">
                <i class="fas fa-search me-2"></i> Filter
            </button>
            <?php if(!empty($tanggal_mulai) || !empty($tanggal_selesai)): ?>
                <a href="<?php echo site_url('rekam_medis'); ?>" class="btn btn-light border py-2 fw-semibold d-flex align-items-center justify-content-center" style="border-radius: 8px; height: 38px;">
                    <i class="fas fa-sync-alt me-2"></i> Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Active Filter Status -->
<?php if(!empty($tanggal_mulai) || !empty($tanggal_selesai)): ?>
    <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between" style="border-radius: 12px; background-color: #e0f2f1; color: var(--dark-teal); font-size: 0.9rem;">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fs-5 me-2"></i>
            <span>
                Menampilkan data rekam medis dari tanggal 
                <strong>
                    <?php echo !empty($tanggal_mulai) ? date('d/m/Y', strtotime($tanggal_mulai)) : 'Awal'; ?>
                </strong> 
                s/d 
                <strong>
                    <?php echo !empty($tanggal_selesai) ? date('d/m/Y', strtotime($tanggal_selesai)) : 'Akhir'; ?>
                </strong> 
                (Ditemukan: <strong><?php echo count($histori); ?></strong> data)
            </span>
        </div>
        <a href="<?php echo site_url('rekam_medis'); ?>" class="btn-close" style="font-size: 0.8rem; filter: invert(34%) sepia(21%) saturate(2421%) hue-rotate(130deg) brightness(92%) contrast(85%);" aria-label="Close"></a>
    </div>
<?php endif; ?>


<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px; background-color: #d1fae5; color: #065f46;">
        <i class="fas fa-check-circle me-2"></i> <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="rmTable">
            <thead>
                <tr class="text-uppercase text-secondary" style="font-size: 0.75rem; letter-spacing: 1px; border-bottom: 2px solid #f1f5f9;">
                    <th class="ps-3 py-3" style="width: 50px;">No</th>
                    <th class="py-3">Tanggal</th>
                    <th class="py-3">Pasien</th>
                    <th class="py-3">Keluhan Utama</th>
                    <th class="py-3">Diagnosis</th>
                    <th class="py-3 text-center">Tindakan</th>
                    <th class="py-3 text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($histori)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-notes-medical fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada histori rekam medis.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($histori as $h): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-secondary"><?php echo $no++; ?></td>
                            <td class="fw-semibold"><?php echo date('d/m/Y', strtotime($h['tanggal_kunjungan'])); ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo $h['nama_pasien']; ?></div>
                                <div class="text-muted small"><?php echo $h['nomor_rm']; ?></div>
                            </td>
                            <td>
                                <div class="text-dark" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo $h['catatan'] ? $h['catatan'] : $h['keluhan_utama']; ?>">
                                    <?php echo $h['catatan'] ? $h['catatan'] : $h['keluhan_utama']; ?>
                                </div>
                                <div class="text-muted small">Riwayat: <?php echo $h['riwayat_penyakit']; ?></div>
                            </td>
                            <td class="text-muted" style="font-size: 0.9rem;"><?php echo $h['diagnosis'] ?: '-'; ?></td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="border-radius: 8px;">
                                    <?php echo $h['tindakan']; ?>
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?php echo site_url('pasien/detail/'.$h['id_pasien']); ?>" class="btn btn-sm btn-light border-0" data-bs-toggle="tooltip" title="Lihat Profil Pasien">
                                    <i class="fas fa-info-circle text-info"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
