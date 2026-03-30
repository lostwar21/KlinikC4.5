<div class="row mb-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-dark-teal mb-0">DATA PASIEN</h3>
        <a href="<?php echo site_url('pasien/tambah'); ?>" class="btn btn-primary d-flex align-items-center px-4 py-2" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">
            <i class="fas fa-plus me-2"></i> Tambah
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted fw-semibold" style="font-size: 0.9rem;">Menampilkan data seluruh pasien terdaftar</div>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted">Cari:</span>
            <input type="text" id="searchInput" class="form-control form-control-sm border-0 bg-light" style="width: 250px; border-radius: 8px; padding: 10px 15px;" placeholder="Ketik nama atau nomor RM...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="pasienTable">
            <thead>
                <tr class="text-uppercase text-secondary" style="font-size: 0.75rem; letter-spacing: 1px; border-bottom: 2px solid #f1f5f9;">
                    <th class="ps-3 py-3" style="width: 50px;">No</th>
                    <th class="py-3">Nomor RM</th>
                    <th class="py-3">Nama Lengkap</th>
                    <th class="py-3 text-center">Usia</th>
                    <th class="py-3 text-center">JK</th>
                    <th class="py-3 text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($pasien)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada data pasien tersimpan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($pasien as $p): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-secondary"><?php echo $no++; ?></td>
                            <td><span class="badge bg-light text-dark fw-bold px-3 py-2" style="border-radius: 6px;"><?php echo $p['nomor_rm']; ?></span></td>
                            <td class="fw-semibold text-dark-teal"><?php echo $p['nama']; ?></td>
                            <td class="text-center"><?php echo $p['usia']; ?> Tahun</td>
                            <td class="text-center">
                                <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: <?php echo ($p['jenis_kelamin'] == 'L') ? '#e0f2fe' : '#fce7f3'; ?>; color: <?php echo ($p['jenis_kelamin'] == 'L') ? '#0369a1' : '#be185d'; ?>;">
                                    <?php echo $p['jenis_kelamin']; ?>
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="<?php echo site_url('pasien/detail/'.$p['id_pasien']); ?>" class="btn btn-sm btn-light border-0 me-1" data-bs-toggle="tooltip" title="Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <a href="<?php echo site_url('pasien/ubah/'.$p['id_pasien']); ?>" class="btn btn-sm btn-light border-0 me-1" data-bs-toggle="tooltip" title="Ubah">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <a href="#" onclick="confirmDelete(<?php echo $p['id_pasien']; ?>)" class="btn btn-sm btn-light border-0" data-bs-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Legend (Mockup style) -->
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-light">
        <div class="text-muted" style="font-size: 0.8rem;">
            Keterangan: 
            <i class="fas fa-eye text-primary ms-2 me-1"></i> Detail, 
            <i class="fas fa-edit text-warning ms-2 me-1"></i> Ubah, 
            <i class="fas fa-trash text-danger ms-2 me-1"></i> Hapus
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <li class="page-item active"><a class="page-link" href="#" style="background-color: var(--primary-teal); border: none;">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">&gt;</a></li>
            </ul>
        </nav>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data pasien ini?')) {
        window.location.href = "<?php echo site_url('pasien/hapus/'); ?>" + id;
    }
}

// Simple search filter
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#pasienTable tbody tr');
    
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
