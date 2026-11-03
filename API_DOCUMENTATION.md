# 📱 HRMS Mobile API Documentation (Flutter Integration)

Dokumentasi API ini digunakan untuk mengintegrasikan aplikasi mobile **Flutter** (Absensi Karyawan & Dashboard Seluler) dengan sistem backend **HRMS CodeIgniter**.

---

## 📌 Base URL
```text
http://<IP_SERVER_ATAU_DOMAIN>/Api/
```
> *Catatan*: Pada pengujian lokal emulator Android, gunakan `http://10.0.2.2/Api/` atau IP lokal Wi-Fi (misal: `http://192.168.1.100/Api/`).

---

## 🌐 Daftar Endpoint API

| No | Endpoint | Method | Deskripsi |
|---|---|---|---|
| 1 | `/Api/login` | `POST` | Autentikasi / Login Karyawan & Admin via Email/NIK & Password |
| 2 | `/Api/cekNIK` | `GET` | Verifikasi ID/NIK Karyawan & profil (Nama, Departemen, Jabatan) |
| 3 | `/Api/saveAttendance` | `POST` | Simpan Presensi (Clock In/Out + GPS Geofencing + Biometric Face Matching) |
| 4 | `/Api/getAttendance` | `GET` | Cek status absensi karyawan pada hari ini |
| 5 | `/Api/getAttendanceHistory` | `GET` | Ambil histori rekam absensi hari-hari sebelumnya |
| 6 | `/Api/getDataCompany` | `GET` | Ambil titik koordinat kantor (Latitude & Longitude) untuk geofencing |
| 7 | `/Api/getSalary` | `GET` | Ambil data slip gaji karyawan per periode (bulan/tahun): bruto, potongan, netto |
| 8 | `/Api/getSalaryHistory` | `GET` | Ambil seluruh riwayat slip gaji karyawan (semua periode) |
| 9 | `/Api/registerFace` | `POST` | Registrasi Biometrik Wajah Master Karyawan (512D Vector Embedding) |
| 10 | `/Api/checkFaceStatus` | `GET` | Cek Status Registrasi Biometrik Wajah Karyawan |
| 11 | `/Api/deleteFace` | `POST` | Hapus Data Biometrik Wajah Master Karyawan (Reset / Daftar Ulang) |

---

## 📄 Spesifikasi Detail Endpoint

### 1. Login Karyawan / Admin
Mengotentikasi pengguna menggunakan Email atau NIK/Kode Karyawan (`em_code` / `em_id`) dan Password.

* **URL**: `/Api/login`
* **Method**: `POST`
* **Content-Type**: `application/x-www-form-urlencoded` / `multipart/form-data`
* **Body Parameters**:
  - `email` *(string, required)*: Email Karyawan / NIK / Kode Karyawan (`em_email` atau `em_code` atau `em_id`)
  - `password` *(string, required)*: Password akun (Plaintext, server otomatis meng-encrypt menggunakan SHA-1)

> 🔐 **Informasi Enkripsi Password**: Password di tabel `employee` dienkripsi menggunakan algoritma **SHA-1** (`sha1($password)`).
> Contoh Hash: `cd5ea73cd58f827fa78eef7197b8ee606c99b2e6` (Hash SHA-1 dari string `admin123456`).

#### 📤 Request Body Example
```text
email: superadmin@email.com
password: admin123456
```

