<div class="row mb-4">
    <div class="col-12 text-center">
        <h3 class="fw-bold text-dark-teal text-uppercase">Uji Model Prediksi C4.5</h3>
        <p class="text-muted">Masukkan parameter pasien untuk mendapatkan saran tindakan medis otomatis.</p>
        <hr class="border-secondary mb-4 mx-auto" style="width: 100px; border-width: 3px !important; border-radius: 5px;">
    </div>
</div>

<div class="row justify-content-center">
    <!-- INPUT FORM -->
    <div class="col-md-5">
        <div class="panel border-0 shadow-sm bg-white" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-light border-bottom p-3">
                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem;">Input Data Uji Baru</h6>
            </div>
            <div class="p-4">
                <?php if(!$latest_model): ?>
                    <div class="alert alert-danger border-0" style="border-radius:0;">
                        <i class="fas fa-times-circle me-2"></i> Model belum tersedia. Silakan lakukan proses klasifikasi terlebih dahulu.
                    </div>
                <?php else: ?>
                    <form id="formUji">
                        <input type="hidden" name="id_model" value="<?php echo $latest_model['id_model']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Usia Pasien (Tahun)</label>
                            <input type="number" name="usia" class="form-control border-secondary" placeholder="Contoh: 25" required style="border-radius:0;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select border-secondary" required style="border-radius:0;">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Keluhan Utama</label>
                            <select name="keluhan_utama" class="form-select border-secondary" required style="border-radius:0;">
                                <option value="Karies Gigi">Karies Gigi</option>
                                <option value="Abses Gigi">Abses Gigi</option>
                                <option value="Gingivitis">Gingivitis</option>
                                <option value="Pulpitis">Pulpitis</option>
                                <option value="Persistensi">Persistensi</option>
                                <option value="Gigi Sensitif">Gigi Sensitif</option>
                                <option value="Karang Gigi">Karang Gigi</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Riwayat Penyakit</label>
                            <select name="riwayat_penyakit" class="form-select border-secondary" required style="border-radius:0;">
                                <option value="Tidak Ada">Tidak Ada</option>
                                <option value="Hipertensi">Hipertensi</option>
                                <option value="Diabetes">Diabetes</option>
                                <option value="Asma">Asma</option>
                                <option value="Alergi Obat">Alergi Obat</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-teal-solid w-100 py-2 fw-bold" style="border-radius:0;">
                            <i class="fas fa-stethoscope me-2"></i> DAPATKAN PREDIKSI
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RESULT DISPLAY -->
    <div class="col-md-5">
        <div class="panel border-0 shadow-sm bg-white h-100" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-light border-bottom p-3">
                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem;">Hasil Diagnosis & Tindakan</h6>
            </div>
            <div class="p-4 d-flex flex-column align-items-center justify-content-center text-center" id="resultPlaceholder">
                <i class="fas fa-brain fs-1 text-light-teal mb-3 opacity-25" style="font-size: 5rem !important;"></i>
                <p class="text-muted px-4">Hasil prediksi akan muncul di sini setelah Anda menekan tombol "Dapatkan Prediksi".</p>
            </div>
            
            <div class="p-4 d-none" id="resultCard">
                <div class="mb-4 border-bottom pb-3">
                    <p class="text-muted small text-uppercase mb-1">Mapping Kategori Atribut:</p>
                    <div id="katMapping" class="d-flex flex-wrap gap-2 mb-3">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <div class="text-center p-4 bg-light shadow-sm border border-secondary" style="border-radius: 0;">
                    <p class="text-muted text-uppercase fw-bold small mb-2">Saran Tindakan Medis (C4.5):</p>
                    <h2 class="fw-black text-dark-teal mb-0" id="predLabel">T1 - Penambalan</h2>
                    <hr class="my-3 border-secondary opacity-25">
                    <p class="mb-0 small text-secondary">Akurasi Model Terakhir: <span class="fw-bold"><?php echo isset($latest_model) ? number_format($latest_model['akurasi'], 2) : '0'; ?>%</span></p>
                </div>

                <div class="mt-4">
                    <button onclick="location.reload()" class="btn btn-outline-secondary btn-sm w-100" style="border-radius:0;">
                        <i class="fas fa-redo me-2"></i> UJI ULANG
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-teal-solid { background-color: var(--primary-teal); color: white; border: none; transition: all 0.3s; }
.btn-teal-solid:hover { background-color: #0d9488; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(20,184,166,0.3); }
.text-light-teal { color: var(--primary-teal); }
.fw-black { font-weight: 900; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUji');
    const placeholders = document.getElementById('resultPlaceholder');
    const results = document.getElementById('resultCard');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            fetch('<?php echo site_url("uji_model/prediksi"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    placeholders.classList.add('d-none');
                    results.classList.remove('d-none');

                    document.getElementById('predLabel').textContent = data.prediksi;
                    
                    const katDiv = document.getElementById('katMapping');
                    katDiv.innerHTML = '';
                    
                    Object.entries(data.input).forEach(([key, val]) => {
                        const span = document.createElement('span');
                        span.className = 'badge bg-teal-subtle text-teal border border-teal-subtle px-3 py-2';
                        span.style.cssText = 'background: #f0fdfa; color: #0d9488;';
                        span.innerHTML = `<strong>${key}:</strong> ${val}`;
                        katDiv.appendChild(span);
                    });
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Terjadi kesalahan sistem.'));
        });
    }
});
</script>
