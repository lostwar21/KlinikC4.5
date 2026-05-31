<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo site_url('klasifikasi/proses'); ?>" class="text-decoration-none text-teal">Proses Klasifikasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pohon Hasil</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-bold text-dark-teal text-uppercase mb-0">Pohon Keputusan - Hasil Klasifikasi</h3>
            <button onclick="downloadTreeImage()" class="btn btn-teal-solid fw-bold shadow-sm" style="border-radius:0;">
                <i class="fas fa-download me-2"></i> UNDUH GAMBAR (PNG)
            </button>
        </div>
        <hr class="border-secondary mb-4 mt-3">
    </div>
</div>

<?php if(!$model): ?>
    <div class="alert alert-warning border-0 shadow-sm" style="border-radius:0;">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada model yang dilatih. Silakan jalankan proses klasifikasi terlebih dahulu.
    </div>
<?php else: ?>
    <div class="row g-4">
        <!-- HEADER INFO -->
        <div class="col-12">
            <div class="panel border p-3 bg-light-teal" style="border-radius:0; border: 1px solid var(--primary-teal) !important;">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <span class="fw-bold text-dark me-4">Model: <span class="text-teal"><?php echo $model['nama_model']; ?></span></span>
                        <span class="fw-bold text-dark me-4">Akurasi: <span class="text-teal"><?php echo number_format($model['akurasi'], 2); ?>%</span></span>
                        <span class="fw-bold text-dark">Tanggal: <span class="text-teal"><?php echo date('d/m/Y', strtotime($model['created_at'])); ?></span></span>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="<?php echo site_url('klasifikasi/aturan/'.$model['id_model']); ?>" class="btn btn-outline-teal btn-sm px-3 fw-bold bg-white" style="border-radius:0;">
                            ATURAN (RULES) <i class="fas fa-list ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- VISUALISASI POHON CANVAS -->
        <div class="col-12">
            <div class="panel border shadow-sm bg-white" style="border-radius: 0; border: 1px solid #dee2e6 !important;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light flex-wrap">
                    <h6 class="fw-bold text-uppercase mb-0" style="font-size: 0.8rem; letter-spacing: 1px; color: #475569;">
                        <i class="fas fa-project-diagram me-2 text-teal"></i> Canvas Visualisasi Pohon Keputusan
                    </h6>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                        <span class="text-muted small me-2"><i class="fas fa-search me-1"></i> Kontrol Pembesaran:</span>
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary bg-white" id="btnZoomOut" title="Zoom Out">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary bg-white fw-bold disabled" style="width: 60px; color:#475569;" id="zoomLevelDisplay">
                                100%
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary bg-white" id="btnZoomReset" title="Reset Zoom">
                                <i class="fas fa-compress"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary bg-white" id="btnZoomIn" title="Zoom In">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- SCROLLABLE & EXPORTABLE WRAPPER -->
                <div class="p-0 position-relative" style="overflow: auto; min-height: 500px; cursor: grab; background-color: #f8fafc;" id="scrollContainer">
                    <div id="exportArea" style="min-width: max-content; padding: 40px; background-color: #f8fafc; transition: transform 0.2s ease-out; transform-origin: top center;">
                        
                        <!-- TITLE FOR EXPORTED IMAGE ONLY -->
                        <div class="text-center mb-5 d-none" id="exportTitle">
                            <h4 class="fw-bold" style="color: #0f172a; font-size: 2rem;">POHON KEPUTUSAN C4.5</h4>
                            <p class="text-muted fw-semibold" style="font-size: 1.2rem;">Model: <?php echo $model['nama_model']; ?> | Akurasi: <?php echo number_format($model['akurasi'], 2); ?>%</p>
                            <hr style="width: 300px; margin: 25px auto; border-width: 3px; border-color: var(--primary-teal);">
                        </div>

                        <div class="tree-container">
                            <?php
                            if (!function_exists('render_tree_node')) {
                                function render_tree_node($node, $parent_val = null) {
                                    if ($node['type'] == 'leaf') {
                                        $patients_json = isset($node['patients']) ? htmlspecialchars(json_encode($node['patients']), ENT_QUOTES, 'UTF-8') : '[]';
                                        echo '<li>';
                                        if ($parent_val !== null) echo '<span class="branch-label">' . $parent_val . '</span>';
                                        echo '<div class="node leaf-node shadow-sm" style="cursor:pointer;" onclick="showPatients(this)" data-patients="'.$patients_json.'">';
                                        echo '<span class="leaf-label">TINDAKAN:</span>';
                                        echo '<strong class="text-white d-block">' . $node['label'] . '</strong>';
                                        echo '<small class="d-block opacity-75 mt-1">(' . $node['count'] . ' kasus)</small>';
                                        echo '<div class="mt-2 text-info small" style="font-size:0.7rem;"><i class="fas fa-search me-1"></i> Lihat Pasien</div>';
                                        echo '</div>';
                                        echo '</li>';
                                    } else {
                                        $patients_json = isset($node['patients']) ? htmlspecialchars(json_encode($node['patients']), ENT_QUOTES, 'UTF-8') : '[]';
                                        echo '<li>';
                                        if ($parent_val !== null) echo '<span class="branch-label">' . $parent_val . '</span>';
                                        echo '<div class="node internal-node shadow-sm" style="cursor:pointer;" onclick="showPatients(this)" data-patients="'.$patients_json.'">';
                                        echo '<span class="attr-label">Atribut:</span>';
                                        echo '<strong>' . str_replace('_', ' ', $node['attribute']) . '</strong>';
                                        echo '<div class="mt-2 text-teal small" style="font-size:0.7rem;"><i class="fas fa-search me-1"></i> Lihat ' . $node['count'] . ' Pasien</div>';
                                        echo '</div>';
                                        if (isset($node['branches'])) {
                                            echo '<ul>';
                                            foreach ($node['branches'] as $val => $branch) {
                                                render_tree_node($branch, $val);
                                            }
                                            echo '</ul>';
                                        }
                                        echo '</li>';
                                    }
                                }
                            }
                            ?>
                            
                            <div class="tree d-inline-block">
                                <ul>
                                    <?php render_tree_node($model['tree']); ?>
                                </ul>
                            </div>
                        </div>

                        <!-- KETERANGAN / LEGENDA -->
                        <div class="mt-5 pt-4 border-top" style="max-width: 600px; margin: 0 auto; text-align: center;">
                            <div class="d-flex flex-wrap justify-content-center gap-4 text-muted border border-secondary p-3 bg-white" style="font-size: 0.85rem; border-radius: 8px;">
                                <div><i class="fas fa-square text-teal me-2 fs-5 align-middle"></i> <span class="fw-bold align-middle">Simpul Kriteria</span></div>
                                <div><i class="fas fa-square text-dark me-2 fs-5 align-middle" style="color: #1e293b !important;"></i> <span class="fw-bold align-middle">Simpul Tindakan (Daun)</span></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<style>
