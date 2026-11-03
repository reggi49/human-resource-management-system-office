# Human Resource Management System Office (HRMS Office)

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Framework: CodeIgniter 3](https://img.shields.io/badge/Framework-CodeIgniter%203-orange.svg)](https://codeigniter.com)
[![AI Engine: InsightFace](https://img.shields.io/badge/AI%20Biometrics-InsightFace%20ArcFace-brightgreen.svg)](https://github.com/deepinsight/insightface)
[![Tax: PPh 21 TER 2024](https://img.shields.io/badge/Payroll-PPh%2021%20TER%20PP%2058%2F2023-blueviolet.svg)](https://pajak.go.id)

**Human Resource Management System Office** adalah platform tata kelola sumber daya manusia (SDM) dan operasional perkantoran modern yang komprehensif. Sistem ini dirancang untuk mengotomasi seluruh siklus manajemen karyawan mulai dari presensi biometrik berbasis kecerdasan buatan (AI), otomasi penggajian dan kalkulasi pajak PPh 21 TER terbaru, klaim reimbursement berbasis OCR AI, hingga rekrutmen dan portal mandiri karyawan (Employee Self-Service).

---

## 🌟 Keunggulan & Fitur Unggulan

### 1. 🤖 Presensi Biometrik Wajah AI & Anti-Spoofing (Face Recognition Engine)
* **InsightFace ArcFace (Buffalo_L) 512-D:** Ekstraksi vektor fitur wajah berkepresisian tinggi dengan normalisasi L2.
* **Multi-Pose Verification:** Pendaftaran 5 sudut pose wajah (Tampak Depan, Atas, Bawah, Kiri, dan Kanan) untuk akurasi pencocokan maksimal dan meminimalisir kesalahan deteksi.
* **Triple-Layer Anti-Spoofing:**
  * **FFT High-Frequency Analysis:** Deteksi pola grid piksel/moiré layar monitor, tablet, atau smartphone.
  * **Laplacian Sharpness & Edge Variance:** Pencegahan foto cetak kertas (print-out attack) dan foto buram.
  * **YCrCb Chrominance Distribution:** Validasi persebaran warna dan tekstur alami kulit manusia.
* **Standalone Kiosk Presensi Mandiri (`/presence`):** Antarmuka absensi langsung tanpa login, siap digunakan pada tablet atau monitor di lobi kantor.

### 2. 🧾 Smart Reimbursement OCR AI (Multimodal Financial Vision)
* **Pindai Kuitansi & Nota Otomatis:** Cukup unggah foto kuitansi atau dokumen PDF multi-halaman.
* **Chain-of-Thought Parsing:** Ekstraksi otomatis nama merchant, tanggal transaksi, rincian biaya per nota, kategori pengeluaran, dan kalkulasi total nominal tanpa perlu input manual.

### 3. 💰 Penggajian & Pajak PPh 21 TER Terotomasi
* **Kepatuhan Pajak PP 58/2023 & PMK 168/2023:** Perhitungan dinamis tarif efektif rata-rata (TER Kategori A, B, dan C) sesuai ketentuan perpajakan Indonesia terbaru.
* **Kalkulasi Payroll Terpadu:** Penggabungan otomatis gaji pokok, tunjangan jabatan/kehadiran, lembur, insentif, potongan cicilan pinjaman kasbon, dan cetak slip gaji resmi karyawan.

### 4. 👥 Portal Mandiri Karyawan (Employee Self-Service / ESS)
* **Dashboard Karyawan (`/portal`):** Rekapitulasi absensi bulanan, status keterlambatan, dan jam kerja riil.
* **Pengajuan Izin & Cuti Online:** Pengajuan cuti terintegrasi dengan saldo kuota tahunan dan riwayat persetujuan HR.
* **Pengajuan Pinjaman (Loan Request):** Transparansi permohonan pinjaman internal perusahaan beserta jadwal potongan tenor.
* **Unduh Slip Gaji Elektronik:** Akses langsung rincian payslip bulanan karyawan.

### 5. 🛡️ Matrix Role-Based Access Control (RBAC)
* Pembagian hak akses terperinci (*granular permissions*) antara Super Admin, Admin HR, Manajer Divisi, dan Karyawan.
* Kontrol modul ketat untuk menjaga kerahasiaan data keuangan, payroll, dan informasi personal.

### 6. 💼 Portal Rekrutmen Publik & AI CV Screening
* **Lowongan Karir Terbuka (`/careers`):** Halaman publik daftar lowongan pekerjaan aktif perusahaan.
* **Penerimaan & Evaluasi Lamaran:** Penyimpanan berkas pelamar terstruktur dan screening CV terotomasi.

### 7. 📱 Mobile API Siap Pakai (Android & iOS Integration)
* RESTful API lengkap untuk presensi mobile karyawan.
* **Geofencing & Koordinat GPS:** Validasi radius kantor (latitude & longitude) untuk memastikan karyawan berada di lokasi yang ditentukan saat melakukan absensi.

---

## 🏛️ Arsitektur Sistem

```
┌────────────────────────────────────────────────────────┐
│               Karyawan & Administrator                 │
│  (Browser Desktop, Tablet Kiosk, Mobile App Android)   │
└──────────────────────────┬─────────────────────────────┘
                           │ HTTP / JSON REST API
┌──────────────────────────▼─────────────────────────────┐
│    Human Resource Management System Office Backend     │
│                 (PHP / CodeIgniter 3)                  │
├──────────────────────────┬─────────────────────────────┤
│ • Manajemen Karyawan     │ • Portal ESS Karyawan       │
│ • Payroll & PPh 21 TER   │ • RBAC & Pengaturan         │
│ • Pengajuan Cuti & Pinjam│ • Mobile RESTful Endpoints  │
└────────────┬─────────────────────────────┬─────────────┘
             │ HMAC Auth                   │ SQL Queries
┌────────────▼─────────────┐ ┌─────────────▼─────────────┐
│  AI Face Recognition     │ │      Database Server      │
│     Sidecar Engine       │ │    (MySQL / MariaDB)      │
│ (Python FastAPI, PyTorch,│ └───────────────────────────┘
│  InsightFace Buffalo_L)  │
└──────────────────────────┘
```

---

## 🚀 Panduan Instalasi & Menjalankan Sistem

### 1. Kebutuhan Sistem
* PHP 7.4 - 8.2 (dengan ekstensi `mysqli`, `curl`, `json`, `mbstring`, `gd`, `openssl`)
* Web Server (Apache / Nginx / XAMPP)
* MySQL 5.7+ atau MariaDB 10.4+
* Python 3.9+ (Opsional: untuk menjalankan AI Sidecar Face Recognition)

### 2. Konfigurasi Lingkungan (.env)
Salin template konfigurasi dan sesuaikan nilai kredensial lokal Anda:
```bash
cp .env.example .env
```
Isi konfigurasi database dan integrasi AI pada file `.env`:
```ini
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=hrms_lokal

# AI Receipt OCR Configuration
AI_OCR_API_KEY=your_api_key_here
AI_OCR_PROVIDER=nararouter

# Face Recognition Sidecar Engine
FACE_SECRET=your_random_64_char_secret
FACE_SERVICE_URL=http://127.0.0.1:8000
```

### 3. Setup Database
1. Buat database baru di MySQL (misal: `hrms_lokal`).
2. Impor struktur dan data awal:
   ```bash
   mysql -u root -p hrms_lokal < hrms_full.sql
   mysql -u root -p hrms_lokal < database_setup.sql
   ```

### 4. Menjalankan AI Face Recognition Sidecar (Opsional)
```bash
# Masuk ke virtual environment Python
pip install -r face_service/requirements.txt
bash start_sidecar.sh
```

---

## 🔑 Akun Demo Bawaan

| Role | Username / Email | Password Default | Akses Portal |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin@email.com` | `admin123456` | `/login` (Full Access) |
| **Admin HR** | `admin@email.com` | `admin123456` | `/login` (Back-Office HR) |
| **Karyawan** | `employee@email.com` | `admin123456` | `/portal` (Employee Self-Service) |

---

## 📖 Dokumentasi Lanjutan
* Panduan Rute & Akses: [guide.md](guide.md)
* Spesifikasi API Mobile: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
* Panduan Kontribusi: [contributing.md](contributing.md)

---

## 📄 Lisensi
Proyek ini didistribusikan di bawah lisensi **MIT License** — silakan baca file [LICENSE](LICENSE) untuk ketentuan lengkapnya.
