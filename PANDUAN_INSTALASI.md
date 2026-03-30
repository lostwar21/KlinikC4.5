# 📦 PANDUAN INSTALASI SISTEM
# Klasifikasi Keluhan Rekam Medis Pasien — Algoritma C4.5
### Praktik Gigi Mandiri Esensiil

> [!IMPORTANT]
> Ikuti setiap langkah secara **berurutan** dari awal sampai akhir.
> Jangan melewati satu langkah pun agar sistem tidak error.

---

## PERSYARATAN MINIMUM LAPTOP

| Komponen | Minimum | Rekomendasi |
|:--|:--|:--|
| Sistem Operasi | Windows 10 64-bit | Windows 10/11 64-bit |
| RAM | 4 GB | 8 GB |
| Penyimpanan | 500 MB kosong | 1 GB kosong |
| Browser | Chrome 90+ | Chrome / Edge terbaru |
| Koneksi Internet | Diperlukan (untuk CDN awal) | — |

---

## LANGKAH 1: INSTALL XAMPP

XAMPP adalah paket yang berisi Apache (web server) + MySQL (database) + PHP, semua dalam satu installer.

### 1.1 Download XAMPP
1. Buka browser, kunjungi: **https://www.apachefriends.org/download.html**
2. Pilih versi **XAMPP for Windows** dengan **PHP 8.0** atau lebih baru (PHP 8.1 / 8.2 juga bisa)
3. Klik **Download** (ukuran file: ±150 MB)

### 1.2 Install XAMPP
1. Buka file installer yang sudah di-download (`xampp-windows-x64-8.x.x-installer.exe`)
2. Jika muncul peringatan UAC (User Account Control), klik **Yes**
3. Jika muncul peringatan antivirus, klik **OK/Continue**
4. Pada layar **Select Components**, pastikan yang dicentang:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
   - Yang lain boleh di-uncheck
5. Pada **Installation folder**, biarkan default: `C:\xampp`

> [!CAUTION]
> **JANGAN** install XAMPP di dalam folder `Program Files`. Gunakan `C:\xampp`.

6. Klik **Next → Next → Finish**

### 1.3 Jalankan XAMPP
1. Buka **XAMPP Control Panel** (cari di Start Menu)
2. Klik tombol **Start** di sebelah **Apache** → harus muncul tulisan hijau "Running"
3. Klik tombol **Start** di sebelah **MySQL** → harus muncul tulisan hijau "Running"

> [!WARNING]
> Jika Apache tidak bisa Start (port 80 terblokir), lakukan ini:
> 1. Klik tombol **Config** di sebelah Apache
> 2. Pilih **httpd.conf**
> 3. Cari baris `Listen 80`, ganti menjadi `Listen 8080`
> 4. Cari baris `ServerName localhost:80`, ganti menjadi `ServerName localhost:8080`
> 5. Save file, lalu klik **Start** lagi
> 6. Jika menggunakan port 8080, nanti base_url harus disesuaikan (lihat Langkah 4)

### 1.4 Verifikasi XAMPP
1. Buka browser
2. Ketik: `http://localhost/`
3. Harus muncul halaman **Welcome to XAMPP**

✅ **Jika halaman XAMPP muncul, lanjut ke Langkah 2.**

---

## LANGKAH 2: SALIN FILE PROYEK

### 2.1 Copy Folder Proyek
1. Copy seluruh folder `project_skripsi` ke dalam folder htdocs XAMPP:
   ```
   C:\xampp\htdocs\
   ```
2. Hasilnya harus terbentuk path seperti ini:
   ```
   C:\xampp\htdocs\project_skripsi\
   ```