#### 📥 Response Success (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Login berhasil",
  "data": {
    "id": "10",
    "em_id": "Soy1332",
    "em_code": "99",
    "first_name": "Bagus",
    "last_name": "Nagabonar",
    "emp_name": "Bagus Nagabonar",
    "em_email": "superadmin@email.com",
    "em_role": "SUPER ADMIN",
    "em_phone": "01723177901",
    "department": "-",
    "designation": "-",
    "em_image": "http://localhost/assets/images/users/Soy1332.jpg",
    "status": "ACTIVE"
  }
}
```

#### 📥 Response Failed (`400 Bad Request`)
```json
{
  "status": "400",
  "success": false,
  "message": "Email/NIK atau Password salah atau akun tidak aktif",
  "data": null
}
```

---

### 2. Verifikasi / Cek Data Karyawan
Mengecek ketersediaan ID Karyawan (`em_id`) dan mengembalikan nama lengkap, departemen, serta jabatan.

* **URL**: `/Api/cekNIK`
* **Method**: `GET`
* **Headers**: `Content-Type: application/json`
* **Query Parameters**:
  - `nik` *(string, required)*: ID / NIP Karyawan (contoh: `12345`)

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/cekNIK?nik=12345
```

#### 📥 Response Success (`200 OK`)
```json
{
  "message": "Data Ditemukan (Jhon Doe)",
  "status": "200",
  "success": true,
  "data": [
    {
      "emp_name": "Jhon Doe",
      "dept": "Administration",
      "jabatan": "HR Manager"
    }
  ]
}
```

#### 📥 Response Data Not Found (`200 OK`)
```json
{
  "message": "ID Karyawan 99999 Tidak Ditemukan",
  "status": "200",
  "success": true,
  "data": []
}
```

---

### 2. Simpan Presensi (Clock In / Clock Out + Biometric Face Verification & Geofencing)
Memperbarui endpoint `saveAttendance` untuk mendukung verifikasi biometrik wajah (Face Matching) dan validasi jarak radius Geofence (Maksimal 30 meter dari lokasi kantor).

* **URL**: `/Api/saveAttendance`
* **Method**: `POST`
* **Content-Type**: `multipart/form-data` / `application/x-www-form-urlencoded`
* **Request Parameters**:
  - `nik` *(string, required)*: NIK / ID Karyawan (`em_code` / `em_id`)
  - `signout_time` *(string, optional)*: Isi `""` jika Clock In, isi `"out"` jika Clock Out
  - `lat` *(string, required)*: Latitude koordinat GPS perangkat
  - `lng` *(string, required)*: Longitude koordinat GPS perangkat
  - `image` *(file, optional/required)*: Foto selfie presensi karyawan saat ini (Disimpan di `/uploads/attendance/{nik}_{timestamp}.jpg`)

#### ⚙️ Security & Validation Logic:
1. **Validasi Geofence Radius**: Hitung jarak GPS perangkat terhadap lokasi kantor (Maksimal 30 meter). Jika > 30m ➔ Reject `400 Bad Request`.
2. **Validasi Biometrik Wajah**: Cek Master 512D Vector Signature di tabel `employee_face_signatures`. Jika belum daftar ➔ Reject `400`. Hitung Cosine Similarity antara Live Vector vs Master Vector (Threshold >= 80%). Jika < 80% ➔ Reject `400`.
3. **Simpan Log Foto**: Foto presensi disimpan di `/uploads/attendance/{nik}_{timestamp}.jpg` dan URL disimpan di kolom `photo_url`.

#### 📤 Request Body Example (Multipart Form-Data)
```text
nik: 12345
signout_time: 
lat: -6.160981
lng: 106.904365
image: (binary selfie photo)
```

