<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property Rekam_medis_model $Rekam_medis_model
 * @property Pasien_model $Pasien_model
 */
class Rekam_medis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Rekam_medis_model', 'rm_model');
        $this->load->model('Pasien_model', 'p_model');
    }

    /**
     * Tampilan Utama (Daftar Histori RM)
     */
    public function index() {
        $data['title'] = "Rekam Medis";
        $data['active'] = "rekam_medis";
        
        $tanggal_mulai = $this->input->get('tanggal_mulai');
        $tanggal_selesai = $this->input->get('tanggal_selesai');
        
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['histori'] = $this->rm_model->get_all($tanggal_mulai, $tanggal_selesai);

        $this->load->view('layout/header', $data);
        $this->load->view('rekam_medis/index', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Halaman Input Rekam Medis Baru
     */
    public function tambah($selected_id = null) {
        // Hanya Admin & Pemilik (Dokter) yang bisa mengakses Form Input Medis
        if ($this->session->userdata('level') == 'petugas') {
            $this->session->set_flashdata('error', 'Bagian Administrasi tidak berwenang menginput data klinis.');
            redirect('rekam_medis');
        }

        $data['title'] = "Input Rekam Medis";
        $data['active'] = "rekam_medis";
        $data['pasien'] = $this->p_model->get_all();
        $data['selected_id'] = $selected_id;

        $this->load->view('layout/header', $data);
        $this->load->view('rekam_medis/tambah', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Simpan Rekam Medis Baru
     */
    public function simpan() {
        // Hanya Admin & Pemilik (Dokter) yang bisa menyimpan diagnosis
        if ($this->session->userdata('level') == 'petugas') {
            redirect('dashboard');
        }

        $post = $this->input->post();
        
        // Handle "Jika Ada, sebutkan" for Riwayat Penyakit
        $riwayat = $post['riwayat_penyakit'];
        // Riwayat untuk C4.5 tetap 'Ada' atau 'Tidak Ada'
        // Detail sebutkan disimpan di catatan
        $catatan = $post['catatan'];
        if ($riwayat === 'Ada' && !empty($post['riwayat_sebutkan'])) {
            $catatan = 'Riwayat: ' . $post['riwayat_sebutkan'] . ($catatan ? '. ' . $catatan : '');
        }

        $insert_data = [
            'id_pasien'         => $post['id_pasien'],
            'id_pengguna'       => $this->session->userdata('id_pengguna'),
            'tanggal_kunjungan' => $post['tanggal_kunjungan'],
            'keluhan_utama'     => $post['keluhan_utama'],
            'riwayat_penyakit'  => $riwayat,
            'hasil_pemeriksaan' => $post['hasil_pemeriksaan'],
            'diagnosis'         => $post['diagnosis'],
            'tindakan'          => $post['tindakan'],
            'catatan'           => $catatan
        ];

        if ($this->rm_model->insert($insert_data)) {
            $this->session->set_flashdata('success', 'Rekam medis berhasil disimpan!');
            redirect('pasien/detail/'.$post['id_pasien']);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan rekam medis.');
            redirect('rekam_medis/tambah');
        }
    }

    /**
     * AJAX Get Pasien Info
     */
    public function get_pasien_ajax($id = null) {
        if (!$id) {
            echo json_encode(['status' => 'error']);
            return;
        }
        $p = $this->p_model->get_by_id($id);
        header('Content-Type: application/json');
        echo json_encode($p);
    }
}

