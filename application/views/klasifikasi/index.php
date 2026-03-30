<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark-teal text-uppercase">Proses Klasifikasi C4.5</h3>
        <hr class="border-secondary mb-4">
    </div>
</div>

<div class="row g-4">
    <!-- 1. INFORMASI DATASET (Gambar IV.12 Top) -->
    <div class="col-12">
        <div class="panel border-0 shadow-sm" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-light border-bottom p-2 px-3">
                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem; color: #475569;">Informasi Dataset</h6>
            </div>
            <div class="p-3">
                <div class="row mb-2">
                    <div class="col-md-3 fw-semibold text-secondary">Nama Dataset</div>
                    <div class="col-md-9">: <?php echo $dataset['nama_dataset']; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3 fw-semibold text-secondary">Jumlah Record</div>
                    <div class="col-md-9">: <span class="badge bg-teal fw-bold" style="font-size: 0.9rem;"><?php echo $actual_record_count; ?></span> record aktif</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3 fw-semibold text-secondary">Jumlah Atribut</div>
                    <div class="col-md-9">: <?php echo count($atribut_list); ?> atribut + 1 Target</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3 fw-semibold text-secondary">Atribut</div>
                    <div class="col-md-9">: 
                        <?php 
                        $attr_names = array_column($atribut_list, 'nama_atribut');
                        echo implode(', ', $attr_names);
                        ?>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-3 fw-semibold text-secondary">Target</div>
                    <div class="col-md-9">: Tindakan_Perawatan (5 kelas)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. PENGATURAN PARAMETER (Gambar IV.12 Middle) -->
    <div class="col-12">
        <div class="panel border-0 shadow-sm" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
            <div class="bg-light border-bottom p-2 px-3">
                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem; color: #475569;">Pengaturan Parameter</h6>
            </div>
            <div class="p-3">
                <form id="formTraining">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-3 fw-semibold text-secondary">Pembagian Data</div>
                        <div class="col-md-6">
                            <input type="range" class="form-range custom-range" id="splitRange" name="split_ratio" min="50" max="95" value="80">
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-secondary">Data Latih:</span>
                                <input type="text" class="form-control border-secondary text-center fw-bold" id="splitVal" value="80" readonly>
                                <span class="input-group-text bg-white border-secondary">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3 fw-semibold text-secondary">Metode Seleksi Atribut</div>
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="metode" id="m1" value="gain" checked>
                                <label class="form-check-label" for="m1">Gain Ratio (C4.5 Standard)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="metode" id="m2" value="ig" disabled>
                                <label class="form-check-label opacity-50" for="m2">Information Gain</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3 fw-semibold text-secondary">Pruning (Pemangkasan)</div>
                        <div class="col-md-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pruning" checked>
                                <label class="form-check-label" for="pruning">Aktifkan Error-Based Pruning</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-3 fw-semibold text-secondary">Confidence Level</div>
                        <div class="col-md-3">
                            <input type="number" class="form-control border-secondary" value="0.25" step="0.05" style="border-radius:0;">
                            <small class="text-muted">(default: 0.25)</small>
                        </div>
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn btn-teal-solid px-5 fw-bold" id="btnProses" style="border-radius: 0;">
                            <i class="fas fa-play me-2"></i> MULAI PROSES
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. HASIL PROSES (Gambar IV.12 Bottom - Hidden by default) -->
    <div class="col-12" id="panelHasil" style="display: none;">
        <div class="panel border-0 shadow-sm" style="border-radius: 0; border: 2px solid var(--primary-teal) !important;">
            <div class="p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold text-dark-teal mb-2">HASIL PROSES KLASIFIKASI</h5>
                        <p class="text-muted mb-0" id="resSplitInfo">Total Data: 100. Digunakan Latih: 80 (80%), Uji: 20 (20%)</p>
                        <h2 class="fw-black mt-2 mb-0 text-dark">Akurasi : <span id="resAkurasi" class="text-success">00.0</span>%</h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?php echo site_url('klasifikasi/pohon'); ?>" id="btnPohon" class="btn btn-secondary px-4 py-2 fw-bold" style="border-radius: 0;">
                            LIHAT POHON KEPUTUSAN <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(255,255,255,0.8); z-index: 2000;">
        <div class="text-center">
            <div class="spinner-border text-teal mb-3" style="width: 3rem; height: 3rem;" role="status text-primary"></div>
            <h5 class="fw-bold text-dark-teal">Sedang memproses algoritma C4.5...</h5>
            <p class="text-muted">Menghitung Entropy & Gain...</p>
        </div>
    </div>
</div>

<style>
.btn-teal-solid {
    background-color: var(--primary-teal);
    color: white;
    border: none;
    transition: all 0.3s;
}
.btn-teal-solid:hover {
    background-color: #0d9488;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}
.fw-black { font-weight: 900; }
.custom-range::-webkit-slider-thumb { background: var(--primary-teal); }
.custom-range::-moz-range-thumb { background: var(--primary-teal); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const splitRange = document.getElementById('splitRange');
    const splitVal = document.getElementById('splitVal');
    const form = document.getElementById('formTraining');
    const loading = document.getElementById('loadingOverlay');
    const panelHasil = document.getElementById('panelHasil');

    splitRange.addEventListener('input', function() {
        splitVal.value = this.value;
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        loading.classList.remove('d-none');
        loading.classList.add('d-flex');
        panelHasil.style.display = 'none';

        const formData = new FormData(this);

        fetch('<?php echo site_url("klasifikasi/run_training"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                loading.classList.add('d-none');
                loading.classList.remove('d-flex');
                
                if(data.status === 'success') {
                    document.getElementById('resAkurasi').textContent = data.akurasi;
                    document.getElementById('resSplitInfo').textContent = data.split_info;
                    document.getElementById('btnPohon').href = '<?php echo site_url("klasifikasi/pohon/"); ?>' + data.id_model;
                    
                    panelHasil.style.display = 'block';
                    panelHasil.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Gagal: ' + data.message);
                }
            }, 1000);
        })
        .catch(error => {
            loading.classList.add('d-none');
            alert('Terjadi kesalahan sistem.');
        });
    });
});
</script>
