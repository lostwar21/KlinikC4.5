<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Pengecekan Login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $this->load->model('Dashboard_model');
    }

    public function index() {
        // Data for head and layout
        $data['title'] = "Dashboard";
        $data['header_title'] = "Dashboard";
        $data['active'] = "dashboard";
        
        // Fetch stats
        $data['total_pasien'] = $this->Dashboard_model->get_count_pasien();
        $data['total_rm'] = $this->Dashboard_model->get_count_rekam_medis();
        $data['active_model'] = $this->Dashboard_model->get_active_model();
        $data['recent_activities'] = $this->Dashboard_model->get_recent_activities();

        // Prepare Chart Data (Keluhan distribution)
        $distribution = $this->Dashboard_model->get_keluhan_distribution();
        $labels = [];
        $values = [];
        foreach ($distribution as $row) {
            $labels[] = str_replace('"', '', $row['keluhan']); // Remove JSON quotes
            $values[] = $row['jumlah'];
        }
        $data['chart_labels'] = json_encode($labels);
        $data['chart_values'] = json_encode($values);

        // Load View with layout
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('layout/footer', $data);
    }
}
