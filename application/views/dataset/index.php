<div class="row mb-4">
    <div class="col-md-7">
        <h3 class="fw-black text-dark-teal mb-0 uppercase">DATASET MINING (C4.5)</h3>
        <p class="text-muted small">Kelola data latih untuk meningkatkan akurasi diagnosa otomatis.</p>
    </div>
    <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-2">
        <a href="<?php echo site_url('dataset/sync_from_rm'); ?>" class="btn btn-teal-solid px-3 fw-bold" style="border-radius:0;">
            <i class="fas fa-sync-alt me-2"></i> SINKRONISASI DATA RIIL
        </a>
        <span class="badge bg-light text-teal border border-teal-subtle px-3 py-2 fw-bold" style="font-size: 0.8rem;">
            Total: <?php echo count($rows); ?> Baris
        </span>
    </div>
</div>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:0;">
        <i class="fas fa-check-circle me-2"></i> <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if($this->session->flashdata('info')): ?>
    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius:0;">
        <i class="fas fa-info-circle me-2"></i> <?php echo $this->session->flashdata('info'); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="panel border shadow-sm bg-white" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-light border-bottom p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">TABEL DATA LATIH: <?php echo $dataset_info['nama_dataset']; ?></h6>
                <div class="small text-muted italic">Format: Kategorikal (String)</div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="datasetTable" style="font-size: 0.85rem;">
                    <thead class="bg-light border-bottom">
                        <tr class="text-uppercase text-secondary" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <th class="ps-3 py-3" style="width: 50px;">No</th>
                            <th class="py-3">Pasien</th>
                            <th class="py-3 bg-light-teal border-start">Usia</th>
                            <th class="py-3 bg-light-teal">JK</th>
                            <th class="py-3 bg-light-teal">Keluhan</th>
                            <th class="py-3 bg-light-teal border-end">Riwayat</th>
                            <th class="py-3 fw-bold text-center">Target</th>
                            <th class="py-3 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($rows)): ?>
                            <tr><td colspan="8" class="text-center py-5">Dataset kosong. Klik sinkronisasi untuk mengambil data rekam medis.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach($rows as $r): 
                                $attr = json_decode($r['nilai_atribut_json'], true);
                                ?>
                                <tr>
                                    <td class="ps-3 text-secondary"><?php echo $no++; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo $r['nama_pasien']; ?></div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">Asli: <?php echo $r['usia_asli']; ?>th / <?php echo $r['jk_asli']; ?></div>
                                    </td>
                                    <td class="bg-light-teal border-start"><?php echo $attr['Usia'] ?? '-'; ?></td>
                                    <td class="bg-light-teal"><?php echo $attr['Jenis_Kelamin'] ?? '-'; ?></td>
                                    <td class="bg-light-teal"><?php echo $attr['Keluhan_Utama'] ?? '-'; ?></td>
                                    <td class="bg-light-teal border-end"><?php echo $attr['Riwayat_Penyakit'] ?? '-'; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-dark px-2 py-1 fw-bold" style="border-radius:0; min-width: 40px;">
                                            <?php echo $r['kelas_target']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="<?php echo site_url('dataset/hapus/'.$r['id_data_latih']); ?>" class="btn btn-sm text-danger" onclick="return confirm('Hapus data ini dari dataset mining?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
            
            <div class="panel-footer p-3 bg-light border-top">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Data di atas adalah data mining yang digunakan oleh mesin C4.5 untuk membangun pohon keputusan.</small>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-teal { background-color: #f0fdfa; }
.text-teal { color: var(--primary-teal); }
.border-teal-subtle { border-color: #ccfbf1 !important; }
</style>