### 2.2 Verifikasi Struktur Folder
Pastikan di dalam `C:\xampp\htdocs\project_skripsi\` terdapat file-file ini:

```
C:\xampp\htdocs\project_skripsi\
├── application\        ← (folder, berisi controllers, models, views, dll)
├── assets\             ← (folder, berisi css dan img)
├── system\             ← (folder, core CodeIgniter)
├── database.sql        ← (FILE INI SANGAT PENTING)
├── index.php           ← (entry point)
└── .htaccess           ← (mungkin tersembunyi)
```

> [!CAUTION]
> Jika file `database.sql` tidak ada di folder tersebut, sistem tidak bisa dibuat.
> Pastikan file ini ikut tercopy.

✅ **Jika folder sudah benar, lanjut ke Langkah 3.**

---

## LANGKAH 3: BUAT DATABASE

### 3.1 Buka phpMyAdmin
1. Pastikan Apache dan MySQL sudah **Running** di XAMPP
2. Buka browser
3. Ketik: `http://localhost/phpmyadmin/`
4. Halaman phpMyAdmin akan terbuka

### 3.2 Import Database
1. Di phpMyAdmin, klik tab **Import** di bagian atas
2. Klik tombol **Choose File** (atau **Pilih File**)
3. Cari dan pilih file:
   ```
   C:\xampp\htdocs\project_skripsi\database.sql
   ```
4. Scroll ke bawah, klik tombol **Go** (atau **Jalankan**)
5. Tunggu sampai muncul pesan hijau: **Import has been successfully finished**

### 3.3 Verifikasi Database
1. Di panel kiri phpMyAdmin, klik database `db_klinik_c45`
2. Pastikan muncul **13 tabel**:

| No | Nama Tabel | Keterangan |
|:--:|:--|:--|
| 1 | `pengguna` | ✅ Harus ada 2 data awal (admin, petugas) |
| 2 | `pasien` | ✅ Harus ada 100 data |
| 3 | `rekam_medis` | ✅ Kosong (akan terisi saat dipakai) |
| 4 | `dataset` | ✅ Harus ada 1 data |
| 5 | `atribut` | ✅ Harus ada 5 data |
| 6 | `data_latih` | ✅ Harus ada 100 data |
| 7 | `data_uji` | ✅ Kosong |
| 8 | `model_klasifikasi` | ✅ Kosong |
| 9 | `pohon_keputusan` | ✅ Kosong |
| 10 | `node_pohon` | ✅ Kosong |
| 11 | `aturan_klasifikasi` | ✅ Kosong |
| 12 | `hasil_klasifikasi` | ✅ Kosong |
| 13 | `log_aktivitas` | ✅ Kosong |

### 3.4 Tambahkan Akun Pemilik (Dokter)
Akun `pemilik` belum ada di file SQL. Anda harus menambahkannya secara manual:

1. Di phpMyAdmin, klik tabel `pengguna`
2. Klik tab **SQL**
3. Paste query berikut:

```sql
INSERT INTO `pengguna` (`username`, `password`, `nama_lengkap`, `level`, `status`)
VALUES ('pemilik', 'pemilik', 'Dr. Pemilik Klinik', 'pemilik', 'aktif');
```

4. Klik **Go**

> [!NOTE]
> Password di atas disimpan dalam bentuk plain text (`pemilik`). Ini hanya untuk demonstrasi/sidang.
> Untuk penggunaan nyata, password harus di-hash menggunakan `password_hash('password_anda', PASSWORD_DEFAULT)`.

✅ **Jika 13 tabel muncul dan tidak ada error, lanjut ke Langkah 4.**

---

## LANGKAH 4: KONFIGURASI APLIKASI

### 4.1 Sesuaikan Base URL
1. Buka file:
   ```
   C:\xampp\htdocs\project_skripsi\application\config\config.php
   ```
2. Cari baris (sekitar baris 26):
   ```php
   $config['base_url'] = 'http://localhost/project_skripsi/';
   ```
3. **Jika folder Anda bernama `project_skripsi`**, biarkan apa adanya
4. **Jika Anda menamai folder berbeda** (misalnya `skripsi_c45`), ubah menjadi:
   ```php
   $config['base_url'] = 'http://localhost/skripsi_c45/';
   ```
