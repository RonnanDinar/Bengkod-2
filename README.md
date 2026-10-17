# Dokumentasi Lengkap Aplikasi Poliklinik

Aplikasi Sistem Informasi Poliklinik berbasis **Laravel 12**, dilengkapi dengan sistem **Multi-User Authentication** (*Admin, Dokter, Pasien*), manajemen basis data relasional Eloquent, validasi komprehensif, notifikasi flash message, dan antarmuka modern yang seragam menggunakan **Tailwind CSS & DaisyUI**.

---

## Daftar Isi
1. [Akun Pengujian (Kredensial Default)](#1-akun-pengujian-kredensial-default)
2. [Arsitektur Routing & Hak Akses](#2-arsitektur-routing--hak-akses)
3. [Fitur CRUD Role Admin](#3-fitur-crud-role-admin)
   - [A. Manajemen Poli](#a-manajemen-poli)
   - [B. Manajemen Dokter](#b-manajemen-dokter)
   - [C. Manajemen Pasien](#c-manajemen-pasien)
   - [D. Manajemen Obat](#d-manajemen-obat)
4. [Fitur CRUD Role Dokter](#4-fitur-crud-role-dokter)
   - [Manajemen Jadwal Periksa](#manajemen-jadwal-periksa)
5. [Sistem UI, Navigasi Sidebar, dan Notifikasi](#5-sistem-ui-navigasi-sidebar-dan-notifikasi)
6. [Panduan Menjalankan Proyek](#6-panduan-menjalankan-proyek)

---

## 1. Akun Pengujian (Kredensial Default)

Aplikasi telah dilengkapi seeder data pengguna untuk masing-masing hak akses:

| Role | Email | Password | URL Akses |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `admin` | `http://127.0.0.1:8000/admin/dashboard` |
| **Dokter** | `dokter@gmail.com` | `dokter` | `http://127.0.0.1:8000/dokter/dashboard` |
| **Pasien** | `pasien@gmail.com` | `pasien` | `http://127.0.0.1:8000/pasien/dashboard` |

---

## 2. Arsitektur Routing & Hak Akses

Routing diamankan menggunakan middleware `auth` dan `role:<role_name>` untuk memastikan pengguna hanya dapat mengakses fitur sesuai hak aksesnya:

```php
// routes/web.php

// 1. Role Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
});

// 2. Role Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');
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

## 3. Fitur CRUD Role Admin

Seluruh modul CRUD admin didesain dengan standar antarmuka yang seragam (Header dengan tombol `+ Tambah`, Card putih bersih rounded-2xl border halus, input responsif 2 kolom, tombol Aksi Edit warna amber & Hapus warna merah dengan dialog konfirmasi).

### A. Manajemen Poli
* **URL:** `http://127.0.0.1:8000/admin/polis`
* **Controller:** `App\Http\Controllers\Admin\AdminPoliController`
* **File View:**
  - `resources/views/admin/polis/index.blade.php` (Daftar Poli)
  - `resources/views/admin/polis/create.blade.php` (Form Tambah Poli)
  - `resources/views/admin/polis/edit.blade.php` (Form Edit Poli)
* **Aturan Validasi Form:**
  ```php
  $request->validate([
      'nama_poli' => 'required|string|max:255',
      'keterangan' => 'nullable|string',
  ]);
  ```
* **Fitur & Alur Kerja:**
  - **Tampil:** Menampilkan seluruh daftar poli klinik dan keterangannya.
  - **Tambah:** Input nama poli dan keterangan poli.
  - **Edit:** Memperbarui informasi nama poli dan keterangannya.
  - **Hapus:** Menghapus poli dari sistem dengan konfirmasi popup.
  - **Notifikasi:** Flash alert sukses muncul setelah operasi selesai.

---

### B. Manajemen Dokter
* **URL:** `http://127.0.0.1:8000/admin/dokter`
* **Controller:** `App\Http\Controllers\Admin\DokterController`
* **File View:**
  - `resources/views/admin/dokter/index.blade.php` (Daftar Dokter)
  - `resources/views/admin/dokter/create.blade.php` (Form Tambah Dokter)
  - `resources/views/admin/dokter/edit.blade.php` (Form Edit Dokter)
* **Aturan Validasi Form:**
  - **Tambah (Store):**
    ```php
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
        'no_hp' => 'required|string|max:15',
        'id_poli' => 'required|string|exists:poli,id',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|min:6',
    ]);
    ```
  - **Ubah (Update):**
    ```php
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $dokter->id,
        'no_hp' => 'required|string|max:15',
        'id_poli' => 'required|string|exists:poli,id',
        'email' => 'required|string|unique:users,email,' . $dokter->id,
        'password' => 'nullable|string|min:6', // Fleksibel jika tidak ingin mengganti password
    ]);
    ```
* **Fitur & Alur Kerja:**
  - **Tampil:** Menampilkan tabel berkolom `Nama Dokter`, `Email`, `No. KTP`, `No. HP`, `Alamat`, `Poli` *(pill badge biru muda)*, dan `Aksi`.
  - **Tambah:** Form grid 2 kolom dengan dropdown data Poli dinamis, otomatis menyimpan ke model `User` dengan `role = 'dokter'` dan password ter-hash (`Hash::make`).
  - **Edit:** Memperbarui profil dokter dengan opsi update password opsional.
  - **Hapus:** Menghapus akun dokter dari basis data.

---

### C. Manajemen Pasien
* **URL:** `http://127.0.0.1:8000/admin/pasien`
* **Controller:** `App\Http\Controllers\Admin\PasienController`
* **File View:**
  - `resources/views/admin/pasien/index.blade.php` (Daftar Pasien)
  - `resources/views/admin/pasien/create.blade.php` (Form Tambah Pasien)
  - `resources/views/admin/pasien/edit.blade.php` (Form Edit Pasien)
* **Aturan Validasi Form:**
  - **Tambah (Store):**
    ```php
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
        'no_hp' => 'required|string|max:15',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);
    ```
  - **Ubah (Update):**
    ```php
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $pasien->id,
        'no_hp' => 'required|string|max:15',
        'email' => 'required|string|email|unique:users,email,' . $pasien->id,
        'password' => 'nullable|string|min:6',
    ]);
    ```
* **Fitur & Alur Kerja Khusus:**
  - **Auto-Generate Nomor Rekam Medis (No. RM):**
    Pada saat admin menambahkan pasien baru, sistem otomatis membuat Nomor Rekam Medis unik dengan format `YYYYMM-XXX` (contoh: `202610-001`):
    ```php
    $lastPasien = User::where('role', 'pasien')->orderBy('id', 'desc')->first();
    $lastId = $lastPasien ? $lastPasien->id + 1 : 1;
    $no_rm = date('Ym') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
    ```
  - **Tampil:** Kolom `No. RM` disajikan dengan badge pill berwarna emerald.
  - **Edit:** Menampilkan info No. RM eksisting secara readonly agar integritas rekam medis tetap terjaga.

---

### D. Manajemen Obat
* **URL:** `http://127.0.0.1:8000/admin/obat`
* **Controller:** `App\Http\Controllers\Admin\ObatController`
* **File View:**
  - `resources/views/admin/obat/index.blade.php` (Daftar Obat)
  - `resources/views/admin/obat/create.blade.php` (Form Tambah Obat)
  - `resources/views/admin/obat/edit.blade.php` (Form Edit Obat)
* **Aturan Validasi Form:**
  ```php
  $request->validate([
      'nama_obat' => 'required|string|max:255',
      'kemasan' => 'required|string|max:35',
      'harga' => 'required|numeric|min:0',
  ]);
  ```
* **Fitur & Alur Kerja:**
  - **Tampil:** Tabel data obat dengan kolom `Nama Obat`, `Kemasan` *(badge rounded)*, `Harga` *(format nominal rupiah: `Rp 15.000`)*, dan `Aksi`.
  - **Tambah:** Input nama obat, kemasan (contoh: *Strip @ 10 Tablet*, *Botol 60ml*), dan harga dalam angka.
  - **Edit:** Form edit informasi obat.
  - **Hapus:** Menghapus data obat dengan konfirmasi.

---

## 4. Fitur CRUD Role Dokter

### Manajemen Jadwal Periksa
* **URL:** `http://127.0.0.1:8000/dokter/jadwal-periksa`
* **Controller:** `App\Http\Controllers\Dokter\JadwalPeriksaController`
* **File View:**
  - `resources/views/dokter/jadwal-periksa/index.blade.php` (Daftar Jadwal Periksa)
  - `resources/views/dokter/jadwal-periksa/create.blade.php` (Form Tambah Jadwal)
  - `resources/views/dokter/jadwal-periksa/edit.blade.php` (Form Edit Jadwal)
* **Keamanan Data (Scoping Dokter Login):**
  Setiap dokter yang login hanya dapat melihat, menambah, mengubah, dan menghapus jadwal miliknya sendiri melalui scoping `where('id_dokter', Auth::id())`:
  ```php
  $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())->get();
  ```
* **Aturan Validasi Form:**
  ```php
  $request->validate([
      'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
      'jam_mulai' => 'required',
      'jam_selesai' => 'required|after:jam_mulai',
  ], [
      'jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
  ]);
  ```
* **Fitur & Alur Kerja:**
  - **Tampil:** Menampilkan hari praktik, jam mulai, jam selesai (format `H:i WIB`), status aktif (badge hijau), serta tombol edit & hapus.
  - **Tambah:** Dropdown pilihan hari (`Senin` s/d `Minggu`) dan input waktu jam mulai & selesai. Dilengkapi validasi bahwa jam selesai harus sesudah jam mulai.
  - **Edit:** Form edit jadwal periksa dengan data awal yang siap diubah.
  - **Hapus:** Menghapus jadwal periksa dokter.

---

## 5. Sistem UI, Navigasi Sidebar, dan Notifikasi

### A. Navigasi Sidebar Dinamis (`sidebar.blade.php`)
Menu sidebar otomatis mendeteksi rute halaman yang sedang aktif menggunakan helper `request()->routeIs(...)` sehingga tautan aktif mendapatkan aksen gradient dan border yang jelas:
* **Menu Admin:**
  - Dashboard Admin: `request()->routeIs('admin.dashboard')`
  - Manajemen Poli: `request()->routeIs('polis.*')`
  - Manajemen Dokter: `request()->routeIs('dokter.*')`
  - Manajemen Pasien: `request()->routeIs('pasien.*')`
  - Manajemen Obat: `request()->routeIs('obat.*')`
* **Menu Dokter:**
  - Dashboard Dokter: `request()->routeIs('dokter.dashboard')`
  - Jadwal Periksa: `request()->routeIs('jadwal-periksa.*')`

### B. Komponen Notifikasi Global (`app.blade.php`)
Layout utama menangani flash alert secara universal baik dari session `success`, `error`, maupun `message` (`type = success / info`):
```blade
@if(session('message'))
<div class="alert alert-{{ session('type') === 'success' ? 'success' : 'info' }} mb-4 rounded-xl shadow-sm">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('message') }}</span>
</div>
@endif
```

---

## 6. Panduan Menjalankan Proyek

1. **Pastikan Web Server & Database Aktif**:
   Nyalakan Apache & MySQL pada XAMPP.

2. **Konfigurasi Environment (.env)**:
   Pastikan pengaturan database pada berkas `.env` sudah sesuai:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=poliklinik_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Install Dependensi & Migrate**:
   ```bash
   composer install
   npm install
   php artisan migrate --seed
   ```

4. **Kompilasi Aset Frontend (Tailwind & DaisyUI)**:
   ```bash
   npm run build
   ```
   *(Atau jalankan `npm run dev` untuk hot reload saat development)*

5. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka di peramban: **`http://127.0.0.1:8000`** atau langsung ke halaman login di **`http://127.0.0.1:8000/login`**.
