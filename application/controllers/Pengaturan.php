<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 */
class Pengaturan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Pengecekan Login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = "Pengaturan Akun";
        $data['active'] = "pengaturan";
        
        // Ambil detail user saat ini
        $this->db->where('id_pengguna', $this->session->userdata('id_pengguna'));
        $data['user'] = $this->db->get('pengguna')->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('pengaturan/index', $data);
        $this->load->view('layout/footer');
    }
}