5. **Jika Anda menggunakan port 8080** (karena port 80 terblokir), ubah menjadi:
   ```php
   $config['base_url'] = 'http://localhost:8080/project_skripsi/';
   ```

### 4.2 Verifikasi Konfigurasi Database
1. Buka file:
   ```
   C:\xampp\htdocs\project_skripsi\application\config\database.php
   ```
2. Pastikan baris-baris berikut sesuai:
   ```php
   'hostname' => 'localhost',      // Biarkan
   'username' => 'root',           // Default XAMPP
   'password' => '',               // Default XAMPP (kosong)
   'database' => 'db_klinik_c45',  // Harus sama persis
   'dbdriver' => 'mysqli',         // Biarkan
   ```

> [!IMPORTANT]
> Jika Anda menggunakan **WAMP** atau instalasi MySQL kustom yang memiliki password root,
> ganti `'password' => ''` menjadi `'password' => 'password_anda'`.

✅ **Jika konfigurasi sudah benar, lanjut ke Langkah 5.**

---

## LANGKAH 5: JALANKAN SISTEM

### 5.1 Buka Sistem di Browser
1. Pastikan Apache dan MySQL **Running** di XAMPP Control Panel
2. Buka browser (Chrome disarankan)
3. Ketik di address bar:
   ```
   http://localhost/project_skripsi/
   ```
4. Halaman **Login** akan muncul dengan logo Praktik Gigi Mandiri Esensiil

### 5.2 Login dengan Akun Uji Coba

Sistem memiliki **3 akun** dengan peran berbeda:

| Username | Password | Peran | Hak Akses |
|:--|:--|:--|:--|
| `admin` | `admin` | Administrator | Semua menu |
| `petugas` | `petugas` | Bag. Administrasi | Data Pasien, RM, Laporan |
| `pemilik` | `pemilik` | Dokter / Pemilik | RM, Algoritma, Laporan |

> [!NOTE]
> Untuk demonstrasi sidang, coba login dengan ketiga akun secara bergantian
> untuk membuktikan bahwa hak akses (RBAC) berfungsi sesuai skripsi.

### 5.3 Uji Coba Fitur Utama

Setelah login sebagai `admin` atau `pemilik`, lakukan langkah-langkah ini:

#### A. Jalankan Klasifikasi C4.5
1. Klik menu **Klasifikasi → Proses C4.5**
2. Atur **Pembagian Data** (misal: 80%)
3. Klik tombol **MULAI PROSES**
4. Tunggu proses selesai → Lihat akurasi yang muncul

#### B. Lihat Pohon Keputusan
1. Setelah proses selesai, klik **LIHAT POHON KEPUTUSAN**
2. Atau klik menu **Klasifikasi → Pohon Hasil**
3. Pohon akan muncul secara visual. Gunakan tombol +/- untuk zoom

#### C. Lihat Aturan IF-THEN
1. Klik menu **Klasifikasi → Aturan**
2. Daftar aturan akan muncul (contoh: IF Keluhan = K1 AND Usia = Anak THEN T1)

#### D. Uji Prediksi Manual
1. Klik menu **Uji Model**
2. Pilih: Usia, Jenis Kelamin, Keluhan, Riwayat Penyakit
3. Klik **Prediksi** → Sistem akan menampilkan rekomendasi tindakan

✅ **Jika semua fitur di atas berfungsi, instalasi BERHASIL!**

---

## TROUBLESHOOTING (SOLUSI MASALAH)

### ❌ Error: "Unable to connect to your database server"
**Penyebab**: MySQL belum jalan atau konfigurasi salah.
**Solusi**:
1. Buka XAMPP, pastikan MySQL **Running** (hijau)
2. Periksa `application/config/database.php`:
   - `hostname` harus `localhost`
   - `database` harus `db_klinik_c45`
   - `username` harus `root`

