<div class="row mb-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-dark-teal mb-0">HISTORI REKAM MEDIS</h3>
        <a href="<?php echo site_url('rekam_medis/tambah'); ?>" class="btn btn-primary d-flex align-items-center px-4 py-2" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">
            <i class="fas fa-plus me-2"></i> Input RM Baru
        </a>
    </div>
</div>

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
                            <td><?php echo $h['keluhan_utama']; ?></td>
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