/* 🌲 CSS Tree Structure Refined for Wide Overflow */
.tree-container { padding: 20px 0; text-align: center; }
.tree { display: inline-flex; justify-content: center; } 
.tree ul { padding-top: 35px; position: relative; transition: all 0.5s; display: flex; justify-content: center; margin: 0; padding-left:0; }
.tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 35px 10px 0 10px; transition: all 0.5s; }

/* The lines connecting nodes */
.tree li::before, .tree li::after{ content: ''; position: absolute; top: 0; right: 50%; border-top: 3px solid #94a3b8; width: 50%; height: 35px; }
.tree li::after{ right: auto; left: 50%; border-left: 3px solid #94a3b8; }
.tree li:only-child::after, .tree li:only-child::before { display: none; }
.tree li:only-child{ padding-top: 0; }
.tree li:first-child::before, .tree li:last-child::after{ border: 0 none; }
.tree li:last-child::before{ border-right: 3px solid #94a3b8; border-radius: 0 6px 0 0; }
.tree li:first-child::after{ border-radius: 6px 0 0 0; }
.tree ul ul::before{ content: ''; position: absolute; top: 0; left: 50%; border-left: 3px solid #94a3b8; width: 0; height: 35px; }

/* Node Styling */
.tree .node { border: 2.5px solid #94a3b8; padding: 15px 25px; color: #334155; font-size: 1rem; display: inline-block; border-radius: 8px; transition: all 0.3s; background: white; position: relative; z-index: 10; min-width: 170px; }
.tree .internal-node { background: #f0fdfa; border-color: var(--primary-teal); border-width: 3px; }
.tree .internal-node strong { color: var(--primary-teal); font-size: 1.15rem; display: block; margin-top: 6px; }
.tree .leaf-node { background: #1e293b; border-color: #0f172a; color: white; border-width: 3px; }
.tree .leaf-node strong { font-size: 1.15rem; margin-top: 5px; }

/* Branch Labels (The Edge Value) */
.tree .branch-label {
    display: block; position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
    background: #ffffff; padding: 4px 14px; font-size: 0.85rem; font-weight: 800;
    border-radius: 20px; color: #0f172a; z-index: 11; border: 2px solid #cbd5e1;
    white-space: nowrap; box-shadow: 0 3px 6px rgba(0,0,0,0.08); text-transform: capitalize;
}

.attr-label, .leaf-label { font-size: 0.75rem; text-transform: uppercase; display: block; opacity: 0.8; font-weight: 800; letter-spacing: 0.5px; }
.bg-light-teal { background-color: #f0fdfa; }
.btn-teal-solid { background-color: var(--primary-teal); color: white; border: none; transition: all 0.3s; }
.btn-teal-solid:hover { background-color: #0d9488; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(20,184,166,0.3); color: white; }
.btn-outline-teal { border: 1px solid var(--primary-teal); color: var(--primary-teal); transition: all 0.3s; }
.btn-outline-teal:hover { background-color: #f0fdfa; color: var(--primary-teal); }

/* Custom Scroll Canvas */
#scrollContainer::-webkit-scrollbar { height: 16px; width: 16px; }
#scrollContainer::-webkit-scrollbar-track { background: #e2e8f0; border: 4px solid #f8fafc; }
#scrollContainer::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; border: 4px solid #f8fafc; }
#scrollContainer::-webkit-scrollbar-thumb:hover { background: #64748b; }
</style>

<!-- Load html2canvas for image rendering -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let currentZoom = 1.0;
const ZOOM_STEP = 0.15;
const MIN_ZOOM = 0.4;
const MAX_ZOOM = 2.0;

document.addEventListener('DOMContentLoaded', function() {
    // Enable Grab to Scroll functionality
    const slider = document.getElementById('scrollContainer');
    let isDown = false;
    let startX;
    let startY;
    let scrollLeft;
    let scrollTop;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.style.cursor = 'grabbing';
        startX = e.pageX - slider.offsetLeft;
        startY = e.pageY - slider.offsetTop;
        scrollLeft = slider.scrollLeft;
        scrollTop = slider.scrollTop;
    });
    slider.addEventListener('mouseleave', () => { isDown = false; slider.style.cursor = 'grab'; });
    slider.addEventListener('mouseup', () => { isDown = false; slider.style.cursor = 'grab'; });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const y = e.pageY - slider.offsetTop;
        const walkX = (x - startX) * 1.5;
        const walkY = (y - startY) * 1.5;
        slider.scrollLeft = scrollLeft - walkX;
        slider.scrollTop = scrollTop - walkY;
    });

    // Zoom Controls Logic
    const exportArea = document.getElementById('exportArea');
    const zoomDisplay = document.getElementById('zoomLevelDisplay');

    function applyZoom() {
        exportArea.style.transform = `scale(${currentZoom})`;
        zoomDisplay.textContent = Math.round(currentZoom * 100) + '%';
        
        // Adjust container padding based on zoom to ensure it doesn't get cut off when scaled down
        if (currentZoom < 1) {
             exportArea.style.transformOrigin = "top center";
        } else {
             exportArea.style.transformOrigin = "top center";
        }
    }

    document.getElementById('btnZoomIn').addEventListener('click', () => {
        if(currentZoom < MAX_ZOOM) {
            currentZoom += ZOOM_STEP;
            applyZoom();
        }
    });

    document.getElementById('btnZoomOut').addEventListener('click', () => {
        if(currentZoom > MIN_ZOOM) {
            currentZoom -= ZOOM_STEP;
            applyZoom();
        }
    });

    document.getElementById('btnZoomReset').addEventListener('click', () => {
        currentZoom = 1.0;
        applyZoom();
        
        // Reset scroll position to center-top perfectly
        slider.scrollLeft = (exportArea.scrollWidth - slider.clientWidth) / 2;
        slider.scrollTop = 0;
    });
    
    // Initial center scroll
    setTimeout(() => {
        slider.scrollLeft = (exportArea.scrollWidth - slider.clientWidth) / 2;
    }, 500);
});

// Download Function using html2canvas
function downloadTreeImage() {
    const exportArea = document.getElementById('exportArea');
    const exportTitle = document.getElementById('exportTitle');
    const btn = event.currentTarget;
    
    // 1. Save original zoom state and UI state
    const originalZoom = currentZoom;
    const originalBtnHtml = btn.innerHTML;
    
    // 2. Temporarily set to 100% zoom for highest quality un-warped export
    currentZoom = 1.0;
    exportArea.style.transform = `scale(1.0)`;
    exportArea.style.backgroundColor = "#ffffff";
    
    // 3. UI Loading state
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> MEMROSES...';
    btn.disabled = true;

    // 4. Show title specifically for export only
    exportTitle.classList.remove('d-none');
    
    // Small timeout to allow DOM to render scale(1) before capturing
    setTimeout(() => {
        // Use html2canvas
        html2canvas(exportArea, {
            scale: 2, // Retains Retina high-res quality
            backgroundColor: "#ffffff",
            logging: false,
            useCORS: true,
            windowWidth: exportArea.scrollWidth, // Ensures full width is captured
            windowHeight: exportArea.scrollHeight
        }).then(canvas => {
            // Convert canvas to Image URL
            const imgData = canvas.toDataURL('image/png');
            
            // Create virtual download link
            const link = document.createElement('a');
            link.download = 'Pohon_Keputusan_C45_<?php echo date("Ymd_His"); ?>.png';
            link.href = imgData;
            link.click();
            
            // Restore UI & Scale entirely
            exportTitle.classList.add('d-none');
            exportArea.style.backgroundColor = "#f8fafc";
            
            // Restore original zoom
            currentZoom = originalZoom;
            exportArea.style.transform = `scale(${currentZoom})`;
            
            btn.innerHTML = originalBtnHtml;
            btn.disabled = false;
        }).catch(err => {
            alert("Terjadi kesalahan saat mengekspor gambar!");
            console.error(err);
            exportTitle.classList.add('d-none');
            
            currentZoom = originalZoom;
            exportArea.style.transform = `scale(${currentZoom})`;
            
            btn.innerHTML = originalBtnHtml;
            btn.disabled = false;
        });
    }, 300); // 300ms rendering buffer
}

// Function to show patients list in modal
function showPatients(element) {
    const patientsData = element.getAttribute('data-patients');
    if (!patientsData) return;
    
    try {
        const patients = JSON.parse(patientsData);
        const modalBody = document.getElementById('patientsModalBody');
        const modalCount = document.getElementById('patientsModalCount');
        
        modalCount.textContent = patients.length + ' Pasien';
        
        if (patients.length === 0) {
            modalBody.innerHTML = '<div class="alert alert-info">Tidak ada data pasien untuk simpul ini.</div>';
        } else {
            let html = '<ul class="list-group list-group-flush border" style="border-radius: 8px;">';
            patients.forEach((name, index) => {
                html += '<li class="list-group-item px-3 py-2 border-bottom">' + 
                        '<span class="fw-bold text-muted me-2">' + (index + 1) + '.</span>' + 
                        '<span class="text-dark">' + name + '</span>' +
                        '</li>';
            });
            html += '</ul>';
            modalBody.innerHTML = html;
        }
        
        // Show modal
        const myModal = new bootstrap.Modal(document.getElementById('patientsModal'));
        myModal.show();
    } catch (e) {
        console.error("Gagal parse data pasien:", e);
    }
}
</script>

<!-- Modal Menampilkan Data Pasien -->
<div class="modal fade" id="patientsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="border-radius:0; border-top: 4px solid var(--primary-teal);">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold text-dark-teal">Daftar Pasien pada Simpul</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <span class="text-muted fw-bold">Total Record Data:</span>
            <span class="badge bg-teal fw-bold fs-6" id="patientsModalCount">0 Pasien</span>
        </div>
        <div id="patientsModalBody">
            <!-- List pasien akan di-render di sini -->
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" style="border-radius:0;" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
