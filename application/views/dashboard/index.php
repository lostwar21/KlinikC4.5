<div class="row mb-5">
    <div class="col-12">
        <h3 class="fw-bold text-dark-teal" style="font-size: 1.5rem;">Selamat Datang, <?php echo $this->session->userdata('nama_lengkap') ?: 'Pengguna'; ?></h3>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: KPIs and Chart -->
    <div class="col-lg-8">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="kpi-card wave-bg">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="meta text-uppercase">Total Pasien</div>
                        <span class="badge-teal">Terdaftar</span>
                    </div>
                    <span class="value"><?php echo number_format($total_pasien); ?></span>
                    <div class="text-muted mt-2" style="font-size: 0.75rem;">Data RSGM USU</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kpi-card wave-bg">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="meta text-uppercase">Total Rekam Medis</div>
                        <span class="badge-teal">Tercatat</span>
                    </div>
                    <span class="value"><?php echo number_format($total_rm); ?></span>
                    <div class="text-muted mt-2" style="font-size: 0.75rem;">Data Kunjungan</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kpi-card wave-bg">
                    <div class="meta text-uppercase mb-1">Model Aktif</div>
                    <span class="value" style="font-size: 1.8rem;">
                        <?php echo isset($active_model['nama_model']) ? "ID-".$active_model['id_model']."-".date('y') : 'C4.5-BASIC'; ?>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kpi-card wave-bg">
                    <div class="meta text-uppercase mb-1">Akurasi Model</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="value"><?php echo isset($active_model['akurasi']) ? number_format($active_model['akurasi'], 1) . '%' : 'Belum ada'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <h4 class="panel-title">GRAFIK DISTRIBUSI KELUHAN</h4>
            <div style="height: 300px;">
                <canvas id="complaintChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activities -->
    <div class="col-lg-4">
        <div class="panel h-100">
            <h4 class="panel-title">AKTIVITAS TERBARU:</h4>
            <div class="activity-list">
                <?php if (empty($recent_activities)): ?>
                    <!-- Fallback items matching UI mockup -->
                    <div class="activity-item">
                        <span class="date">24/04/2025</span>
                        <div class="d-inline">Pasien: Budi Santoso</div>
                        <div class="text-muted ms-4" style="font-size: 0.8rem;">Rekam Medis: RM-001</div>
                    </div>
                    <div class="activity-item">
                        <span class="date">24/04/2025</span>
                        <div class="d-inline">Pasien: Siti Aminah</div>
                        <div class="text-muted ms-4" style="font-size: 0.8rem;">Rekam Medis: RM-002</div>
                    </div>
                    <div class="activity-item">
                        <span class="date">24/04/2025</span>
                        <div class="d-inline">Pasien: Andi Wijaya</div>
                        <div class="text-muted ms-4" style="font-size: 0.8rem;">Rekam Medis: RM-003</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_activities as $log): ?>
                        <div class="activity-item">
                            <span class="date"><?php echo date('d/m/Y', strtotime($log['created_at'])); ?></span>
                            <div class="d-inline"><?php echo $log['aktivitas']; ?></div>
                            <div class="text-muted ms-4" style="font-size: 0.8rem;">Endpoint: <?php echo $log['endpoint']; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script Integration -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('complaintChart').getContext('2d');
    
    // Create Gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(13, 148, 136, 0.4)');
    gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $chart_labels; ?>,
            datasets: [{
                label: 'Jumlah Pasien',
                data: <?php echo $chart_values; ?>,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#0d9488',
                borderWidth: 2,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d9488',
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
});
</script>
