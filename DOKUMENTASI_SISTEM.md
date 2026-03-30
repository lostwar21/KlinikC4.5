# 📘 DOKUMENTASI SISTEM
# Klasifikasi Keluhan Rekam Medis Pasien dengan Algoritma C4.5
### Praktik Gigi Mandiri Esensiil — Modern Dental Care

---

## DAFTAR ISI
1. [Pendahuluan](#1-pendahuluan)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Struktur Direktori](#3-struktur-direktori)
4. [Skema Database](#4-skema-database)
5. [Penjelasan Kode — Controllers](#5-penjelasan-kode--controllers)
6. [Penjelasan Kode — Models](#6-penjelasan-kode--models)
7. [Penjelasan Kode — Libraries](#7-penjelasan-kode--libraries)
8. [Penjelasan Kode — Views](#8-penjelasan-kode--views)
9. [Alur Kerja Sistem](#9-alur-kerja-sistem)
10. [Keamanan (RBAC)](#10-keamanan-rbac)
11. [Kamus Data Medis](#11-kamus-data-medis)

---

## 1. PENDAHULUAN

### 1.1 Tujuan Sistem
Sistem ini dibangun untuk membantu **Praktik Gigi Mandiri Esensiil** dalam mengklasifikasikan keluhan pasien secara otomatis menggunakan **Algoritma C4.5 (Decision Tree)**. Sistem mampu:
- Mengelola data pasien dan rekam medis
- Melatih model klasifikasi dari data historis
- Memprediksi tindakan perawatan berdasarkan keluhan baru
- Menampilkan pohon keputusan dan aturan IF-THEN secara visual

### 1.2 Teknologi yang Digunakan

| Komponen | Teknologi | Versi |
|:--|:--|:--|
| Framework Backend | CodeIgniter 3 | 3.1.13 |
| Bahasa Pemrograman | PHP | 8.0+ |
| Database | MySQL / MariaDB | 5.7+ / 10.4+ |
| Web Server | Apache (XAMPP) | 2.4+ |
| Frontend CSS | Bootstrap 5 | 5.3.0 |
| Ikon | Font Awesome | 6.4.0 |
| Font | Google Fonts (Inter) | — |
| Grafik | Chart.js | 4.x |

---

## 2. ARSITEKTUR SISTEM

### 2.1 Pola Arsitektur: MVC (Model-View-Controller)

```
┌─────────────────────────────────────────────────────────────┐
│                        BROWSER (Client)                      │
│              Chrome / Firefox / Edge / Safari                │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTP Request
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    APACHE WEB SERVER                         │
│                     (XAMPP / WAMP)                           │
│                index.php (front controller)                  │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│               CODEIGNITER 3 FRAMEWORK                        │
│                                                              │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────────┐  │
│  │ CONTROLLERS │──│    MODELS    │──│    DATABASE        │  │
│  │             │  │              │  │   (MySQL/MariaDB)  │  │
│  │ • Auth      │  │ • C45_Model  │  │   db_klinik_c45    │  │
│  │ • Dashboard │  │ • Dashboard_ │  │                    │  │
│  │ • Pasien    │  │   model      │  │ 13 Tabel:          │  │
│  │ • Rekam_    │  │ • Pasien_    │  │ • pengguna         │  │
│  │   medis     │  │   model      │  │ • pasien           │  │
│  │ • Dataset   │  │ • Rekam_     │  │ • rekam_medis      │  │
│  │ • Klasifi-  │  │   medis_     │  │ • dataset          │  │
│  │   kasi      │  │   model      │  │ • data_latih       │  │
│  │ • Uji_model │  │              │  │ • model_klasifi-   │  │
│  │ • Laporan   │  └──────────────┘  │   kasi             │  │
│  │ • Pengaturan│                    │ • pohon_keputusan  │  │
│  └──────┬──────┘                    │ • log_aktivitas    │  │
│         │                           └────────────────────┘  │
│         ▼                                                    │
│  ┌──────────────┐  ┌──────────────┐                         │
│  │    VIEWS     │  │  LIBRARIES   │                         │
│  │              │  │              │                         │
│  │ • layout/    │  │ • C45_Engine │                         │
│  │   header.php │  │   (Algoritma │                         │
│  │   footer.php │  │    murni)    │                         │
│  │ • auth/      │  │              │                         │
│  │ • dashboard/ │  └──────────────┘                         │
│  │ • pasien/    │                                           │
│  │ • rekam_     │                                           │
│  │   medis/     │                                           │
│  │ • klasifi-   │                                           │
│  │   kasi/      │                                           │
│  │ • dataset/   │                                           │
│  │ • uji_model/ │                                           │
│  │ • laporan/   │                                           │
│  │ • pengaturan/│                                           │
│  └──────────────┘                                           │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Alur Request

```
User → Browser → Apache → index.php → Router (routes.php)
→ Controller → Model (Query DB) → Controller → View → HTML → Browser
```

---

## 3. STRUKTUR DIREKTORI

```
project_skripsi/
│
├── application/                    # Kode aplikasi utama
│   ├── config/
│   │   ├── config.php             # Konfigurasi umum (base_url, session, dll)
│   │   ├── database.php           # Konfigurasi koneksi database
│   │   └── routes.php             # Pemetaan URL ke controller
│   │
│   ├── controllers/               # Logika bisnis (9 controller)
│   │   ├── Auth.php               # Login, Logout, Autentikasi
│   │   ├── Dashboard.php          # Halaman utama setelah login
│   │   ├── Pasien.php             # CRUD data pasien
│   │   ├── Rekam_medis.php        # CRUD rekam medis klinis
│   │   ├── Dataset.php            # Manajemen data latih + sinkronisasi
│   │   ├── Klasifikasi.php        # Proses C4.5, pohon, aturan
│   │   ├── Uji_model.php         # Prediksi manual (uji satu pasien)
│   │   ├── Laporan.php            # Statistik & cetak laporan
│   │   └── Pengaturan.php         # Profil akun pengguna
│   │
│   ├── models/                    # Akses database (4 model)
│   │   ├── C45_Model.php          # Query data latih, simpan/ambil model
│   │   ├── Dashboard_model.php    # Query statistik dashboard
│   │   ├── Pasien_model.php       # Query pasien + cascading delete
│   │   └── Rekam_medis_model.php  # Query rekam medis + join pasien
│   │
│   ├── libraries/                 # Library kustom
│   │   └── C45_Engine.php         # Implementasi murni algoritma C4.5
│   │
│   └── views/                     # Tampilan HTML (11 folder)
│       ├── layout/
│       │   ├── header.php         # Navbar + Sidebar (shared)
│       │   └── footer.php         # Script JS + penutup HTML
│       ├── auth/login.php         # Halaman login
│       ├── dashboard/index.php    # Dashboard statistik
│       ├── pasien/                # Daftar, tambah, edit, detail pasien
│       ├── rekam_medis/           # Daftar, input rekam medis
│       ├── dataset/index.php      # Tabel data latih + tombol sync
│       ├── klasifikasi/
│       │   ├── index.php          # Proses training C4.5
│       │   ├── pohon.php          # Visualisasi pohon keputusan
│       │   └── aturan.php         # Daftar aturan IF-THEN
│       ├── uji_model/index.php    # Form prediksi manual
│       ├── laporan/index.php      # Statistik + cetak PDF
│       └── pengaturan/index.php   # Profil akun
│
├── assets/                        # File statis
│   ├── css/dashboard.css          # Stylesheet kustom
│   └── img/logo.png               # Logo klinik
│
├── system/                        # Core CodeIgniter (JANGAN DIUBAH)
├── database.sql                   # File SQL untuk membuat database
├── index.php                      # Front controller (entry point)
└── .htaccess                      # URL rewriting Apache
```

---

## 4. SKEMA DATABASE

### 4.1 Nama Database: `db_klinik_c45`

Sistem menggunakan **13 tabel** yang saling berelasi:

```
┌────────────┐     ┌──────────────┐     ┌─────────────┐
│  pengguna  │◄────│ rekam_medis  │────►│   pasien    │
│            │     │              │     │             │
│ id_pengguna│     │ id_rm        │     │ id_pasien   │
│ username   │     │ id_pasien    │     │ nomor_rm    │
│ password   │     │ id_pengguna  │     │ nama        │
│ nama_lkp   │     │ tgl_kunjung  │     │ usia        │
│ level(enum)│     │ keluhan_utm  │     │ jenis_kel   │
│ status     │     │ riwayat_peny │     │ alamat      │
└────────────┘     │ diagnosis    │     │ no_telp     │
                   │ tindakan     │     └──────┬──────┘
                   └──────────────┘            │
                                               │
                   ┌──────────────┐            │
                   │  data_latih  │◄───────────┘
                   │              │
                   │ id_data_ltih │     ┌─────────────────┐
                   │ id_dataset   │────►│     dataset     │
                   │ id_pasien    │     │                 │
                   │ nilai_atr_json│     │ id_dataset      │
                   │ kelas_target │     │ nama_dataset    │
                   └──────────────┘     │ jumlah_record   │
                                        └────────┬────────┘
                                                 │
                   ┌──────────────────┐          │
                   │ model_klasifikasi│◄─────────┘
                   │                  │
                   │ id_model         │     ┌──────────────────┐
                   │ akurasi          │────►│ pohon_keputusan  │
                   │ presisi (json)   │     │                  │
                   │ recall (json)    │     │ id_pohon         │
                   │ f1_score (json)  │     │ struktur_pohon   │
                   │ confusion_matrix │     │  (serialized)    │
                   └──────────────────┘     └──────────────────┘
```

### 4.2 Tabel Utama

| No | Tabel | Fungsi | Jumlah Kolom |
|:--:|:--|:--|:--:|
| 1 | `pengguna` | Menyimpan akun pengguna dan hak akses (RBAC) | 7 |
| 2 | `pasien` | Data identitas pasien klinik | 11 |
| 3 | `rekam_medis` | Catatan kunjungan, keluhan, diagnosis, tindakan | 10 |
| 4 | `dataset` | Metadata dataset (nama, jumlah record) | 6 |
| 5 | `atribut` | Definisi atribut mining (Usia, JK, Keluhan, dll) | 6 |
| 6 | `data_latih` | Baris data mining dalam format JSON kategorikal | 5 |
| 7 | `data_uji` | Baris data uji beserta prediksi | 7 |
| 8 | `model_klasifikasi` | Hasil model (akurasi, presisi, recall, F1) | 10 |
| 9 | `pohon_keputusan` | Struktur pohon (PHP serialize) | 5 |
| 10 | `node_pohon` | Detail per-node (entropy, gain) | 11 |
| 11 | `aturan_klasifikasi` | Aturan IF-THEN yang di-generate | 5 |
| 12 | `hasil_klasifikasi` | Histori prediksi per-pasien | 10 |
| 13 | `log_aktivitas` | Audit trail login/logout | 6 |

### 4.3 Level Pengguna (ENUM)

| Level | Peran | Hak Akses |
|:--|:--|:--|
| `admin` | Administrator Sistem | Seluruh modul tanpa kecuali |
| `petugas` | Bagian Administrasi | Pasien, Rekam Medis, Laporan |
| `pemilik` | Dokter / Pemilik Klinik | Rekam Medis, Algoritma, Uji Model, Laporan |

---

## 5. PENJELASAN KODE — CONTROLLERS

### 5.1 Auth.php — Autentikasi Pengguna
**Lokasi**: `application/controllers/Auth.php`

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `login()` | GET | Menampilkan halaman login. Jika sudah login, redirect ke dashboard |
| `login_process()` | POST | Memverifikasi username/password, membuat session, mencatat log |
| `logout()` | GET | Menghapus session aktif, mencatat log, redirect ke login |

**Fitur Keamanan**:
- Password di-hash menggunakan `password_verify()` (bcrypt)
- Setiap login/logout dicatat ke tabel `log_aktivitas` beserta IP Address dan User Agent
- Status akun dicek (`aktif`/`nonaktif`) sebelum login diizinkan

---

### 5.2 Dashboard.php — Halaman Utama
**Lokasi**: `application/controllers/Dashboard.php`

| Method | Fungsi |
|:--|:--|
| `index()` | Menampilkan ringkasan statistik: total pasien, total RM, model aktif, distribusi keluhan (Chart.js), dan log aktivitas terakhir |

**Data yang di-query**: Total pasien, total rekam medis, model terakhir, distribusi keluhan (pie chart), 5 aktivitas terakhir.

---

### 5.3 Pasien.php — Manajemen Data Pasien
**Lokasi**: `application/controllers/Pasien.php`

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `index()` | GET | Daftar seluruh pasien (tabel paginasi) |
| `tambah()` | GET | Form input pasien baru |
| `simpan()` | POST | Menyimpan data pasien ke DB |
| `edit($id)` | GET | Form edit pasien berdasarkan ID |
| `update($id)` | POST | Update data pasien di DB |
| `hapus($id)` | GET | Hapus pasien beserta rekam medis dan data latih terkait (Cascading Delete) |
| `detail($id)` | GET | Detail pasien + histori rekam medis |

**RBAC**: Hanya `admin` dan `petugas` yang bisa mengakses. `pemilik` (dokter) dialihkan ke dashboard.

**Cascading Delete**: Saat menghapus pasien, sistem juga menghapus seluruh rekam medis dan data latih terkait menggunakan **database transaction** untuk mencegah data yatim piatu.

---

### 5.4 Rekam_medis.php — Catatan Klinis
**Lokasi**: `application/controllers/Rekam_medis.php`

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `index()` | GET | Daftar seluruh rekam medis (join dengan tabel pasien) |
| `input($id_pasien)` | GET | Form input rekam medis untuk pasien tertentu |
| `save()` | POST | Simpan rekam medis baru |

**RBAC**: Semua peran bisa melihat daftar. Namun hanya `admin` dan `pemilik` yang boleh melakukan input klinis (mengisi keluhan, diagnosis, dan tindakan).

---

### 5.5 Dataset.php — Manajemen Data Latih
**Lokasi**: `application/controllers/Dataset.php`

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `index()` | GET | Menampilkan tabel data latih lengkap (format JSON → tabel kategorikal) |
| `hapus($id)` | GET | Hapus satu baris data latih |
| `sync_from_rm()` | GET | **SINKRONISASI OTOMATIS**: Mengambil rekam medis yang belum ada di data latih, mengkonversi usia numerik → kategori (Anak/Remaja/Dewasa/Lansia), dan memasukkannya ke tabel `data_latih` |

**Logika Kategorisasi Usia** (di method `sync_from_rm`):
```
Usia ≤ 12  → "Anak"
Usia ≤ 25  → "Remaja"
Usia ≤ 55  → "Dewasa"
Usia > 55  → "Lansia"
```

---

### 5.6 Klasifikasi.php — Inti Algoritma C4.5 ⭐
**Lokasi**: `application/controllers/Klasifikasi.php`

Ini adalah controller paling penting dalam sistem. Mengelola seluruh siklus hidup model machine learning.

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `proses($id_dataset)` | GET | Menampilkan halaman konfigurasi parameter (Split Ratio, Pruning, Confidence Level) |
| `run_training($id_dataset)` | POST/AJAX | Menjalankan algoritma C4.5 secara penuh |
| `pohon($id_model)` | GET | Menampilkan visualisasi pohon keputusan (canvas/SVG) |
| `aturan($id_model)` | GET | Menampilkan aturan IF-THEN yang diekstrak dari pohon |
| `predict($tree, $data)` | Private | Fungsi rekursif untuk menelusuri pohon dan menghasilkan prediksi |

**Alur `run_training()`**:
1. Ambil seluruh data dari `data_latih`
2. Acak urutan data (`shuffle`)
3. Bagi data → Data Latih (80%) + Data Uji (20%)
4. Bangun pohon keputusan menggunakan `C45_Engine->build_tree()`
5. Uji pohon terhadap Data Uji → hitung akurasi
6. Hitung Confusion Matrix, Precision, Recall, F1-Score per kelas
7. Simpan seluruh hasil ke `model_klasifikasi` dan `pohon_keputusan`
8. Return JSON (untuk AJAX front-end)

---

### 5.7 Uji_model.php — Prediksi Manual
**Lokasi**: `application/controllers/Uji_model.php`

| Method | HTTP | Fungsi |
|:--|:--|:--|
| `index()` | GET | Form dropdown untuk memilih: Usia, JK, Keluhan, Riwayat |
| `predict()` | POST | Mengambil model terakhir, menelusuri pohon keputusan, mengembalikan prediksi tindakan |

Pengguna memilih 4 atribut → Sistem menelusuri pohon → Menampilkan tindakan yang direkomendasikan.

---

### 5.8 Laporan.php — Statistik & Cetak
**Lokasi**: `application/controllers/Laporan.php`

| Method | Fungsi |
|:--|:--|
| `index()` | Menampilkan ringkasan: total pasien, RM, data latih, akurasi terbaik, distribusi tindakan (doughnut chart), riwayat 10 model terakhir. Mendukung cetak PDF dengan kop surat dan tanda tangan. |

---

### 5.9 Pengaturan.php — Profil Akun
**Lokasi**: `application/controllers/Pengaturan.php`

| Method | Fungsi |
|:--|:--|
| `index()` | Menampilkan detail profil pengguna yang sedang login (username, level, nama lengkap) |

---

## 6. PENJELASAN KODE — MODELS

### 6.1 C45_Model.php — Model Inti Klasifikasi
**Lokasi**: `application/models/C45_Model.php`

| Method | Parameter | Return | Fungsi |
|:--|:--|:--|:--|
| `get_training_data($id_dataset)` | ID dataset | Array | Mengambil data latih dan mendekode JSON atribut menjadi array asosiatif PHP |
| `get_attributes($id_dataset)` | ID dataset | Array string | Mengambil daftar nama atribut (non-target) |
| `save_model_full(...)` | 6 parameter | int (id_model) | Menyimpan model beserta Precision/Recall/F1/Confusion Matrix + Pohon (serialize) |
| `get_model($id_model)` | ID model | Array | Mengambil satu model beserta struktur pohonnya (unserialize) |
| `get_latest_model($id_dataset)` | ID dataset | Array/null | Mengambil model terakhir yang dibuat |

---

### 6.2 Dashboard_model.php — Statistik Dashboard
| Method | Fungsi |
|:--|:--|
| `get_count_pasien()` | Menghitung total baris di tabel `pasien` |
| `get_count_rekam_medis()` | Menghitung total baris di tabel `rekam_medis` |
| `get_active_model()` | Mengambil model terakhir berdasarkan `created_at DESC` |
| `get_keluhan_distribution()` | Mengambil distribusi keluhan dari JSON di `data_latih` menggunakan `JSON_EXTRACT()` |
| `get_recent_activities()` | Mengambil 5 baris terakhir dari `log_aktivitas` |

---

### 6.3 Pasien_model.php — CRUD Pasien
| Method | Fungsi |
|:--|:--|
| `get_all()` | `SELECT * FROM pasien ORDER BY id DESC` |
| `get_by_id($id)` | Mengambil satu pasien berdasarkan ID |
| `insert($data)` | Insert pasien baru |
| `update($id, $data)` | Update data pasien |
| `delete($id)` | **Cascading Delete**: Hapus dari `data_latih` → `rekam_medis` → `pasien` dalam satu transaksi |

---

### 6.4 Rekam_medis_model.php — Query Rekam Medis
| Method | Fungsi |
|:--|:--|
| `get_all()` | Mengambil seluruh RM dengan JOIN ke tabel `pasien` dan `pengguna` |
| `get_by_pasien($id_pasien)` | Mengambil histori RM untuk satu pasien tertentu |
| `insert($data)` | Insert rekam medis baru |

---

## 7. PENJELASAN KODE — LIBRARIES

### 7.1 C45_Engine.php — Implementasi Algoritma C4.5 ⭐⭐
**Lokasi**: `application/libraries/C45_Engine.php`

Ini adalah **otak** dari sistem. Mengimplementasikan algoritma C4.5 secara murni (tanpa library ML eksternal).

#### Method 1: `calculate_entropy($data_count, $total_records)`
```
Rumus:  H(S) = -p × log₂(p)
Input:  Jumlah kejadian suatu kelas, total record
Output: Nilai entropy parsial (float)
```

#### Method 2: `get_total_entropy($data, $target_attribute)`
```
Menghitung total entropy untuk seluruh dataset.
Menghitung frekuensi setiap kelas target, lalu menjumlahkan
entropy parsial masing-masing kelas.
```

#### Method 3: `calculate_gain_ratio($data, $attribute, $target, $total_entropy)`
```
Langkah:
1. Kelompokkan data berdasarkan nilai atribut
2. Hitung Entropy setiap subset
3. Hitung Information Gain = H(S) - Σ (|Sv|/|S|) × H(Sv)
4. Hitung Split Info = -Σ (|Sv|/|S|) × log₂(|Sv|/|S|)
5. Hitung Gain Ratio = Gain / Split Info

Return: { gain, split_info, gain_ratio, subsets }
```

#### Method 4: `build_tree($data, $attributes, $target, $depth)`
```
Algoritma rekursif untuk membangun pohon:

1. Jika semua data memiliki kelas yang sama → DAUN (return label)
2. Jika atribut sudah habis → DAUN (return kelas mayoritas)
3. Hitung Gain Ratio untuk setiap atribut tersisa
4. Pilih atribut dengan Gain Ratio tertinggi → NODE
5. Untuk setiap nilai atribut terpilih:
   → Rekursi ke subset data → build_tree(subset, sisa_atribut, ...)
6. Return { type: 'node', attribute, branches }
```

**Output Pohon (Struktur PHP Array)**:
```php
[
  'type' => 'node',
  'attribute' => 'Keluhan_Utama',
  'gain_ratio' => 0.85,
  'branches' => [
    'K1' => [
      'type' => 'node',
      'attribute' => 'Usia',
      'branches' => [
        'Anak'   => ['type' => 'leaf', 'label' => 'T1', 'count' => 12],
        'Dewasa' => ['type' => 'leaf', 'label' => 'T2', 'count' => 8],
        ...
      ]
    ],
    'K2' => ['type' => 'leaf', 'label' => 'T4', 'count' => 20],
    ...
  ]
]
```

---

## 8. PENJELASAN KODE — VIEWS

### 8.1 Layout (Shared)

| File | Fungsi |
|:--|:--|
| `layout/header.php` | Navbar atas (logo, judul, logout), Sidebar navigasi dengan RBAC dinamis, memuat Bootstrap CSS, Font Awesome, Google Fonts |
| `layout/footer.php` | Memuat jQuery, Bootstrap JS, Chart.js, dan script toggle sidebar mobile |

### 8.2 Halaman-Halaman

| View | Elemen UI | Fungsi |
|:--|:--|:--|
| `auth/login.php` | Card glassmorphism, form login | Autentikasi pengguna |
| `dashboard/index.php` | 3 stat cards, bar chart keluhan, tabel aktivitas | Ringkasan sistem |
| `pasien/*.php` | Tabel DataTable, form CRUD | Kelola identitas pasien |
| `rekam_medis/*.php` | Tabel RM, form input klinis | Kelola catatan kunjungan |
| `dataset/index.php` | Tabel data latih, tombol Sync & Hapus | Kelola knowledge base |
| `klasifikasi/index.php` | Panel parameter, range slider, tombol proses, panel hasil (AJAX) | Training C4.5 |
| `klasifikasi/pohon.php` | Canvas SVG, tombol zoom, tombol download PNG | Visualisasi pohon keputusan |
| `klasifikasi/aturan.php` | Daftar aturan IF-THEN bernomor, tombol cetak | Representasi tekstual pohon |
| `uji_model/index.php` | 4 dropdown, tombol prediksi, panel hasil | Prediksi manual |
| `laporan/index.php` | Stat cards, doughnut chart, tabel model, kop surat cetak | Laporan & print |
| `pengaturan/index.php` | Card profil pengguna | Detail akun login |

---

## 9. ALUR KERJA SISTEM

### 9.1 Alur Lengkap (End-to-End)

```
[1] LOGIN                    → Autentikasi + Sesi + Log Aktivitas
       ↓
[2] INPUT PASIEN             → Daftarkan identitas pasien baru
       ↓
[3] INPUT REKAM MEDIS        → Catat keluhan, diagnosis, tindakan
       ↓
[4] SINKRONISASI DATASET     → Konversi RM → Data Latih (kategorikal)
       ↓
[5] PROSES KLASIFIKASI C4.5  → Training → Pohon Keputusan → Akurasi
       ↓
[6] LIHAT POHON & ATURAN     → Visualisasi + IF-THEN Rules
       ↓
[7] UJI MODEL (PREDIKSI)     → Input keluhan baru → Rekomendasi tindakan
       ↓
[8] CETAK LAPORAN             → Ringkasan + Kop Surat + Tanda Tangan
```

### 9.2 Alur Algoritma C4.5 (Detail)

```
Input: Dataset 100 baris, 4 atribut + 1 target

Langkah 1: Shuffle data
Langkah 2: Split → 80 baris latih, 20 baris uji

Langkah 3: Build Tree (Rekursif)
   3a. Hitung Entropy total dataset latih
   3b. Untuk setiap atribut:
       - Hitung Information Gain
       - Hitung Split Info
       - Hitung Gain Ratio = Gain / Split Info
   3c. Pilih atribut dengan Gain Ratio tertinggi
   3d. Buat node → Bagi data berdasarkan nilai atribut terpilih
   3e. Rekursi untuk setiap subset
   3f. Jika pure (satu kelas) atau atribut habis → buat leaf

Langkah 4: Test Tree pada 20 data uji
   4a. Untuk setiap baris uji, telusuri pohon
   4b. Bandingkan prediksi vs aktual
   4c. Hitung Confusion Matrix
   4d. Hitung Accuracy, Precision, Recall, F1-Score

Langkah 5: Simpan model ke database
```

---

## 10. KEAMANAN (RBAC)

### 10.1 Pembagian Hak Akses

| Menu Sidebar | admin | petugas | pemilik |
|:--|:--:|:--:|:--:|
| Dashboard | ✅ | ✅ | ✅ |
| Data Pasien | ✅ | ✅ | ❌ |
| Rekam Medis (Lihat) | ✅ | ✅ | ✅ |
| Rekam Medis (Input) | ✅ | ❌ | ✅ |
| Dataset | ✅ | ❌ | ✅ |
| Klasifikasi | ✅ | ❌ | ✅ |
| Uji Model | ✅ | ❌ | ✅ |
| Laporan | ✅ | ✅ | ✅ |
| Pengaturan | ✅ | ❌ | ❌ |

### 10.2 Implementasi Teknis
- **Sidebar Dinamis**: `header.php` menggunakan `$this->session->userdata('level')` untuk menyembunyikan/menampilkan menu
- **Controller Guard**: Setiap constructor controller melakukan pengecekan `level` dan `redirect('dashboard')` jika tidak berhak
- **Session Management**: Menggunakan CI Session Library dengan cookie-based storage

---

## 11. KAMUS DATA MEDIS

### 11.1 Kode Keluhan

| Kode | Keluhan |
|:--|:--|
| K1 | Karies Gigi |
| K2 | Nyeri Gigi |
| K3 | Radang Gusi |
| K4 | Karang Gigi |
| K5 | Gigi Sensitif / Ngilu |

### 11.2 Kode Tindakan (Target Class)

| Kode | Tindakan |
|:--|:--|
| T1 | Penambalan |
| T2 | Pencabutan |
| T3 | Scaling (Pembersihan Karang) |
| T4 | Medikamentosa (Pemberian Obat) |
| T5 | Rujukan ke Spesialis |

### 11.3 Kategori Usia

| Kategori | Rentang |
|:--|:--|
| Anak | 0 – 12 tahun |
| Remaja | 13 – 25 tahun |
| Dewasa | 26 – 55 tahun |
| Lansia | 56+ tahun |

---

> **Dokumen ini dibuat secara otomatis berdasarkan analisis kode sumber sistem.**
> Versi Terakhir: Maret 2026
