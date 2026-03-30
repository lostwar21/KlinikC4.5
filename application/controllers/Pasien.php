<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property Pasien_model $Pasien_model
 */
class Pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Pengecekan Login & Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Hanya Admin & Petugas (Administrasi) yang mengelola Data Pasien
        if ($this->session->userdata('level') == 'pemilik') {
            redirect('dashboard');
        }

        $this->load->model('Pasien_model');
        $this->load->model('Rekam_medis_model');
    }

    /**
     * Tampilan Utama Tabel Data Pasien
     */
    public function index() {
        $data['title'] = "Data Pasien";
        $data['active'] = "pasien";
        $data['pasien'] = $this->Pasien_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('pasien/index', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Form Tambah Pasien
     */
    public function tambah() {
        $data['title'] = "Tambah Pasien Baru";
        $data['active'] = "pasien";
        $data['next_rm'] = $this->Pasien_model->get_latest_rm();

        $this->load->view('layout/header', $data);
        $this->load->view('pasien/form_tambah', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Simpan Data Baru
     */
    public function simpan() {
        $post = $this->input->post();
        
        $insert_data = [
            'nomor_rm'      => $post['nomor_rm'],
            'nama'          => $post['nama'],
            'tempat_lahir'  => $post['tempat_lahir'],
            'tanggal_lahir' => $post['tanggal_lahir'],
            'usia'          => $post['usia'],
            'jenis_kelamin' => $post['jenis_kelamin'],
            'alamat'        => $post['alamat'],
            'no_telp'       => $post['no_telp'],
            'pekerjaan'     => $post['pekerjaan']
        ];

        if ($this->Pasien_model->insert($insert_data)) {
            $this->session->set_flashdata('success', 'Data pasien berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data pasien.');
        }

        redirect('pasien');
    }

    /**
     * Hapus Pasien
     */
    public function hapus($id) {
        if ($this->Pasien_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pasien berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data pasien.');
        }
        redirect('pasien');
    }

    /**
     * Form Ubah Pasien
     */
    public function ubah($id) {
        $data['title'] = "Ubah Data Pasien";
        $data['active'] = "pasien";
        $data['p'] = $this->Pasien_model->get_by_id($id);

        if (!$data['p']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('pasien');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('pasien/form_ubah', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Proses Update Data
     */
    public function update() {
        $id = $this->input->post('id_pasien');
        $post = $this->input->post();
        
        $update_data = [
            'nama'          => $post['nama'],
            'tempat_lahir'  => $post['tempat_lahir'],
            'tanggal_lahir' => $post['tanggal_lahir'],
            'usia'          => $post['usia'],
            'jenis_kelamin' => $post['jenis_kelamin'],
            'alamat'        => $post['alamat'],
            'no_telp'       => $post['no_telp'],
            'pekerjaan'     => $post['pekerjaan']
        ];

        if ($this->Pasien_model->update($id, $update_data)) {
            $this->session->set_flashdata('success', 'Data pasien berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data pasien.');
        }

        redirect('pasien');
    }

    /**
     * Detail Pasien & Histori Medis
     */
    public function detail($id) {
        $data['title'] = "Detail Pasien";
        $data['active'] = "pasien";
        $data['p'] = $this->Pasien_model->get_by_id($id);

        if (!$data['p']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('pasien');
        }

        // Mengambil histori rekam medis menggunakan model yang sudah ada
        $data['histori'] = $this->Rekam_medis_model->get_history_by_pasien($id);

        $this->load->view('layout/header', $data);
        $this->load->view('pasien/detail', $data);
        $this->load->view('layout/footer');
    }
}