#### 📥 Response Success (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Absensi berhasil dicatat",
  "data": {
    "emp_id": "12345",
    "attend_date": "2026-08-01",
    "similarity_score": "94.2%",
    "photo_url": "http://192.168.3.57/HRMS-master/uploads/attendance/12345_20260801_0800.jpg"
  }
}
```

#### 📥 Response Gagal Diluar Radius (`400 Bad Request`)
```json
{
  "status": "400",
  "success": false,
  "message": "Gagal: Anda di luar radius presensi kantor (Maksimal 30 meter)"
}
```

#### 📥 Response Gagal Wajah Berbeda (`400 Bad Request`)
```json
{
  "status": "400",
  "success": false,
  "message": "Verifikasi Wajah Gagal: Wajah Anda tidak cocok dengan data biometrik terdaftar!"
}
```

---

### 9. Registrasi Biometrik Wajah Master (Enrollment)
Mengirimkan foto wajah karyawan untuk diekstrak menjadi Master 512D Vector Signature yang disimpan di tabel `employee_face_signatures`. Demi kebijakan privasi, foto mentah registrasi langsung di-purge dari memori/disk.

* **URL**: `/Api/registerFace`
* **Method**: `POST`
* **Content-Type**: `multipart/form-data`
* **Request Parameters**:
  - `nik` *(string, required)*: NIK / ID Karyawan (`em_code` / `em_id`)
  - `image_front` *(file, required)*: Foto wajah tampak depan (lurus)
  - `image_left` *(file, required)*: Foto wajah tampak tengok kiri (~20°)
  - `image_right` *(file, required)*: Foto wajah tampak tengok kanan (~20°)
  - `vector_data` *(string, optional)*: String JSON array float embedding (jika pre-extracted oleh mobile)

#### 📥 Response Success (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Pendaftaran biometrik wajah master berhasil disimpan"
}
```

#### 📥 Response Error Wajah Tidak Jelas (`400 Bad Request`)
```json
{
  "status": "400",
  "success": false,
  "message": "Wajah pada foto tidak terdeteksi dengan jelas. Pastikan cahaya cukup dan posisi pas."
}
```

---

### 10. Cek Status Registrasi Wajah
Mengecek apakah karyawan dengan NIK ini sudah pernah mendaftarkan biometrik wajahnya atau belum.

* **URL**: `/Api/checkFaceStatus`
* **Method**: `GET`
* **Query Parameters**:
  - `nik` *(string, required)*: NIK / ID Karyawan

#### 📥 Response Status Registered (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "data": {
    "nik": "12345",
    "is_registered": true,
    "registered_at": "2026-08-01 10:00:00"
  }
}
```

#### 📥 Response Status Not Registered (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "data": {
    "nik": "12345",
    "is_registered": false,
    "registered_at": null
  }
}
```

---

### 11. Hapus Biometrik Wajah Master (Delete / Reset Face Data)
Menghapus data 512D Master Vector Signature karyawan dari tabel `employee_face_signatures` sehingga karyawan dapat melakukan registrasi biometrik ulang dari awal.

* **URL**: `/Api/deleteFace`
* **Method**: `POST`
* **Content-Type**: `application/x-www-form-urlencoded` / `multipart/form-data`
* **Request Parameters**:
  - `nik` *(string, required)*: NIK / ID Karyawan (`em_code` / `em_id`)

#### 📤 Request Body Example
```text
nik: 12345
```

#### 📥 Response Success (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Data biometrik wajah master berhasil dihapus. Silakan lakukan pendaftaran ulang."
}
```

#### 📥 Response NIK Tidak Ditemukan (`400 Bad Request`)
```json
{
  "status": "400",
  "success": false,
  "message": "Data biometrik tidak ditemukan untuk NIK ini"
}
```

---

### 3. Cek Status Presensi Hari Ini
Mengambil data jam masuk dan jam keluar karyawan pada hari berjalan.

* **URL**: `/Api/getAttendance`
* **Method**: `GET`
* **Query Parameters**:
  - `nik` *(string, required)*: ID Karyawan (`em_id`)

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/getAttendance?nik=12345
```

#### 📥 Response Success (Sudah Absen) (`200 OK`)
```json
{
  "message": "Data Ditemukan",
  "status": "200",
  "success": true,
  "data": [
    {
      "emp_id": "12345",
      "emp_name": "Jhon Doe",
      "attend_date": "2026-07-31",
      "starttime": "2026-07-31 08:00:00",
      "endtime": "2026-07-31 17:01:45"
    }
  ]
}
```

