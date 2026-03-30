<div class="row mb-5">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('pasien'); ?>" class="text-decoration-none">Data Pasien</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ubah Pasien</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark-teal">UBAH DATA PASIEN</h3>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="panel">
            <form action="<?php echo site_url('pasien/update'); ?>" method="POST">
                <input type="hidden" name="id_pasien" value="<?php echo $p['id_pasien']; ?>">
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nomor Rekam Medis</label>
                            <input type="text" class="form-control bg-light border-0 fw-bold text-muted" value="<?php echo $p['nomor_rm']; ?>" readonly style="border-radius: 10px; padding: 12px 15px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nama Lengkap Pasien</label>
                            <input type="text" name="nama" class="form-control border-light" value="<?php echo $p['nama']; ?>" required style="border-radius: 10px; padding: 12px 15px;">
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control border-light" value="<?php echo $p['tempat_lahir']; ?>" style="border-radius: 10px; padding: 12px 15px;">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="birthDate" class="form-control border-light" value="<?php echo $p['tanggal_lahir']; ?>" style="border-radius: 10px; padding: 12px 15px;">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Usia</label>
                                <input type="number" name="usia" id="ageInput" class="form-control border-light" value="<?php echo $p['usia']; ?>" required style="border-radius: 10px; padding: 12px 15px;">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" <?php echo ($p['jenis_kelamin'] == 'L') ? 'checked' : ''; ?> required> Laki-laki
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" <?php echo ($p['jenis_kelamin'] == 'P') ? 'checked' : ''; ?>> Perempuan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 border-start-md ps-md-5">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control border-light" value="<?php echo $p['pekerjaan']; ?>" style="border-radius: 10px; padding: 12px 15px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Nomor Telepon</label>
                            <input type="text" name="no_telp" class="form-control border-light" value="<?php echo $p['no_telp']; ?>" style="border-radius: 10px; padding: 12px 15px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary" style="font-size: 0.75rem;">Alamat</label>
                            <textarea name="alamat" class="form-control border-light" rows="4" style="border-radius: 10px; padding: 12px 15px;"><?php echo $p['alamat']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top border-light d-flex justify-content-end gap-2">
                    <a href="<?php echo site_url('pasien'); ?>" class="btn btn-light px-5 py-2 fw-semibold" style="border-radius: 10px;">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="border-radius: 10px; background-color: var(--primary-teal); border: none;">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
