<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property C45_Model $C45_Model
 * @property C45_Engine $c45_engine
 */
class Klasifikasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('C45_Model');
        $this->load->library('C45_Engine');
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');

        // Pengecekan Login & Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Hanya Admin & Pemilik (Dokter) yang bisa menjalankan Algoritma
        if ($this->session->userdata('level') == 'petugas') {
            redirect('dashboard');
        }
    }

    /**
     * Halaman Utama Proses Klasifikasi (Gambar IV.12)
     */
    public function proses($id_dataset = 1) {
        $data['title'] = "Proses Klasifikasi C4.5";
        $data['active'] = "proses";
        
        // Ambil info dataset dasar
        $this->db->where('id_dataset', $id_dataset);
        $data['dataset'] = $this->db->get('dataset')->row_array();
        
        // SINKRONISASI: Hitung jumlah record aktual di data_latih
        $this->db->where('id_dataset', $id_dataset);
        $data['actual_record_count'] = $this->db->count_all_results('data_latih');
        
        // Data Atribut
        $this->db->where('id_dataset', $id_dataset);
        $data['atribut_list'] = $this->db->get('atribut')->result_array();
        
        // Model Terakhir
        $data['latest_model'] = $this->C45_Model->get_latest_model($id_dataset);

        $this->load->view('layout/header', $data);
        $this->load->view('klasifikasi/index', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Method AJAX untuk Menjalankan Algoritma (80:20 Split)
     */
    public function run_training($id_dataset = 1) {
        // Ambil parameter dari POST (Confidence, Pruning, dsb)
        $post = $this->input->post();
        $split_ratio = isset($post['split_ratio']) ? ($post['split_ratio'] / 100) : 0.8;

        // 1. Ambil Semua Data dari Data Latih (Dataset Sumber) & Atribut
        $all_data = $this->C45_Model->get_training_data($id_dataset);
        $attributes = $this->C45_Model->get_attributes($id_dataset);
        $target_attribute = 'Target';

        if (empty($all_data)) {
            echo json_encode(['status' => 'error', 'message' => 'Dataset kosong.']);
            return;
        }

        // 2. Data Splitting (Random Sampling)
        shuffle($all_data);
        $total_data = count($all_data);
        $train_count = floor($total_data * $split_ratio);
        
        $data_latih = array_slice($all_data, 0, $train_count);
        $data_uji   = array_slice($all_data, $train_count);

        // 3. Build Tree (HANYA menggunakan $data_latih)
        $tree = $this->c45_engine->build_tree($data_latih, $attributes, $target_attribute);

        // 4. Pengujian pada Data Uji
        $correct = 0;
        $total_test = count($data_uji);
        
        // Ambil kelas unik secara dinamis dari data latih
        $kelas_unik = array_values(array_unique(array_column($all_data, $target_attribute)));
        $confusion_matrix = [];
        foreach ($kelas_unik as $k1) {
            foreach ($kelas_unik as $k2) { $confusion_matrix[$k1][$k2] = 0; }
            $confusion_matrix[$k1]['Unknown'] = 0;
        }

        foreach ($data_uji as $row) {
            $aktual = $row[$target_attribute];
            $prediksi = $this->predict($tree, $row);
            
            // Pengamanan jika label tidak dikenal
            if (!isset($confusion_matrix[$aktual])) {
                continue; 
            }

            if ($prediksi == $aktual) $correct++;
            
            if(isset($confusion_matrix[$aktual][$prediksi])) {
                $confusion_matrix[$aktual][$prediksi]++;
            } else {
                $confusion_matrix[$aktual]['Unknown']++;
            }
        }

        $accuracy = ($total_test > 0) ? ($correct / $total_test) * 100 : 0;
        
        // Kalkulasi Precision, Recall, F1
        $evaluasi_per_kelas = [];
        foreach ($kelas_unik as $kelas) {
            $tp = isset($confusion_matrix[$kelas][$kelas]) ? $confusion_matrix[$kelas][$kelas] : 0;
            $fn = array_sum($confusion_matrix[$kelas]) - $tp - ($confusion_matrix[$kelas]['Unknown'] ?? 0);
            
            $fp = 0;
            foreach ($kelas_unik as $akt) { 
                if ($akt != $kelas) {
                    $fp += isset($confusion_matrix[$akt][$kelas]) ? $confusion_matrix[$akt][$kelas] : 0;
                }
            }
            
            $precision = ($tp + $fp == 0) ? 0 : ($tp / ($tp + $fp)) * 100;
            $recall = ($tp + $fn == 0) ? 0 : ($tp / ($tp + $fn)) * 100;
            $f1 = ($precision + $recall == 0) ? 0 : 2 * (($precision * $recall) / ($precision + $recall));
            
            $evaluasi_per_kelas[$kelas] = ['precision' => $precision, 'recall' => $recall, 'f1_score' => $f1];
        }

        // 5. Simpan Model
        $id_model = $this->C45_Model->save_model_full(
            $id_dataset, 'Model C4.5 ' . date('d/m/Y H:i'), $accuracy, $evaluasi_per_kelas, $confusion_matrix, $tree
        );

        echo json_encode([
            'status'     => 'success',
            'id_model'   => $id_model,
            'akurasi'    => number_format($accuracy, 2),
            'tree'       => $tree,
            'split_info' => "Total: $total_data (Latih: $train_count, Uji: $total_test)"
        ]);
    }

    /**
     * Halaman Visualisasi Pohon (Gambar IV.13)
     */
    public function pohon($id_model = null) {
        $data['title'] = "Pohon Keputusan";
        $data['active'] = "pohon";
        
        if ($id_model) {
            $data['model'] = $this->C45_Model->get_model($id_model);
        } else {
            $data['model'] = $this->C45_Model->get_latest_model();
        }

        $this->load->view('layout/header', $data);
        $this->load->view('klasifikasi/pohon', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Halaman Aturan (IF-THEN)
     */
    public function aturan($id_model = null) {
        $data['title'] = "Aturan Klasifikasi";
        $data['active'] = "aturan";
        
        if ($id_model) {
            $data['model'] = $this->C45_Model->get_model($id_model);
        } else {
            $data['model'] = $this->C45_Model->get_latest_model();
        }

        $this->load->view('layout/header', $data);
        $this->load->view('klasifikasi/aturan', $data);
        $this->load->view('layout/footer');
    }

    private function predict($tree, $data) {
        if ($tree['type'] == 'leaf') return $tree['label'];
        $attr = $tree['attribute'];
        $val = isset($data[$attr]) ? $data[$attr] : null;
        if ($val !== null && isset($tree['branches'][$val])) {
            return $this->predict($tree['branches'][$val], $data);
        } else {
            $max_count = -1; $best_label = 'Unknown';
            foreach($tree['branches'] as $branch) {
                if ($branch['type'] == 'leaf' && isset($branch['count']) && $branch['count'] > $max_count) {
                    $max_count = $branch['count']; $best_label = $branch['label'];
                }
            }
            return $best_label;
        }
    }
}

