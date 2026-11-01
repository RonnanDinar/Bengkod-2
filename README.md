# Dokumentasi Pengerjaan Penugasan Aplikasi Poliklinik

Dokumentasi ini merangkum pengembangan arsitektur, logika bisnis, dan penambahan fitur pada sistem aplikasi Poliklinik berbasis **Laravel 12**:
1. **CRUD Manajemen Obat** (Role: **Admin**)
2. **CRUD Jadwal Periksa** (Role: **Dokter**)
3. **Pendaftaran Poli / Periksa** (Role: **Pasien**)
4. **Pemeriksaan Pasien & Resep Obat** (Role: **Dokter**)
5. **Riwayat Pemeriksaan Pasien** (Role: **Dokter**)
6. **Navigasi Sidebar Dinamis & Responsive Layout**

---

## Daftar Isi
1. [Akun Pengujian (Kredensial Default)](#1-akun-pengujian-kredensial-default)
2. [Arsitektur Perutean (routes/web.php)](#2-arsitektur-perutean-routeswebphp)
3. [Fitur Pendaftaran Poli (Role Pasien)](#3-fitur-pendaftaran-poli-role-pasien)
4. [Fitur Pemeriksaan Pasien (Role Dokter)](#4-fitur-pemeriksaan-pasien-role-dokter)
5. [Fitur Riwayat Pasien (Role Dokter)](#5-fitur-riwayat-pasien-role-dokter)
6. [Fitur CRUD Jadwal Periksa (Role Dokter)](#6-fitur-crud-jadwal-periksa-role-dokter)
7. [Fitur CRUD Manajemen Obat (Role Admin)](#7-fitur-crud-manajemen-obat-role-admin)
8. [Navigasi Sidebar Dinamis (sidebar.blade.php)](#8-navigasi-sidebar-dinamis-sidebarbladephp)
9. [Panduan Menjalankan Aplikasi](#9-panduan-menjalankan-aplikasi)

---

## 1. Akun Pengujian (Kredensial Default)

Untuk menguji fitur yang dibuat, gunakan kredensial berikut:

| Role | Email | Password | URL Akses Utama |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `admin` | `http://127.0.0.1:8000/admin/obat` |
| **Dokter** | `dokter@gmail.com` | `dokter` | `http://127.0.0.1:8000/dokter/periksa-pasien` |
| **Pasien** | `pasien@gmail.com` | `pasien` | `http://127.0.0.1:8000/pasien/daftar` |

---

## 2. Arsitektur Perutean (`routes/web.php`)

Seluruh perutean dikelompokkan berdasarkan peran (*role-based access control*) dengan middleware keamanan `auth` dan `role`:

```php
// 1. Role Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

// 2. Role Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');
});

// 3. Role Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});
```

---

## 3. Fitur Pendaftaran Poli (Role Pasien)

Modul ini memberikan fasilitas bagi pasien yang telah login untuk mendaftarkan diri ke poli tujuan dan memilih jadwal praktik dokter yang tersedia.

### A. Berkas yang Dibuat & Dimodifikasi:
* **Model:** [`app/Models/DaftarPoli.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/DaftarPoli.php), [`app/Models/Poli.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/Poli.php), [`app/Models/JadwalPeriksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/JadwalPeriksa.php)
* **Controller:** [`app/Http/Controllers/Pasien/PoliController.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Http/Controllers/Pasien/PoliController.php)
* **Route:** `GET|POST /pasien/daftar` di `routes/web.php`
* **Sidebar Menu:** Menu *"Pendaftaran Periksa"* di [`sidebar.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/components/partials/sidebar.blade.php)
* **View:** [`resources/views/pasien/daftar.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/pasien/daftar.blade.php)

### B. Penjelasan Logika & Fitur:
1. **Nomor Rekam Medis Otomatis**: Menampilkan nomor rekam medis (`no_rm`) pasien secara otomatis dari sesi autentikasi pengguna dalam bentuk field *disabled/readonly*.
2. **Dropdown Jadwal Dependen Dinamis**: Pilihan jadwal periksa terhubung langsung dengan poli yang dipilih. Opsi jadwal otomatis difilter di sisi client (JavaScript) sesuai `data-poli`.
3. **Penomoran Antrian Otomatis**: Antrian dihitung secara otomatis berdasarkan jumlah pasien yang telah mendaftar pada jadwal periksa terkait (`count() + 1`).
4. **Validasi Formulir**:
   ```php
   $request->validate([
       'id_poli' => 'required|exists:poli,id',
       'id_jadwal' => 'required|exists:jadwal_periksa,id',
       'keluhan' => 'nullable|string',
       'id_pasien' => 'required|exists:users,id',
   ]);
   ```

### C. Screenshot Halaman Pendaftaran Poli:
Tangkapan layar formulir pendaftaran poli pasien:

![Pendaftaran Poli Pasien](screenshoot/pendaftaran%20poli.png)

---

## 4. Fitur Pemeriksaan Pasien (Role Dokter)

Modul ini digunakan oleh dokter untuk melihat daftar antrian pasien yang terdaftar pada jadwal praktiknya, menginput catatan hasil pemeriksaan/diagnosa, serta meresepkan obat.

### A. Berkas yang Dibuat & Dimodifikasi:
* **Model:** [`app/Models/DaftarPoli.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/DaftarPoli.php), [`app/Models/Periksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/Periksa.php), [`app/Models/DetailPeriksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/DetailPeriksa.php), [`app/Models/Obat.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/Obat.php)
* **Controller:** [`app/Http/Controllers/Dokter/PeriksaPasienController.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Http/Controllers/Dokter/PeriksaPasienController.php)
* **Route:**
  - `GET /dokter/periksa-pasien` (`periksa-pasien.index`)
  - `GET /dokter/periksa-pasien/{id}` (`periksa-pasien.create`)
  - `POST /dokter/periksa-pasien` (`periksa-pasien.store`)
* **Sidebar Menu:** Menu *"Periksa Pasien"* di [`sidebar.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/components/partials/sidebar.blade.php)
* **Views:**
  - [`resources/views/dokter/periksa-pasien/index.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/periksa-pasien/index.blade.php) (Tabel antrian pasien periksa)
  - [`resources/views/dokter/periksa-pasien/create.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/periksa-pasien/create.blade.php) (Formulir pemeriksaan & pemilihan obat)

### B. Penjelasan Logika & Fitur:
1. **Scoping Data Pasien Dokter**: Hanya menampilkan pasien yang terdaftar pada jadwal praktik dokter yang sedang login (`whereHas('jadwalPeriksa', fn($q) => $q->where('id_dokter', Auth::id()))`).
2. **Status & Tombol Aksi Dinamis**:
   - Jika pasien belum diperiksa: Status **Menunggu** dengan tombol biru **Periksa**.
   - Jika pasien sudah diperiksa: Status **Sudah Diperiksa** dengan tombol kuning **Edit**.
3. **Pemilihan Obat & Keranjang Resep**:
   - Dokter memilih obat dari dropdown data obat.
   - Obat yang dipilih otomatis masuk ke daftar resep dengan harga satuan dan tombol hapus per item.
4. **Perhitungan Biaya Otomatis (Realtime)**:
   - Biaya Jasa Dokter standar: **Rp 150.000**.
   - Total Biaya = **Rp 150.000 + Total Harga Obat Terpilih**.
5. **Dukungan Edit Pemeriksaan**:
   - Penyimpanan menggunakan `Periksa::updateOrCreate(...)` dan sinkronisasi baris pada `detail_periksa`.
   - Form otomatis memuat kembali diagnosis dan daftar obat yang sebelumnya telah dipilih saat mode Edit dibuka.

### C. Screenshot Halaman Periksa Pasien:

#### 1. Tabel Daftar Pasien Periksa
Menampilkan nomor antrian, nama pasien, keluhan, status (*Menunggu* / *Sudah Diperiksa*), serta tombol aksi *Periksa* dan *Edit*:

![Daftar Pasien Periksa](screenshoot/periksa%20pasien.png)

#### 2. Formulir Pemeriksaan Pasien Baru
Menampilkan informasi pasien, pilihan obat interaktif, daftar obat terpilih, estimasi biaya, dan catatan dokter:

![Formulir Periksa Pasien](screenshoot/periksa%20pasien%20periksa.png)

#### 3. Formulir Edit Pemeriksaan Pasien
Memuat ulang catatan diagnosis dokter serta obat yang sebelumnya telah diresepkan:

![Edit Periksa Pasien](screenshoot/periksa%20pasien%20edit.png)

---

## 5. Fitur Riwayat Pasien (Role Dokter)

Modul ini menampilkan seluruh rekam jejak pemeriksaan yang telah diselesaikan oleh dokter yang bersangkutan, beserta rincian lengkap tiap tindakan.

### A. Berkas yang Dibuat & Dimodifikasi:
* **Model:** [`app/Models/Periksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/Periksa.php), [`app/Models/DetailPeriksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/DetailPeriksa.php)
* **Controller:** [`app/Http/Controllers/Dokter/RiwayatPasienController.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Http/Controllers/Dokter/RiwayatPasienController.php)
* **Route:**
  - `GET /dokter/riwayat-pasien` (`riwayat-pasien.index`)
  - `GET /dokter/riwayat-pasien/{id}` (`riwayat-pasien.show`)
* **Sidebar Menu:** Menu *"Riwayat Pasien"* di [`sidebar.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/components/partials/sidebar.blade.php)
* **Views:**
  - [`resources/views/dokter/riwayat-pasien/index.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/riwayat-pasien/index.blade.php) (Tabel riwayat periksa)
  - [`resources/views/dokter/riwayat-pasien/show.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/riwayat-pasien/show.blade.php) (Rincian detail riwayat periksa)

### B. Penjelasan Logika & Fitur:
1. **Urutan Kronologis**: Menampilkan pemeriksaan terurut dari waktu pemeriksaan terbaru (`orderBy('tgl_periksa', 'desc')`).
2. **Tabel Ringkasan**: Menampilkan nomor antrian, nama pasien, keluhan, tanggal periksa, total biaya, dan tombol aksi **Detail**.
3. **Rincian Rekam Medis (Show)**:
   - Data diri pasien, poli, dan dokter pemeriksa.
   - Catatan diagnosis dari dokter.
   - Rincian tabel obat yang diberikan lengkap dengan kemasan dan harga.
   - Total akumulasi biaya pemeriksaan.

### C. Screenshot Halaman Riwayat Pasien:

#### 1. Tabel Daftar Riwayat Pasien
Menampilkan rekapitulasi data pemeriksaan yang telah selesai:

![Tabel Riwayat Pasien](screenshoot/riwayat%20pasien.png)

#### 2. Rincian Detail Riwayat Pasien
Menampilkan informasi pasien, catatan diagnosa dokter, tabel obat yang diresepkan, dan total biaya:

![Detail Riwayat Pasien](screenshoot/riwayat%20pasien%20detail.png)

---

## 6. Fitur CRUD Jadwal Periksa (Role Dokter)

Modul ini memungkinkan dokter mengelola jadwal praktiknya secara mandiri.

### A. Berkas yang Digunakan:
* **Model:** [`app/Models/JadwalPeriksa.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/JadwalPeriksa.php)
* **Controller:** [`app/Http/Controllers/Dokter/JadwalPeriksaController.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Http/Controllers/Dokter/JadwalPeriksaController.php)
* **Route:** `Route::resource('jadwal-periksa', JadwalPeriksaController::class);` di `routes/web.php`
* **Views:**
  - [`resources/views/dokter/jadwal-periksa/index.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/jadwal-periksa/index.blade.php)
  - [`resources/views/dokter/jadwal-periksa/create.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/jadwal-periksa/create.blade.php)
  - [`resources/views/dokter/jadwal-periksa/edit.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/dokter/jadwal-periksa/edit.blade.php)

### B. Penjelasan Logika & Keamanan:
* **Scoping Dokter Login (`Auth::id()`)**: Dokter hanya dapat mengelola jadwal praktiknya sendiri.
* **Validasi Waktu Praktik**: Memastikan jam selesai selalu lebih akhir dari jam mulai (`after:jam_mulai`).

---

## 7. Fitur CRUD Manajemen Obat (Role Admin)

Modul ini memberikan akses penuh bagi Admin untuk mengelola data obat poliklinik.

### A. Berkas yang Digunakan:
* **Model:** [`app/Models/Obat.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Models/Obat.php)
* **Controller:** [`app/Http/Controllers/Admin/ObatController.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/app/Http/Controllers/Admin/ObatController.php)
* **Route:** `Route::resource('obat', ObatController::class);` di `routes/web.php`
* **Views:**
  - [`resources/views/admin/obat/index.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/admin/obat/index.blade.php)
  - [`resources/views/admin/obat/create.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/admin/obat/create.blade.php)
  - [`resources/views/admin/obat/edit.blade.php`](file:///c:/xampp3/htdocs/Bengkod%201/poliklinik-app-5/resources/views/admin/obat/edit.blade.php)

---

## 8. Navigasi Sidebar Dinamis (`sidebar.blade.php`)

Sidebar secara dinamis mendeteksi rute aktif menggunakan helper `request()->routeIs(...)` berbasis wildcard, sehingga status aktif tetap terjaga saat berada pada halaman index, formulir create, maupun formulir edit:

```blade
{{-- Menu Pasien: Pendaftaran Periksa --}}
<a href="{{ route('pasien.daftar') }}"
    class="{{ $baseLink }} {{ request()->routeIs('pasien.daftar*') ? $active : $inactive }}">
    <i class="fas fa-id-card w-4 text-center"></i>
    Pendaftaran Periksa
</a>

{{-- Menu Dokter: Periksa Pasien --}}
<a href="{{ route('periksa-pasien.index') }}"
    class="{{ $baseLink }} {{ request()->routeIs('periksa-pasien.*') ? $active : $inactive }}">
    <i class="fas fa-notes-medical w-4 text-center"></i>
    Periksa Pasien
</a>

{{-- Menu Dokter: Riwayat Pasien --}}
<a href="{{ route('riwayat-pasien.index') }}"
    class="{{ $baseLink }} {{ request()->routeIs('riwayat-pasien.*') ? $active : $inactive }}">
    <i class="fas fa-clock-rotate-left w-4 text-center"></i>
    Riwayat Pasien
</a>
```

---

## 9. Panduan Menjalankan Aplikasi

1. **Pastikan Web Server & Database Aktif**:
   Nyalakan service Apache dan MySQL pada XAMPP Control Panel.

2. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

3. **Kompilasi Aset Frontend**:
   ```bash
   npm run build
   ```

4. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser pada: **`http://127.0.0.1:8000/login`**
