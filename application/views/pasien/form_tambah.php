<div class="row mb-5">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('pasien'); ?>" class="text-decoration-none">Data Pasien</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Pasien</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark-teal">TAMBAH PASIEN BARU</h3>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="panel">
            <form action="<?php echo site_url('pasien/simpan'); ?>" method="POST">
                <div class="row g-4">
                    <!-- Data Identitas -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nomor Rekam Medis</label>
                            <input type="text" name="nomor_rm" class="form-control bg-light border-0 fw-bold text-dark-teal" value="<?php echo $next_rm; ?>" readonly style="border-radius: 10px; padding: 12px 15px;">
                            <small class="text-muted">Dihasilkan otomatis oleh sistem.</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nama Lengkap Pasien</label>
                            <input type="text" name="nama" class="form-control border-light" required style="border-radius: 10px; padding: 12px 15px;" placeholder="Masukkan nama lengkap...">
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control border-light" style="border-radius: 10px; padding: 12px 15px;" placeholder="Contoh: Medan">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="birthDate" class="form-control border-light" style="border-radius: 10px; padding: 12px 15px;">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Usia</label>
                                <div class="input-group">
                                    <input type="number" name="usia" id="ageInput" class="form-control border-light" required style="border-radius: 10px 0 0 10px; padding: 12px 15px;" placeholder="0">
                                    <span class="input-group-text border-light bg-white" style="border-radius: 0 10px 10px 0;">Thn</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L" required>
                                        <label class="form-check-label" for="jkL">Laki-laki</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P">
                                        <label class="form-check-label" for="jkP">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Pekerjaan -->
                    <div class="col-md-6 border-start-md ps-md-5">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control border-light" style="border-radius: 10px; padding: 12px 15px;" placeholder="Contoh: Pegawai Negeri, Petani, dll">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nomor Telepon / WA</label>
                            <input type="text" name="no_telp" class="form-control border-light" style="border-radius: 10px; padding: 12px 15px;" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control border-light" rows="4" style="border-radius: 10px; padding: 12px 15px;" placeholder="Masukkan alamat domisili..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top border-light d-flex justify-content-end gap-2">
                    <a href="<?php echo site_url('pasien'); ?>" class="btn btn-light px-5 py-2 fw-semibold" style="border-radius: 10px; color: #64748b;">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto calculation of age from birthdate
document.getElementById('birthDate').addEventListener('change', function() {
    let today = new Date();
    let birthDate = new Date(this.value);
    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    document.getElementById('ageInput').value = age;
});
</script>