#### 📥 Response Success (Belum Absen) (`200 OK`)
```json
{
  "message": "Belum Melakukan Absensi",
  "status": "200",
  "success": true,
  "data": []
}
```

---

### 4. Histori Absensi Karyawan
Mengambil rekam histori presensi hari-hari terdahulu (sebelum hari ini).

* **URL**: `/Api/getAttendanceHistory`
* **Method**: `GET`
* **Query Parameters**:
  - `nik` *(string, required)*: ID Karyawan (`em_id`)

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/getAttendanceHistory?nik=12345
```

#### 📥 Response Success (`200 OK`)
```json
{
  "message": "Data Ditemukan",
  "status": "200",
  "success": true,
  "data": [
    {
      "attend_date": "2026-07-30",
      "signin_time": "2026-07-30 07:55:00",
      "signout_time": "2026-07-30 17:05:00",
      "lat_in": "-6.2088000",
      "lng_in": "106.8456000",
      "lat_out": "-6.2088100",
      "lng_out": "106.8456100"
    },
    {
      "attend_date": "2026-07-29",
      "signin_time": "2026-07-29 08:02:10",
      "signout_time": "2026-07-29 17:00:20",
      "lat_in": "-6.2088000",
      "lng_in": "106.8456000",
      "lat_out": "-6.2088100",
      "lng_out": "106.8456100"
    }
  ]
}
```

---

### 5. Data Koordinat Kantor (Geofencing GPS)
Mengambil koordinat pusat kantor untuk menghitung jarak radius presensi di Flutter.

* **URL**: `/Api/getDataCompany`
* **Method**: `GET`
* **Query Parameters**:
  - `id` *(string, optional)*: ID Perusahaan (opsional, kosongkan untuk mengambil lokasi default)

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/getDataCompany
```

#### 📥 Response Success (`200 OK`)
```json
{
  "message": "Data Ditemukan",
  "status": "200",
  "success": true,
  "data": [
    {
      "lat": "-6.2088000",
      "lng": "106.8456000"
    }
  ]
}
```

---

### 7. Ambil Slip Gaji Per Periode (Salary)
Mengambil data slip gaji karyawan untuk satu periode tertentu (bulan & tahun). Mengembalikan **bruto**, **potongan**, dan **netto**.

* **URL**: `/Api/getSalary`
* **Method**: `GET`
* **Query Parameters**:
  - `nik` *(string, required)*: ID Karyawan (`em_id`)
  - `month` *(string/int, optional)*: Bulan — bisa angka (`1`-`12`) atau nama bulan (`January`, `February`, dst.). Default: bulan saat ini.
  - `year` *(string, optional)*: Tahun (contoh: `2026`). Default: tahun saat ini.

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/getSalary?nik=12345&month=7&year=2026
```

#### 📥 Response Success (Data Ditemukan) (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Data gaji ditemukan",
  "data": {
    "pay_id": "15",
    "emp_name": "Jhon Doe",
    "em_id": "12345",
    "em_code": "99",
    "department": "Administration",
    "designation": "HR Manager",
    "periode": "July 2026",
    "month": "July",
    "year": "2026",
    "bruto": 6800000,
    "potongan": 450000,
    "netto": 6350000,
    "detail": {
      "gaji_pokok": 5000000,
      "uang_makan": 1150000,
      "tunjangan": 400000,
      "bonus": 250000,
      "bpjs": 272000,
      "pph21": 128000,
      "pinjaman": 50000
    },
    "status": "Paid",
    "paid_date": "2026-07-25",
    "paid_type": "Transfer Bank"
  }
}
```

