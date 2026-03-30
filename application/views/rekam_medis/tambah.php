<div class="row mb-5">
    <div class="col-12">
        <h3 class="fw-bold text-dark-teal text-uppercase">Input Rekam Medis</h3>
        <hr class="border-secondary mb-4">
    </div>
</div>

<form action="<?php echo site_url('rekam_medis/simpan'); ?>" method="POST" id="rmForm">
    <!-- Panel Pilih Pasien -->
    <div class="panel border mb-3" style="border-radius: 0; padding: 15px 20px;">
        <div class="row align-items-center">
            <div class="col-md-2">
                <label class="form-label fw-bold mb-0">Pilih Pasien :</label>
            </div>
            <div class="col-md-8">
                <select name="id_pasien" id="selectPasien" class="form-select border-secondary" required style="border-radius: 0;">
                    <option value="">-- xxxxx - xxxxx --</option>
                    <?php foreach($pasien as $p): ?>
                        <option value="<?php echo $p['id_pasien']; ?>" <?php echo (isset($selected_id) && $selected_id == $p['id_pasien']) ? 'selected' : ''; ?>>
                            <?php echo $p['nomor_rm'] . ' - ' . $p['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary w-100" style="border-radius: 0;" id="btnCari">Cari</button>
            </div>
        </div>
    </div>

    <!-- Panel Data Pasien Terpilih -->
    <div class="panel border mb-4" style="border-radius: 0; padding: 0;">
        <div class="bg-light border-bottom p-2 px-3">
            <h6 class="mb-0 fw-bold text-uppercase">Data Pasien Terpilih</h6>
        </div>
        
        <div class="p-4" id="pasienInfoContent" style="display: none;">
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Nomor RM</div>
                <div class="col-md-9" id="view_rm">: xxxxx</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Nama</div>
                <div class="col-md-9" id="view_nama">: xxxxx</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Usia</div>
                <div class="col-md-9" id="view_usia">: 99 tahun</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Jenis Kelamin</div>
                <div class="col-md-9" id="view_jk">: xxxxxxxxx</div>
            </div>
        </div>
        <!-- Placeholder info -->
        <div class="p-4 text-center text-muted" id="pasienInfoEmpty">
            Pilih atau Cari pasien untuk menampilkan data...
        </div>
    </div>

    <!-- Panel Form Klinis -->
    <div class="panel border mb-3" style="border-radius: 0; padding: 25px;">
        
        <div class="row mb-3 align-items-center">
            <div class="col-md-3">
                <label class="fw-bold">Tanggal Kunjungan :</label>
            </div>
            <div class="col-md-9">
                <input type="date" name="tanggal_kunjungan" class="form-control border-secondary" value="<?php echo date('Y-m-d'); ?>" required style="border-radius: 0;">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-3">
                <label class="fw-bold">Keluhan Utama :</label>
            </div>
            <div class="col-md-9">
                <input type="text" name="keluhan_utama" class="form-control border-secondary" placeholder="xxxxxxxxxxxxxxxxxxxxxxx" required style="border-radius: 0;">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-3">
                <label class="fw-bold">Riwayat Penyakit :</label>
            </div>
            <div class="col-md-9 d-flex align-items-center gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="riwayat_penyakit" value="Tidak Ada" id="rw_tidak" checked>
                    <label class="form-check-label" for="rw_tidak">Tidak Ada</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="riwayat_penyakit" value="Ada" id="rw_ada">
                    <label class="form-check-label" for="rw_ada">Ada riwayat penyerta</label>
                </div>
            </div>
        </div>

        <div class="row mb-3 align-items-center" id="sebutkan_row">
            <div class="col-md-3">
                <label class="fw-bold opacity-75">Jika Ada, sebutkan :</label>
            </div>
            <div class="col-md-9">
                <input type="text" name="riwayat_sebutkan" id="riwayat_sebutkan" class="form-control border-secondary" placeholder="Hipertensi, Diabetes..." style="border-radius: 0;" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="fw-bold">Hasil Pemeriksaan :</label>
            </div>
            <div class="col-md-9">
                <textarea name="hasil_pemeriksaan" class="form-control border-secondary" rows="2" style="border-radius: 0;"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="fw-bold">Diagnosis :</label>
            </div>
            <div class="col-md-9">
                <input type="text" name="diagnosis" class="form-control border-secondary mb-2" placeholder="Nama diagnosis..." style="border-radius: 0;">
                
                <div class="mt-2 p-2 border border-secondary" style="border-radius: 0;">
                    <label class="mb-2 fw-bold text-muted" style="font-size:0.8rem;">Klasifikasi Tindakan C4.5 (Pilih kriteria manual untuk data latih):</label>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tindakan" value="T1 - Penambalan" id="t1" required>
                                <label class="form-check-label" for="t1">[ ] Penambalan (T1)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tindakan" value="T2 - Pencabutan" id="t2">
                                <label class="form-check-label" for="t2">[ ] Pencabutan (T2)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tindakan" value="T3 - Pembersihan Karang" id="t3">
                                <label class="form-check-label" for="t3">[ ] Pembersihan (T3)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tindakan" value="T4 - Medikasi" id="t4">
                                <label class="form-check-label" for="t4">[ ] Medikasi (T4)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tindakan" value="T5 - Konservasi" id="t5">
                                <label class="form-check-label" for="t5">[ ] Konservasi (T5)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 align-items-start">
            <div class="col-md-3">
                <label class="fw-bold">Catatan :</label>
            </div>
            <div class="col-md-9">
                <textarea name="catatan" class="form-control border-secondary" rows="2" style="border-radius: 0;"></textarea>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-secondary px-4 me-2 fw-bold" style="border-radius: 0;">SIMPAN</button>
                <a href="<?php echo site_url('rekam_medis'); ?>" class="btn btn-outline-secondary px-4 fw-bold" style="border-radius: 0;">BATAL</a>
            </div>
        </div>

    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('selectPasien');
    const btnCari = document.getElementById('btnCari');
    
    // Auto-trigger if pre-selected
    if(select.value) {
        getPasienInfo(select.value);
    }

    // Trigger on Dropdown change or button click
    btnCari.addEventListener('click', function() {
        if(select.value) getPasienInfo(select.value);
    });
    
    select.addEventListener('change', function() {
        if(this.value) {
            getPasienInfo(this.value);
        } else {
            document.getElementById('pasienInfoEmpty').style.display = 'block';
            document.getElementById('pasienInfoContent').style.display = 'none';
        }
    });

    // Toggle Sebutkan field
    const rwAda = document.getElementById('rw_ada');
    const rwTidak = document.getElementById('rw_tidak');
    const rwSebutkan = document.getElementById('riwayat_sebutkan');
    
    rwAda.addEventListener('change', function() {
        if(this.checked) {
            rwSebutkan.disabled = false;
            rwSebutkan.focus();
        }
    });
    
    rwTidak.addEventListener('change', function() {
        if(this.checked) {
            rwSebutkan.disabled = true;
            rwSebutkan.value = '';
        }
    });

    function getPasienInfo(id) {
        fetch('<?php echo site_url("rekam_medis/get_pasien_ajax/"); ?>' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('view_nama').textContent = ': ' + data.nama;
                document.getElementById('view_rm').textContent = ': ' + data.nomor_rm;
                document.getElementById('view_usia').textContent = ': ' + data.usia + ' tahun';
                
                let jkFull = (data.jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan';
                document.getElementById('view_jk').textContent = ': ' + jkFull;
                
                document.getElementById('pasienInfoEmpty').style.display = 'none';
                document.getElementById('pasienInfoContent').style.display = 'block';
            });
    }
});
</script>
