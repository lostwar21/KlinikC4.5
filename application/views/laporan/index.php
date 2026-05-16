<!-- Tombol Cetak (Hanya Muncul di Layar) -->
<div class="row mb-4 py-3 border-bottom d-print-none">
    <div class="col-md-9">
        <h2 class="fw-black text-dark-teal">LAPORAN & STATISTIK SISTEM</h2>
        <p class="text-muted mb-0">Ringkasan performa algoritma C4.5 dan data klinis di dalam database.</p>
    </div>
    <div class="col-md-3 text-end d-flex align-items-center justify-content-end">
        <button onclick="window.print()" class="btn btn-secondary px-4 fw-bold" style="border-radius:0;">
            <i class="fas fa-print me-2"></i> CETAK LAPORAN (PDF)
        </button>
    </div>
</div>

<!-- ====================================================== -->
<!-- KOP SURAT (Hanya Muncul Saat Dicetak) -->
<!-- ====================================================== -->
<div class="d-none d-print-block">
    <div class="row align-items-center mb-0 border-bottom border-3 border-dark pb-3">
        <div class="col-2 text-center">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" style="width: 80px; filter: grayscale(1);">
        </div>
        <div class="col-10 text-center">
            <h2 class="fw-black mb-0" style="letter-spacing: -1px; line-height: 1;">RUMAH SAKIT GIGI DAN MULUT USU</h2>
            <p class="mb-0 small fw-bold text-teal">UNIVERSITAS SUMATERA UTARA</p>
            <p class="mb-0 x-small text-muted">Jl. Alumni No.2, Kampus USU, Medan | Telp: (061) 8211633</p>
        </div>
    </div>
    <div class="text-center my-4">
        <h4 class="fw-bold text-uppercase text-decoration-underline">LAPORAN HASIL KLASIFIKASI REKAM MEDIS</h4>
        <p class="small text-muted">Periode: <?php echo date('F Y'); ?> | Dicetak pada: <?php echo date('d/m/Y H:i'); ?></p>
    </div>
</div>

<!-- ROW 1: SUMMARY CARDS -->
<div class="row g-4 mb-5">
    <div class="col-md-3 col-6">
        <div class="p-4 bg-white border border-secondary text-center report-card">
            <p class="text-muted small fw-bold text-uppercase mb-1">Total Pasien</p>
            <h2 class="fw-black mb-0 text-dark-teal"><?php echo $total_pasien; ?></h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-4 bg-white border border-secondary text-center report-card">
            <p class="text-muted small fw-bold text-uppercase mb-1">Total Rekam Medis</p>
            <h2 class="fw-black mb-0 text-dark-teal"><?php echo $total_rm; ?></h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-4 bg-white border border-secondary text-center report-card">
            <p class="text-muted small fw-bold text-uppercase mb-1">Data Latih</p>
            <h2 class="fw-black mb-0 text-dark-teal"><?php echo $total_latih; ?></h2>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-4 bg-white border border-secondary text-center report-card">
            <p class="text-muted small fw-bold text-uppercase mb-1">Akurasi C4.5</p>
            <h2 class="fw-black mb-0 text-dark-teal"><?php echo isset($best_model) ? number_format($best_model['akurasi'], 2) : '0'; ?>%</h2>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- CHART: DISTRIBUSI TINDAKAN -->
    <div class="col-md-6 d-print-none">
        <div class="panel border bg-white p-4" style="border-radius: 0;">
            <h6 class="fw-bold text-uppercase border-bottom pb-3 mb-4" style="font-size:0.8rem;">Visualisasi Distribusi</h6>
            <div style="height: 300px;">
                <canvas id="chartTindakan"></canvas>
            </div>
        </div>
    </div>

    <!-- TABLE: RIWAYAT MODEL -->
    <div class="col-md-6 col-print-12">
        <div class="panel border bg-white p-4" style="border-radius: 0;">
            <h6 class="fw-bold text-uppercase border-bottom pb-3 mb-4" style="font-size:0.8rem;">Log Klasifikasi Terakhir</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="bg-light">
                        <tr class="small text-uppercase">
                            <th>Nama Model</th>
                            <th class="text-center">Akurasi</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($riwayat_model)): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada riwayat model.</td></tr>
                        <?php else: foreach($riwayat_model as $m): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo $m['nama_model']; ?></td>
                                <td class="text-center fw-bold text-dark"><?php echo number_format($m['akurasi'], 2); ?>%</td>
                                <td class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($m['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ====================================================== -->
<!-- TANDA TANGAN (Hanya Muncul Saat Dicetak) -->
<!-- ====================================================== -->
<div class="d-none d-print-block mt-5 pt-5">
    <div class="row">
        <div class="col-8"></div>
        <div class="col-4 text-center">
            <p class="mb-0">Medan, <?php echo date('d F Y'); ?></p>
            <p class="fw-bold mb-5 pb-5">Direktur RSGM USU,</p>
            <p class="fw-bold mb-0">( ............................................ )</p>
            <p class="small text-muted">SIP. 19880101 201501 1 001</p>
        </div>
    </div>
</div>

<style>
.fw-black { font-weight: 900; }
.text-dark-teal { color: #0f172a; }
.report-card { border-radius: 0; box-shadow: none; }

@media print {
    body { background: white !important; font-family: "Times New Roman", Times, serif; }
    .sidebar, .navbar, .d-print-none { display: none !important; }
    .container-fluid, .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
    .col-print-12 { width: 100% !important; }
    .panel { border: 1px solid #000 !important; }
    .card, .panel { box-shadow: none !important; }
    @page { margin: 2cm; }
}
</style>

<!-- Chart.JS Load (Dikelola agar tidak merusak print) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('chartTindakan');
    if(canvas) {
        const ctx = canvas.getContext('2d');
        const dataTindakan = {
            labels: [<?php echo "'" . implode("','", array_column($distribusi_tindakan, 'label')) . "'"; ?>],
            datasets: [{
                data: [<?php echo implode(",", array_column($distribusi_tindakan, 'value')); ?>],
                backgroundColor: ['#14b8a6', '#0f172a', '#334155', '#64748b', '#94a3b8', '#0284c7'],
                borderWidth: 0
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: dataTindakan,
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                },
                maintainAspectRatio: false,
                cutout: '70%'
            }
        });
    }
});
</script>

