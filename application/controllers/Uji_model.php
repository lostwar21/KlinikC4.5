<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property C45_Model $C45_Model
 */
class Uji_model extends CI_Controller {

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

        // Hanya Admin & Pemilik (Dokter) yang bisa melakukan Uji Model
        if ($this->session->userdata('level') == 'petugas') {
            redirect('dashboard');
        }
    }

    public function index() {
        $data['title'] = "Uji Model Prediksi";
        $data['active'] = "uji";
        $data['latest_model'] = $this->C45_Model->get_latest_model();

        $this->load->view('layout/header', $data);
        $this->load->view('uji_model/index', $data);
        $this->load->view('layout/footer');
    }

    public function prediksi() {
        $post = $this->input->post();
        $id_model = $post['id_model'];
        
        $model = $this->C45_Model->get_model($id_model);
        if (!$model || !$model['tree']) {
            echo json_encode(['status' => 'error', 'message' => 'Model tidak ditemukan.']);
            return;
        }

        // Mapping input raw ke kategori (Sesuai dataset dummy kita)
        $usia = (int)$post['usia'];
        if ($usia <= 12) $kat_usia = 'Anak';
        elseif ($usia <= 25) $kat_usia = 'Remaja';
        elseif ($usia <= 55) $kat_usia = 'Dewasa';
        else $kat_usia = 'Lansia';

        $data_uji = [
            'Usia' => $kat_usia,
            'Jenis_Kelamin' => $post['jenis_kelamin'],
            'Keluhan_Utama' => $post['keluhan_utama'],
            'Riwayat_Penyakit' => $post['riwayat_penyakit']
        ];

        $prediksi = $this->traverse($model['tree'], $data_uji);

        echo json_encode([
            'status' => 'success',
            'prediksi' => $prediksi,
            'input' => $data_uji
        ]);
    }

    private function traverse($tree, $data) {
        if ($tree['type'] == 'leaf') return $tree['label'];
        $attr = $tree['attribute'];
        $val = isset($data[$attr]) ? $data[$attr] : null;
        if ($val !== null && isset($tree['branches'][$val])) {
            return $this->traverse($tree['branches'][$val], $data);
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
