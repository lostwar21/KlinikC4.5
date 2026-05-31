<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * C45_Engine Library
 * Algoritma C4.5 murni untuk Klasifikasi Rekam Medis
 * (Entropy, Gain, Split Info, Gain Ratio)
 */
class C45_Engine {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Menghitung Entropy
     * H(S) = sum(-pi * log2(pi))
     */
    public function calculate_entropy($data_count, $total_records) {
        if ($total_records == 0 || $data_count == 0) return 0;
        
        $p = $data_count / $total_records;
        return -$p * log($p, 2);
    }

    /**
     * Menghitung Total Entropy untuk sekelompok data berdasarkan target class
     */
    public function get_total_entropy($data, $target_attribute) {
        $total = count($data);
        if ($total == 0) return 0;

        $counts = array_count_values(array_column($data, $target_attribute));
        $entropy = 0;
        foreach ($counts as $count) {
            $entropy += $this->calculate_entropy($count, $total);
        }
        return $entropy;
    }

    /**
     * Menghitung Gain, Split Info, dan Gain Ratio
     * @param array $data Dataset
     * @param string $attribute Atribut yang diuji
     * @param string $target_attribute Target kelas
     * @param float $total_entropy Entropy awal sebelum pemisahan
     */
    public function calculate_gain_ratio($data, $attribute, $target_attribute, $total_entropy) {
        $total_records = count($data);
        if ($total_records == 0) return ['gain' => 0, 'split_info' => 0, 'gain_ratio' => 0];

        // Kelompokkan data berdasarkan nilai atribut
        $values = [];
        foreach ($data as $row) {
            $val = $row[$attribute];
            if (!isset($values[$val])) $values[$val] = [];
            $values[$val][] = $row;
        }

        $sum_entropy_attr = 0;
        $split_info = 0;

        foreach ($values as $val => $subset) {
            $subset_count = count($subset);
            $weight = $subset_count / $total_records;
            
            // Hitung Entropy untuk subset ini
            $subset_entropy = $this->get_total_entropy($subset, $target_attribute);
            $sum_entropy_attr += $weight * $subset_entropy;

            // Hitung Split Info: -sum(|Sv|/|S| * log2(|Sv|/|S|))
            $split_info -= $weight * log($weight, 2);
        }

        $gain = $total_entropy - $sum_entropy_attr;
        
        // Hindari pembagian dengan nol
        $gain_ratio = ($split_info == 0) ? 0 : ($gain / $split_info);

        return [
            'gain' => $gain,
            'split_info' => $split_info,
            'gain_ratio' => $gain_ratio,
            'subsets' => $values
        ];
    }

    /**
     * Membangun Pohon Keputusan (Recursive)
     */
    public function build_tree($data, $attributes, $target_attribute, $depth = 0) {
        $total_records = count($data);
        if ($total_records == 0) return null;

        // Cek jika semua target class sama (Homogen)
        $target_values = array_unique(array_column($data, $target_attribute));
        if (count($target_values) == 1) {
            return [
                'type' => 'leaf',
                'label' => reset($target_values),
                'count' => $total_records,
                'patients' => array_column($data, '_pasien')
            ];
        }

        // Cek jika atribut sudah habis
        if (empty($attributes)) {
            $counts = array_count_values(array_column($data, $target_attribute));
            arsort($counts);
            return [
                'type' => 'leaf',
                'label' => key($counts),
                'count' => $total_records,
                'patients' => array_column($data, '_pasien')
            ];
        }

        $total_entropy = $this->get_total_entropy($data, $target_attribute);
        $best_gain_ratio = -1;
        $best_attribute = null;
        $best_subsets = null;

        foreach ($attributes as $attr) {
            $result = $this->calculate_gain_ratio($data, $attr, $target_attribute, $total_entropy);
            if ($result['gain_ratio'] > $best_gain_ratio) {
                $best_gain_ratio = $result['gain_ratio'];
                $best_attribute = $attr;
                $best_subsets = $result['subsets'];
            }
        }

        if ($best_attribute === null) {
            $counts = array_count_values(array_column($data, $target_attribute));
            arsort($counts);
            return [
                'type' => 'leaf',
                'label' => key($counts),
                'count' => $total_records,
                'patients' => array_column($data, '_pasien')
            ];
        }

        // Rekursif untuk sisa atribut
        $remaining_attributes = array_diff($attributes, [$best_attribute]);
        $branches = [];

        foreach ($best_subsets as $val => $subset) {
            $branches[$val] = $this->build_tree($subset, $remaining_attributes, $target_attribute, $depth + 1);
        }

        return [
            'type' => 'node',
            'attribute' => $best_attribute,
            'gain_ratio' => $best_gain_ratio,
            'count' => $total_records,
            'patients' => array_column($data, '_pasien'),
            'branches' => $branches
        ];
    }
}
