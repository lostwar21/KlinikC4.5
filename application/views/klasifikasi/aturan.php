<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('klasifikasi/proses'); ?>" class="text-decoration-none text-teal">Proses Klasifikasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Aturan Klasifikasi</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark-teal text-uppercase">Aturan Klasifikasi (Rule-Base)</h3>
        <hr class="border-secondary mb-4">
    </div>
</div>

<?php if(!$model): ?>
    <div class="alert alert-warning border-0 shadow-sm" style="border-radius:0;">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada model yang dilatih.
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="panel border-0 shadow-sm bg-white" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
                <div class="bg-light border-bottom p-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem;">Daftar Aturan Keputusan (IF-THEN)</h6>
                </div>
                <div class="p-4">
                    <p class="text-muted mb-4 small">Berikut adalah representasi logika pohon keputusan dalam bentuk aturan tekstual yang dapat dibaca manusia:</p>
                    
                    <div class="rules-list">
                        <?php
                        if (!function_exists('buat_aturan_teks')) {
                            function buat_aturan_teks($node, $jalur_saat_ini = []) {
                                $kumpulan_aturan = [];
                                
                                if ($node['type'] == 'leaf') {
                                    $teks_if = "<strong>IF</strong> " . implode(" <span class='text-teal fw-bold'>AND</span> ", $jalur_saat_ini);
                                    $teks_then = " <strong>THEN</strong> <span class='badge bg-dark px-3 py-2'>TINDAKAN: " . $node['label'] . "</span>";
                                    $kumpulan_aturan[] = $teks_if . $teks_then;
                                } else {
                                    if (isset($node['branches'])) {
                                        foreach ($node['branches'] as $nilai => $cabang) {
                                            $jalur_baru = $jalur_saat_ini;
                                            $jalur_baru[] = "<u>" . str_replace('_', ' ', $node['attribute']) . "</u> = '" . $nilai . "'";
                                            $kumpulan_aturan = array_merge($kumpulan_aturan, buat_aturan_teks($cabang, $jalur_baru));
                                        }
                                    }
                                }
                                return $kumpulan_aturan;
                            }
                        }
                        
                        $daftar_aturan = buat_aturan_teks($model['tree']);
                        
                        if (empty($daftar_aturan)) {
                            echo '<div class="text-center py-4 text-muted italic">Tidak ada aturan yang dihasilkan dari pohon ini.</div>';
                        } else {
                            foreach ($daftar_aturan as $no => $item_aturan): ?>
                                <div class="rule-item p-3 mb-2 border-start border-4 border-teal bg-light">
                                    <div class="d-flex align-items-start">
                                        <span class="badge bg-teal me-3 mt-1"><?php echo $no + 1; ?></span>
                                        <div class="rule-text text-dark" style="line-height: 1.8; font-size: 0.95rem;">
                                            <?php echo $item_aturan; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        } ?>
                    </div>
                </div>
                <div class="panel-footer p-3 bg-light border-top text-end">
                    <button onclick="window.print()" class="btn btn-secondary btn-sm px-4 fw-bold" style="border-radius:0;">
                        <i class="fas fa-print me-2"></i> CETAK ATURAN
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.rule-item { transition: all 0.2s; }
.rule-item:hover { background-color: #f0fdfa !important; transform: translateX(5px); }
.text-teal { color: var(--primary-teal); }
.bg-teal { background-color: var(--primary-teal); }
.border-teal { border-color: var(--primary-teal) !important; }
</style>
