<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C45_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Mengambil data latih dari tabel data_latih
     */
    public function get_training_data($id_dataset) {
        $this->db->where('id_dataset', $id_dataset);
        $query = $this->db->get('data_latih');
        $result = $query->result_array();

        $formatted_data = [];
        foreach ($result as $row) {
            $attributes = json_decode($row['nilai_atribut_json'], true);
            $attributes['Target'] = $row['kelas_target'];
            $formatted_data[] = $attributes;
        }

        return $formatted_data;
    }

    /**
     * Mengambil daftar atribut untuk dataset tertentu
     */
    public function get_attributes($id_dataset) {
        $this->db->where('id_dataset', $id_dataset);
        $this->db->where('is_target', 0);
        $query = $this->db->get('atribut');
        return array_column($query->result_array(), 'nama_atribut');
    }

    /**
     * Menyimpan hasil model klasifikasi secara lengkap beserta evaluasinya
     */
    public function save_model_full($id_dataset, $nama_model, $accuracy, $evaluasi_per_kelas, $confusion_matrix, $tree_structure) {
        $presisi = [];
        $recall = [];
        $f1_score = [];
        
        foreach($evaluasi_per_kelas as $kelas => $eval) {
            $presisi[$kelas] = $eval['precision'];
            $recall[$kelas] = $eval['recall'];
            $f1_score[$kelas] = $eval['f1_score'];
        }

        $data = [
            'id_dataset' => $id_dataset,
            'nama_model' => $nama_model,
            'algoritma' => 'C4.5',
            'akurasi' => $accuracy,
            'presisi' => json_encode($presisi),
            'recall' => json_encode($recall),
            'f1_score' => json_encode($f1_score),
            'confusion_matrix_json' => json_encode($confusion_matrix),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('model_klasifikasi', $data);
        $id_model = $this->db->insert_id();

        // Simpan pohon keputusan
        $tree_data = [
            'id_model' => $id_model,
            'struktur_pohon' => serialize($tree_structure),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('pohon_keputusan', $tree_data);

        return $id_model;
    }
    /**
     * Mengambil detail model berdasarkan ID
     */
    public function get_model($id_model) {
        $this->db->where('id_model', $id_model);
        $model = $this->db->get('model_klasifikasi')->row_array();
        
        if ($model) {
            $this->db->where('id_model', $id_model);
            $tree = $this->db->get('pohon_keputusan')->row_array();
            $model['tree'] = $tree ? unserialize($tree['struktur_pohon']) : null;
        }
        
        return $model;
    }

    /**
     * Mengambil model terbaru yang dibuat
     */
    public function get_latest_model($id_dataset = 1) {
        $this->db->where('id_dataset', $id_dataset);
        $this->db->order_by('id_model', 'DESC');
        $model = $this->db->get('model_klasifikasi')->row_array();
        
        if ($model) {
            return $this->get_model($model['id_model']);
        }
        
        return null;
    }
}