#### 📥 Response Data Belum Tersedia (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Data gaji belum tersedia untuk periode July 2026",
  "data": null
}
```

---

### 8. Riwayat Slip Gaji (Salary History)
Mengambil **seluruh riwayat** slip gaji karyawan dari semua periode yang tersimpan.

* **URL**: `/Api/getSalaryHistory`
* **Method**: `GET`
* **Query Parameters**:
  - `nik` *(string, required)*: ID Karyawan (`em_id`)

#### 📤 Request Example (URL)
```http
GET http://localhost/Api/getSalaryHistory?nik=12345
```

#### 📥 Response Success (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Ditemukan 3 data gaji",
  "data": [
    {
      "pay_id": "15",
      "emp_name": "Jhon Doe",
      "em_id": "12345",
      "periode": "July 2026",
      "month": "July",
      "year": "2026",
      "bruto": 6800000,
      "potongan": 450000,
      "netto": 6350000,
      "detail": {
        "gaji_pokok": 5000000,
        "uang_makan": 1150000,
        "tunjangan": 400000,
        "bonus": 250000,
        "bpjs": 272000,
        "pph21": 128000,
        "pinjaman": 50000
      },
      "status": "Paid",
      "paid_date": "2026-07-25",
      "paid_type": "Transfer Bank"
    },
    {
      "pay_id": "12",
      "emp_name": "Jhon Doe",
      "em_id": "12345",
      "periode": "June 2026",
      "month": "June",
      "year": "2026",
      "bruto": 6550000,
      "potongan": 422000,
      "netto": 6128000,
      "detail": {
        "gaji_pokok": 5000000,
        "uang_makan": 1000000,
        "tunjangan": 400000,
        "bonus": 150000,
        "bpjs": 272000,
        "pph21": 100000,
        "pinjaman": 50000
      },
      "status": "Paid",
      "paid_date": "2026-06-25",
      "paid_type": "Transfer Bank"
    }
  ]
}
```

#### 📥 Response Belum Ada Riwayat (`200 OK`)
```json
{
  "status": "200",
  "success": true,
  "message": "Belum ada riwayat gaji untuk karyawan ini",
  "data": []
}
```

---

## 🚀 Contoh Implementasi Flutter (Dart)

Berikut adalah contoh penulisan **Service Class** di Flutter menggunakan package `http`:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class HrmsApiService {
  static const String baseUrl = "http://10.0.2.2/Api";

  // 1. Cek NIK / Profil Karyawan
  static Future<Map<String, dynamic>?> checkNik(String nik) async {
    final response = await http.get(Uri.parse('$baseUrl/cekNIK?nik=$nik'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  // 2. Simpan Absensi (Clock In / Clock Out)
  static Future<Map<String, dynamic>?> submitAttendance({
    required String nik,
    required double lat,
    required double lng,
    bool isClockOut = false,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/saveAttendance'),
      body: {
        'nik': nik,
        'signout_time': isClockOut ? 'out' : '',
        'lat': lat.toString(),
        'lng': lng.toString(),
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  // 3. Get Status Absensi Hari Ini
  static Future<Map<String, dynamic>?> getTodayAttendance(String nik) async {
    final response = await http.get(Uri.parse('$baseUrl/getAttendance?nik=$nik'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  // 4. Get Histori Absensi
  static Future<Map<String, dynamic>?> getAttendanceHistory(String nik) async {
    final response = await http.get(Uri.parse('$baseUrl/getAttendanceHistory?nik=$nik'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  // 5. Get Slip Gaji Per Periode (Bruto, Potongan, Netto)
  static Future<Map<String, dynamic>?> getSalary({
    required String nik,
    int? month,
    int? year,
  }) async {
    String url = '$baseUrl/getSalary?nik=$nik';
    if (month != null) url += '&month=$month';
    if (year != null) url += '&year=$year';

    final response = await http.get(Uri.parse(url));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  // 6. Get Riwayat Slip Gaji Seluruh Periode
  static Future<Map<String, dynamic>?> getSalaryHistory(String nik) async {
    final response = await http.get(Uri.parse('$baseUrl/getSalaryHistory?nik=$nik'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }
}
```

