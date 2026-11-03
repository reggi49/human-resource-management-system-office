# Panduan Akses HRMS (Portal Karyawan & Admin)

Dokumen ini berisi panduan lengkap untuk mengakses **Portal Karyawan (Employee Self-Service)** dan **Portal Admin/HR (Back-Office)**, beserta kredensial default dan fitur masing-masing.

---

## 📌 Ringkasan Cepat URL Akses

Asumsi base URL lokal XAMPP adalah `http://localhost/HRMS-master/` *(sesuaikan jika menggunakan virtual host atau port berbeda)*:

| Portal | URL Akses | Login Menggunakan | Halaman Tujuan Setelah Login |
| :--- | :--- | :--- | :--- |
| **Portal Karyawan** (ESS) | `http://localhost/HRMS-master/portal` *(atau `/portal/login`)* | NIK (`em_code`), ID (`em_id`), atau Email + Password | `http://localhost/HRMS-master/portal` |
| **Portal Admin / HR** | `http://localhost/HRMS-master/login` | Username / Email + Password | `http://localhost/HRMS-master/dashboard` |
| **Kiosk Presensi Mandiri** | `http://localhost/HRMS-master/presence` | Standalone Kiosk (Face / Live Log) | Halaman Absensi Live |
| **Portal Karir / Pelamar** | `http://localhost/HRMS-master/careers` | Terbuka untuk umum (Pelamar Kerja) | Halaman Lowongan & Lamaran |
| **Company Profile / Landing** | `http://localhost/HRMS-master/` | Terbuka untuk publik | Profil Perusahaan |

---

## 1. Portal Karyawan (Employee Self-Service / ESS)

Portal khusus untuk staf / karyawan mandiri dalam melihat rekap kehadiran, mengajukan cuti, klaim reimbursement, melihat slip gaji, dsb.

### 🔗 URL Akses:
* **Halaman Portal / Login:**
  ```
  http://localhost/HRMS-master/portal
  ```
  *(Jika belum login, otomatis diarahkan ke `http://localhost/HRMS-master/portal/login`)*

### 🔑 Format Kredensial:
* **NIK / Email:** Bisa memasukkan salah satu dari:
  * NIK / Employee Code (`em_code`, contoh: `123444`)
  * Employee ID (`em_id`, contoh: `Doe1754`)
  * Email Karyawan (`em_email`, contoh: `employee@email.com`)
* **Password:** Password akun karyawan.

### 👤 Akun Demo Karyawan (Bawaan Database):
* **Email / NIK:** `employee@email.com` *(atau NIK: `123444` / ID: `Doe1754`)*
* **Password:** `admin123456`

### 🛠️ Fitur Utama di Portal Karyawan:
1. **Clock-In / Status Kehadiran Hari Ini:** Mengetahui status absensi masuk & pulang harian.
2. **Riwayat Absensi Bulanan:** Rekap data log kehadiran, terlambat, maupun izin.
3. **Pengajuan & Sisa Cuti (Leave Request):** Cek kuota cuti tahunan dan pengajuan permohonan izin/cuti.
4. **Pengajuan Pinjaman (Loan Request):** Pengajuan pinjaman internal karyawan & cicilan.
5. **Pengajuan Reimbursement (OCR AI):** Unggah bukti kuitansi pengeluaran dengan bantuan ekstraksi teks AI.
6. **Slip Gaji (Payslip):** Melihat dan mengunduh slip gaji bulanan.
7. **Daftar Tugas & Proyek:** Melihat proyek yang ditugaskan ke karyawan.

---

## 2. Portal Admin & HR (Back-Office HRMS)

Portal untuk Super Admin, Admin HR, dan Manajemen dalam mengelola data master, absensi seluruh karyawan, penggajian, rekrutmen, dan konfigurasi sistem.

### 🔗 URL Akses:
* **Halaman Login Admin:**
  ```
  http://localhost/HRMS-master/login
  ```

### 🔑 Format Kredensial:
* **Username / Email:** Email atau username akun admin (contoh: `admin@email.com` atau `superadmin@email.com`)
* **Password:** Password akun admin.

### 👤 Akun Demo Admin (Bawaan Database):
#### **A. Super Admin:**
* **Email:** `superadmin@email.com`
* **Password:** `admin123456`
* **Role:** `SUPER ADMIN` *(Akses penuh ke semua modul, konfigurasi sistem, dan manajemen hak akses)*

#### **B. Admin HR:**
* **Email:** `admin@email.com`
* **Password:** `admin123456`
* **Role:** `ADMIN` *(Akses operasional harian HR, karyawan, presensi, cuti, payroll)*

### 🛠️ Fitur Utama di Portal Admin:
1. **Dashboard Manajemen:** Statistik karyawan, grafik kehadiran, grafik cuti, dan notifikasi pending approval.
2. **Manajemen Karyawan (Employee):** Tambah, ubah, nonaktifkan karyawan, atur jabatan/departemen, data keluarga, dan dokumen.
3. **Presensi & Jadwal (Attendance):** Monitor absensi harian, import data mesin fingerprint/kiosk, rekapitulasi keterlambatan.
4. **Manajemen Cuti (Leave Management):** Approval atau penolakan pengajuan cuti staf, pengaturan tipe cuti.
5. **Penggajian & PPh 21 (Payroll):** Generate gaji bulanan, perhitungan tunjangan/potongan, hitungan pajak PPh 21 TER, dan cetak slip gaji masal.
6. **Approval Reimbursement:** Verifikasi dan persetujuan klaim kuitansi biaya operasional karyawan.
7. **Rekrutmen (Recruitment & Careers):** Manajemen lowongan kerja, review berkas pelamar, dan tahapan seleksi kandidat.
8. **KPI & Penilaian Kinerja:** Konfigurasi indikator KPI dan evaluasi performa kerja karyawan.
9. **Pengaturan Sistem (Settings):** Identitas perusahaan, jam kerja, logo, serta hak akses (RBAC).

