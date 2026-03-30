<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Menyimpan data rekam medis baru
     */
    public function insert($data) {
        return $this->db->insert('rekam_medis', $data);
    }

    /**
     * Ambil rekam medis beserta nama pasien (Join)
     */
    public function get_all() {
        $this->db->select('rm.*, p.nama as nama_pasien, p.nomor_rm');
        $this->db->from('rekam_medis rm');
        $this->db->join('pasien p', 'p.id_pasien = rm.id_pasien');
        $this->db->order_by('rm.tanggal_kunjungan', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil histori perkunjungan pasien tertentu
     */
    public function get_history_by_pasien($id_pasien) {
        $this->db->where('id_pasien', $id_pasien);
        $this->db->order_by('tanggal_kunjungan', 'DESC');
        return $this->db->get('rekam_medis')->result_array();
    }
}
