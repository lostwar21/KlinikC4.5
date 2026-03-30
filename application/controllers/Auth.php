<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }

    /**
     * Tampilan Halaman Login
     */
    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $data['title'] = "Sistem Klasifikasi Rekam Medis - Praktik Gigi Mandiri Esensiil";
        $this->load->view('auth/login', $data);
    }

    /**
     * Proses Login
     */
    public function login_process() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Cari user di database
        $this->db->where('username', $username);
        $this->db->where('status', 'aktif');
        $user = $this->db->get('pengguna')->row_array();

        if ($user) {
            // Verify password (support both bcrypt and plain match for dev)
            if (password_verify($password, $user['password']) || $user['password'] === $password) {
                // Set session
                $session_data = [
                    'id_pengguna' => $user['id_pengguna'],
                    'username'    => $user['username'],
                    'nama_lengkap'=> $user['nama_lengkap'],
                    'level'       => $user['level'],
                    'logged_in'   => TRUE
                ];
                $this->session->set_userdata($session_data);

                // Log aktivitas
                $this->db->insert('log_aktivitas', [
                    'id_pengguna' => $user['id_pengguna'],
                    'aktivitas'   => 'Login ke sistem',
                    'endpoint'    => 'auth/login_process',
                    'ip_address'  => $this->input->ip_address(),
                    'user_agent'  => $this->input->user_agent()
                ]);

                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth/login');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan atau akun nonaktif!');
            redirect('auth/login');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        // Log aktivitas
        if ($this->session->userdata('logged_in')) {
            $this->db->insert('log_aktivitas', [
                'id_pengguna' => $this->session->userdata('id_pengguna'),
                'aktivitas'   => 'Logout dari sistem',
                'endpoint'    => 'auth/logout',
                'ip_address'  => $this->input->ip_address(),
                'user_agent'  => $this->input->user_agent()
            ]);
        }
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    /**
     * Default Index
     */
    public function index() {
        redirect('auth/login');
    }
}
