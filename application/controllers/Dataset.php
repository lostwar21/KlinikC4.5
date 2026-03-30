<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property Pasien_model $Pasien_model
 */
class Dataset extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Pengecekan Login & Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Hanya Admin & Pemilik yang bisa melihat Dataset Mentah
        if ($this->session->userdata('level') == 'petugas') {
            redirect('dashboard');
        }
    }

    public function index($id_dataset = 1) {
        $data['title'] = "Dataset Klasifikasi";
        $data['active'] = "dataset";
        
        // Ambil info dataset
        $this->db->where('id_dataset', $id_dataset);
        $data['dataset_info'] = $this->db->get('dataset')->row_array();
        
        // Ambil baris data_latih dengan join pasien
        $this->db->select('dl.*, p.nama as nama_pasien, p.usia as usia_asli, p.jenis_kelamin as jk_asli');
        $this->db->from('data_latih dl');
        $this->db->join('pasien p', 'p.id_pasien = dl.id_pasien');
        $this->db->where('dl.id_dataset', $id_dataset);
        $this->db->order_by('dl.id_data_latih', 'ASC');
        $data['rows'] = $this->db->get()->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('dataset/index', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Hapus baris data latih individu
     */
    public function hapus($id) {
        $this->db->where('id_data_latih', $id);
        if ($this->db->delete('data_latih')) {
            $this->session->set_flashdata('success', 'Data latih berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data latih.');
        }
        redirect('dataset');
    }

    /**
     * Sinkronisasi dari Rekam Medis ke Dataset Mining
     * Proses otomatis merubah data riil menjadi kategori mining
     */
    public function sync_from_rm() {
        // Ambil rekam medis yang belum ada di data_latih
        // (Gunakan LEFT JOIN dan cari yang NULL di data_latih)
        $this->db->select('rm.*, p.usia, p.jenis_kelamin');
        $this->db->from('rekam_medis rm');
        $this->db->join('pasien p', 'p.id_pasien = rm.id_pasien');
        $this->db->join('data_latih dl', 'dl.id_pasien = rm.id_pasien', 'left');
        $this->db->where('dl.id_data_latih', NULL);
        $pending = $this->db->get()->result_array();

        if (empty($pending)) {
            $this->session->set_flashdata('info', 'Semua rekam medis sudah sinkron dengan dataset.');
            redirect('dataset');
        }

        $count = 0;
        foreach ($pending as $row) {
            // 1. Kategorisasi Usia
            $usia = (int)$row['usia'];
            if ($usia <= 12) $kat_usia = 'Anak';
            elseif ($usia <= 25) $kat_usia = 'Remaja';
            elseif ($usia <= 55) $kat_usia = 'Dewasa';
            else $kat_usia = 'Lansia';

            // 2. Data Mining JSON Attributes
            $atribut_dict = [
                "Usia" => $kat_usia,
                "Jenis_Kelamin" => $row['jenis_kelamin'],
                "Keluhan_Utama" => $row['keluhan_utama'],
                "Riwayat_Penyakit" => $row['riwayat_penyakit']
            ];

            // 3. Ekstrak Target Class (Misal: T1 - Penambalan -> T1)
            $target = trim(explode('-', $row['tindakan'])[0]);

            $insert_data = [
                'id_dataset' => 1,
                'id_pasien' => $row['id_pasien'],
                'nilai_atribut_json' => json_encode($atribut_dict),
                'kelas_target' => $target
            ];
            
            if($this->db->insert('data_latih', $insert_data)) $count++;
        }

        $this->session->set_flashdata('success', "$count data rekam medis berhasil disinkronkan ke dataset mining.");
        redirect('dataset');
    }
}
