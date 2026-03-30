<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_count_pasien() {
        return $this->db->count_all('pasien');
    }

    public function get_count_rekam_medis() {
        return $this->db->count_all('rekam_medis');
    }

    public function get_active_model() {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('model_klasifikasi');
        return $query->row_array();
    }

    public function get_keluhan_distribution() {
        // Karena keluhan disimpan di JSON dalam data_latih atau rekam_medis, 
        // kita bisa hitung dari data_latih untuk visualisasi distribusi data latih.
        $this->db->select("JSON_EXTRACT(nilai_atribut_json, '$.Keluhan_Utama') as keluhan, COUNT(*) as jumlah");
        $this->db->group_by("JSON_EXTRACT(nilai_atribut_json, '$.Keluhan_Utama')");
        $query = $this->db->get('data_latih');
        return $query->result_array();
    }

    public function get_recent_activities() {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get('log_aktivitas');
        return $query->result_array();
    }
}
