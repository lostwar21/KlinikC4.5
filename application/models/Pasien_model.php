<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('pasien')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('pasien', ['id_pasien' => $id])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('pasien', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_pasien', $id);
        return $this->db->update('pasien', $data);
    }

    public function delete($id) {
        $this->db->trans_start();
        
        // Hapus data dependen (rekam medis dan data latih) terlebih dahulu untuk menghindari Foreign Key error
        $this->db->where('id_pasien', $id)->delete('data_latih');
        $this->db->where('id_pasien', $id)->delete('rekam_medis');
        
        // Terakhir hapus master pasien
        $this->db->where('id_pasien', $id)->delete('pasien');
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_latest_rm() {
        $this->db->select('nomor_rm');
        $this->db->order_by('id_pasien', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('pasien');
        if ($query->num_rows() > 0) {
            $last_rm = $query->row()->nomor_rm;
            $num = (int) substr($last_rm, 3);
            return 'RM-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        }
        return 'RM-001';
    }
}
