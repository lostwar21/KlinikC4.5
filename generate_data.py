import zipfile
import xml.etree.ElementTree as ET
import random
import json

def main():
    doc = zipfile.ZipFile('Data keluhan pasien RSGM.docx')
    root = ET.fromstring(doc.read('word/document.xml'))
    ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
    rows = root.findall('.//w:tr', ns)
    
    patients = []
    for r in rows:
        cols = [''.join(n.text for n in c.iter() if n.text).strip() for c in r.findall('.//w:tc', ns)]
        if len(cols) >= 2 and cols[0].upper() != 'NAMA' and cols[0].strip() and cols[1].strip():
            patients.append((cols[0], cols[1]))
            
    def infer_keluhan(text):
        t = text.lower()
        
        # Bedah Mulut -> K4 -> T4
        if 'sisa akar' in t or 'cabut' in t or 'pencabutan' in t:
            return 'K4', 'T4'
            
        # Prostodonti -> K5 -> T5
        if 'hilang' in t or 'ompong' in t or 'palsu' in t or 'tiruan' in t or 'kehilangan' in t:
            return 'K5', 'T5'
            
        # Orthodonti -> K3 -> T3
        if 'rapi' in t or 'berjejal' in t or 'maju' in t or 'berantakan' in t or 'jarak' in t:
            return 'K3', 'T3'
            
        # Periodonti -> K2 -> T2
        if 'karang' in t or 'kotor' in t or 'bengkak' in t or 'darah' in t or 'radang' in t or 'goyang' in t:
            return 'K2', 'T2'
            
        # Oral Medicine -> K6 -> T6
        if 'luka' in t or 'bibir' in t or 'sariawan' in t:
            return 'K6', 'T6'
            
        # Konservasi Gigi -> K1 -> T1
        return 'K1', 'T1'

    # Read original database.sql to rebuild
    with open('database.sql', 'r', encoding='utf-8') as f:
        content = f.read()

    cutoff = '-- DATA DUMMY AWAL'
    idx = content.find(cutoff)
    if idx == -1:
        print("Cutoff not found!")
        return

    base_sql = content[:idx]
    
    new_sql = base_sql + "-- DATA DUMMY AWAL\n"
    new_sql += "INSERT INTO `pengguna` (`username`, `password`, `nama_lengkap`, `level`, `status`) VALUES\n"
    new_sql += "('admin', 'admin', 'Administrator Sistem', 'admin', 'aktif'),\n"
    new_sql += "('petugas', 'petugas', 'Petugas Rekam Medis', 'petugas', 'aktif');\n\n"
    
    new_sql += "INSERT INTO `dataset` (`nama_dataset`, `sumber`, `jumlah_record`, `jumlah_atribut`, `deskripsi`) VALUES\n"
    new_sql += f"('Dataset Klasifikasi Gigi v1', 'Rumah Sakit Gigi dan Mulut USU', {len(patients)}, 4, 'Data pasien gigi 2024-2025 untuk algoritma C4.5');\n\n"
    
    new_sql += "INSERT INTO `atribut` (`id_dataset`, `nama_atribut`, `tipe_data`, `nilai_mungkin`, `is_target`) VALUES\n"
    new_sql += "(1, 'Usia', 'kategorikal', 'Anak,Remaja,Dewasa,Lansia', 0),\n"
    new_sql += "(1, 'Jenis_Kelamin', 'kategorikal', 'L,P', 0),\n"
    new_sql += "(1, 'Keluhan_Utama', 'kategorikal', 'K1,K2,K3,K4,K5,K6', 0),\n"
    new_sql += "(1, 'Riwayat_Penyakit', 'kategorikal', 'Ada,Tidak Ada', 0),\n"
    new_sql += "(1, 'Tindakan', 'kategorikal', 'T1,T2,T3,T4,T5,T6', 1);\n\n"

    new_sql += "-- DATA PASIEN RSGM USU & DATA LATIH\n"
    
    pasien_inserts = []
    rm_inserts = []
    data_latih_inserts = []
    
    for i, (nama, keluhan) in enumerate(patients):
        id_pasien = i + 1
        nomor_rm = f'RM-{id_pasien:03d}'
        jk_str = random.choice(['L', 'P'])
        usia_num = random.randint(7, 75)
        if usia_num <= 12: usia_cat = 'Anak'
        elif usia_num <= 25: usia_cat = 'Remaja'
        elif usia_num <= 55: usia_cat = 'Dewasa'
        else: usia_cat = 'Lansia'
        riwayat = random.choice(['Ada', 'Tidak Ada'])
        keluhan_kategori, target_kategori = infer_keluhan(keluhan)
        
        nama_safe = nama.replace(chr(39), '')
        # We store the raw text in 'keluhan_utama' in DB but we want the category for the C4.5
        # Wait, if rekam_medis 'keluhan_utama' is text, but we want to map it to K1-K6. 
        # Actually we should store K1-K6 in 'keluhan_utama' to match the new dropdown design!
        # But where does the original raw text go? "Catatan" maybe?
        keluhan_safe = keluhan.replace(chr(39), '')
        
        pasien_inserts.append(f"({id_pasien}, '{nomor_rm}', '{nama_safe}', {usia_num}, '{jk_str}', 'Medan')")
        
        # We will save the mapped 'K1'-'K6' into keluhan_utama, and raw text into catatan so it's not lost
        rm_inserts.append(f"({id_pasien}, {id_pasien}, 1, '2025-05-16', '{keluhan_kategori}', '{riwayat}', 'Pemeriksaan Klinis', 'Diagnosis Sementara', '{target_kategori}', '{keluhan_safe}')")
        
        json_attr = {"Usia": usia_cat, "Jenis_Kelamin": jk_str, "Keluhan_Utama": keluhan_kategori, "Riwayat_Penyakit": riwayat}
        json_str = json.dumps(json_attr)
        data_latih_inserts.append(f"(1, {id_pasien}, '{json_str}', '{target_kategori}')")
        
    new_sql += "INSERT INTO `pasien` (`id_pasien`, `nomor_rm`, `nama`, `usia`, `jenis_kelamin`, `alamat`) VALUES\n"
    new_sql += ",\n".join(pasien_inserts) + ";\n\n"
    
    new_sql += "INSERT INTO `rekam_medis` (`id_rm`, `id_pasien`, `id_pengguna`, `tanggal_kunjungan`, `keluhan_utama`, `riwayat_penyakit`, `hasil_pemeriksaan`, `diagnosis`, `tindakan`, `catatan`) VALUES\n"
    new_sql += ",\n".join(rm_inserts) + ";\n\n"
    
    new_sql += "INSERT INTO `data_latih` (`id_dataset`, `id_pasien`, `nilai_atribut_json`, `kelas_target`) VALUES\n"
    new_sql += ",\n".join(data_latih_inserts) + ";\n\n"
    
    new_sql += "COMMIT;\n"
    
    with open('database.sql', 'w', encoding='utf-8') as f:
        f.write(new_sql)
        
    print(f"Successfully generated {len(patients)} records mapped to K1-K6 and T1-T6!")

if __name__ == '__main__':
    main()
