<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property C45_Model $C45_Model
 */
class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('C45_Model');
        $this->load->database();
    }

    public function index() {
        $data['title'] = "Laporan & Statistik";
        $data['active'] = "laporan";

        // Statistik Umum
        $data['total_pasien'] = $this->db->count_all('pasien');
        $data['total_rm'] = $this->db->count_all('rekam_medis');
        $data['total_latih'] = $this->db->count_all('data_latih');
        
        // Model Terbaik (Akurasi Tertinggi)
        $this->db->order_by('akurasi', 'DESC');
        $data['best_model'] = $this->db->get('model_klasifikasi')->row_array();
        
        // Distribusi Tindakan (Pie Chart Data)
        $this->db->select('tindakan as label, COUNT(*) as value');
        $this->db->group_by('tindakan');
        $data['distribusi_tindakan'] = $this->db->get('rekam_medis')->result_array();

        // Riwayat Klasifikasi Terakhir
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(10);
        $data['riwayat_model'] = $this->db->get('model_klasifikasi')->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('layout/footer');
    }
}