### ❌ Error: "404 Page Not Found"
**Penyebab**: URL salah atau folder tidak ditemukan.
**Solusi**:
1. Pastikan folder bernama persis `project_skripsi` di `C:\xampp\htdocs\`
2. Pastikan `base_url` di `config.php` cocok dengan nama folder
3. Pastikan Apache **Running**

### ❌ Error: "Class not found" atau halaman putih
**Penyebab**: PHP extension belum aktif.
**Solusi**:
1. Buka XAMPP, klik **Config** di sebelah Apache
2. Pilih **php.ini**
3. Cari dan pastikan baris-baris berikut **TIDAK** diawali titik koma (`;`):
   ```ini
   extension=mysqli
   extension=pdo_mysql
   extension=mbstring
   extension=openssl
   ```
4. Jika ada titik koma di depan, hapus titik koma tersebut
5. Save file
6. **Restart Apache** (klik Stop lalu Start lagi)

### ❌ Error: "Session path not writable"
**Penyebab**: Folder cache/logs tidak bisa ditulis.
**Solusi**:
1. Buat folder (jika belum ada):
   ```
   C:\xampp\htdocs\project_skripsi\application\cache\
   C:\xampp\htdocs\project_skripsi\application\logs\
   ```
2. Klik kanan folder → Properties → hilangkan centang **Read-only**

### ❌ Port 80 Sudah Dipakai (Apache Tidak Start)
**Penyebab**: Program lain (Skype, IIS, VMware) menggunakan port 80.
**Solusi**:
1. Di XAMPP, klik **Config** → **httpd.conf**
2. Ubah `Listen 80` menjadi `Listen 8080`
3. Ubah `ServerName localhost:80` menjadi `ServerName localhost:8080`
4. Update `base_url` di config.php:
   ```php
   $config['base_url'] = 'http://localhost:8080/project_skripsi/';
   ```

### ❌ Proses C4.5 Tidak Menghasilkan Apa-Apa
**Penyebab**: Data latih kosong atau belum di-import.
**Solusi**:
1. Buka phpMyAdmin → `db_klinik_c45` → tabel `data_latih`
2. Pastikan tabel tersebut berisi **100 baris** data
3. Jika kosong, ulangi import `database.sql` (Langkah 3.2)

### ❌ Halaman Login Muncul Tapi Tidak Bisa Login
**Penyebab**: Data pengguna belum ada di database.
**Solusi**:
1. Buka phpMyAdmin → tabel `pengguna`
2. Pastikan ada minimal 1 baris data
3. Jika kosong, jalankan query SQL ini:
```sql
INSERT INTO `pengguna` (`username`, `password`, `nama_lengkap`, `level`, `status`) VALUES
('admin', 'admin', 'Administrator Sistem', 'admin', 'aktif'),
('petugas', 'petugas', 'Petugas Rekam Medis', 'petugas', 'aktif'),
('pemilik', 'pemilik', 'Dr. Pemilik Klinik', 'pemilik', 'aktif');
```

---

## TIPS UNTUK DEMONSTRASI SIDANG

1. **Jalankan XAMPP** sebelum presentasi dimulai
2. **Buka 3 tab browser** yang sudah login dengan 3 akun berbeda (admin, petugas, pemilik) untuk membuktikan RBAC
3. **Jalankan Proses C4.5** sekali di awal agar ada model yang bisa ditampilkan
4. **Siapkan satu skenario input**: Daftarkan pasien baru → Input RM → Sinkronisasi Dataset → Re-training → Prediksi
5. **Tunjukkan cetak laporan**: Klik print di halaman Laporan untuk menampilkan kop surat

---

## KONTAK PENGEMBANG

Jika mengalami kendala, hubungi pengembang sistem:
- **Nama**: *(Isi nama Anda)*
- **Email**: *(Isi email Anda)*
- **Telepon**: *(Isi nomor Anda)

---

> Panduan ini dibuat untuk instalasi pada komputer baru dengan XAMPP fresh.
> Terakhir diperbarui: Maret 2026