---

## 3. Layanan Tambahan Lainnya

### 🕒 Kiosk Presensi Mandiri (`/presence`)
* **URL:** `http://localhost/HRMS-master/presence`
* **Deskripsi:** Layanan presensi standalone yang biasanya dibuka di tablet/layar kantor untuk absensi harian secara langsung dengan rekap real-time hari ini.

### 💼 Portal Karir Publik (`/careers`)
* **URL:** `http://localhost/HRMS-master/careers`
* **Deskripsi:** Halaman publik daftar lowongan pekerjaan aktif tempat calon pelamar dapat melihat kualifikasi dan mengirimkan lamaran kerja (CV).

---

## 4. Troubleshooting & Catatan Teknis

### Lupa Password / Reset Password Manual lewat Database:
Sistem menggunakan algoritma enkripsi **SHA-1** untuk password pada tabel `employee`.
Jika Anda perlu mereset password pengguna secara langsung melalui database (misal di phpMyAdmin):
1. Buka database (contoh: `gen-hr` atau database aktif).
2. Jalankan query berikut untuk mereset password menjadi `admin123456`:
   ```sql
   UPDATE `employee` 
   SET `em_password` = SHA1('admin123456') 
   WHERE `em_email` = 'email_user@example.com';
   ```
*(Catatan: Hash SHA-1 untuk `admin123456` adalah `cd5ea73cd58f827fa78eef7197b8ee606c99b2e6`)*

---

## 5. Master Data Karyawan Digital Production Division & Akun Login

Telah ditambahkan struktur tabel **`division`** dan relasi `division_id`, serta kolom `emp_no` (No urut), `birth_place` (Tempat Lahir), dan `ttl` (Tempat Tanggal Lahir) pada tabel **`employee`**.

Semua karyawan berikut dapat login langsung ke **Portal Karyawan** (`http://localhost/HRMS-master/portal`) menggunakan **NIK** atau **Email** dengan password default: `admin123456`.

| No | NIK | Nama Karyawan | Tempat, Tanggal Lahir | Divisi | Jabatan (Position) | Sejak (Since) | No. HP | Email Login | Role / Akses |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| **15** | `20091105` | **Edi Pamungkas** | Salatiga, 05 Juli 1969 | Digital Production Division (05) | Digital Production Manager | 2009-11-23 | 0812-1059-117 | `edipamungkas@email.com` | `ADMIN` |
| **16** | `20150605` | **Muhamad Arif Erlangga** | Jakarta, 11 Juni 1975 | Digital Production Division (05) | Digital Production Supervisor | 2015-06-08 | 0812-1213-3663 | `ariferlangga@email.com` | `EMPLOYEE` |
| **17** | `20140705` | **Hari Windariyadi** | Lumajang, 10 Juli 1976 | Digital Production Division (05) | Digital Production Video Editor | 2014-07-01 | 0818-8084-94393 | `hariwindariyadi@email.com` | `EMPLOYEE` |
| **18** | `20150305` | **Ahmad Ridwan Elgifary** | Jakarta, 24 Juni 1977 | Digital Production Division (05) | Digital Production Video Editor | 2015-03-02 | 0878-7867-9390 | `ahmadelgifary@email.com` | `EMPLOYEE` |
| **19** | `20180405` | **Muhamad Reggi** | Bogor, 08 Mei 1993 | Digital Production Division (05) | Digital Production Programmer | 2018-04-02 | 0857-8205-7092 | `muhamadreggi@email.com` | `EMPLOYEE` |
| **20** | `20201205` | **Bramastyo Kusumo** | Mojokerto, 3 Mei 1992 | Digital Production Division (05) | Digital Production Programmer | 2020-12-01 | 0821-1113-1902 | `bramastyokusumo@email.com` | `EMPLOYEE` |
| **21** | `20210105` | **Aris Afrianto** | Cirebon, 23 April 1996 | Digital Production Division (05) | Digital Production Programmer | 2021-01-04 | 0822-1876-9546 | `arisafrianto@email.com` | `EMPLOYEE` |
| **22** | `20240805` | **Muhammad Kamil Idris** | Jakarta, 01 Oktober 1999 | Digital Production Division (05) | Digital Production Cameraman | 2024-08-12 | 08215477944 | `kamilidris@email.com` | `EMPLOYEE` |
| **36** | `20120307` | **Akhbar Bhakti Banuadji** | Jakarta, 29 Desember 1978 | Digital Production Division (07) | Digital Production Staff | 2012-03-01 | 021-86611218 | `akhbarbanuadji@email.com` | `EMPLOYEE` |

> [!NOTE]
> File skrip migrasi database SQL tersimpan di: [database/add_division_and_employees.sql](file:///Applications/XAMPP/xamppfiles/htdocs/HRMS-master/database/add_division_and_employees.sql).

