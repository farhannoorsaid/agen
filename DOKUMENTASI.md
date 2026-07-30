# 📦 Sistem Manajemen Stok Agen Hendi - Dokumentasi Lengkap

## 🎯 Ringkasan Project

Ini adalah **Sistem Manajemen Stok Fullstack** yang dibangun dengan:
- **Backend**: Laravel 11
- **Frontend**: Blade Template + Tailwind CSS
- **Database**: MySQL
- **Fitur Utama**: Manajemen barang, stok masuk/keluar, laporan penjualan dengan snapshot data

Semua logika yang kamu minta sudah **100% implementasikan sesuai spesifikasi**.

---

## ✅ Checklist Implementasi

### 1. **Login System**
- ✅ Hanya ada Staff (tidak ada Admin/Owner)
- ✅ Register + Login menggunakan Laravel Breeze
- ✅ Password hashed dengan bcrypt
- **File**: `app/Models/User.php`, `routes/auth.php`

### 2. **Dashboard**
- ✅ Banner utama dengan gradient
- ✅ Notifikasi stok rendah (realtime)
- ✅ Notifikasi barang hampir kadaluarsa (< 30 hari)
- ✅ Statistik: Total barang, stok, penjualan
- ✅ Quick action buttons
- **File**: `app/Http/Controllers/DashboardController.php`, `resources/views/dashboard.blade.php`

### 3. **Manajemen Data Barang**
- ✅ CRUD (Create, Read, Update, Delete)
- ✅ **Tanpa halaman detail** - hanya list dengan buttons
- ✅ Button Edit (ubah data)
- ✅ Button Arsip (soft delete, `is_arsip = true`)
- ✅ Dropdown supplier saat input
- ✅ Stok minimum, tanggal kadaluarsa, harga jual
- **File**: `app/Http/Controllers/BarangController.php`, `resources/views/barang/`

### 4. **Manajemen Supplier**
- ✅ CRUD lengkap (Create, Read, Update, Delete)
- ✅ Nama, alamat, no HP
- ✅ Relasi dengan barang
- **File**: `app/Http/Controllers/SupplierController.php`, `resources/views/pemasok/`

### 5. **Stok Masuk (Pembelian)**
- ✅ Dropdown barang dari tabel products (hanya barang aktif)
- ✅ Otomatis **increment stok barang** saat submit
- ✅ Simpan riwayat di tabel `stock_ins`
- ✅ Catat user yang input
- ✅ Opsional: keterangan (PO, batch, dll)
- **File**: `app/Http/Controllers/StockInController.php`, `resources/views/stok-masuk/`

### 6. **Stok Keluar (Penjualan)**
- ✅ Dropdown barang dengan stok real-time
- ✅ Harga jual **otomatis** × jumlah
- ✅ Validasi stok cukup
- ✅ Otomatis **decrement stok barang**
- ✅ Status pembayaran: Lunas / Belum Lunas
- ✅ **SNAPSHOT DATA DISIMPAN** (lihat poin 8)
- **File**: `app/Http/Controllers/StockOutController.php`, `resources/views/stok-keluar/`

### 7. **Laporan Stok**
- ✅ Data real-time dari tabel `barangs` + transaksi
- ✅ Total masuk, total keluar, stok terkini
- ✅ Status warning (stok rendah / normal)
- ✅ Detail breakdown per barang
- **File**: `app/Http/Controllers/ReportController.php`, `resources/views/laporan/stok.blade.php`

### 8. **Laporan Penjualan (YANG PALING PENTING!)**
- ✅ **SNAPSHOT DATA DISIMPAN** saat transaksi:
  - `product_name_snapshot` → Nama barang saat transaksi
  - `supplier_name_snapshot` → Nama supplier saat transaksi
  - `price_snapshot` → Harga jual × qty saat transaksi
- ✅ **Data tidak berubah** meski:
  - Nama barang diubah
  - Harga jual diubah
  - Supplier diubah
- ✅ Filter berdasarkan:
  - Status pembayaran (Lunas / Belum Lunas)
  - Range tanggal (dari - sampai)
- ✅ Tampilkan summary: Total, Lunas, Belum Lunas
- **File**: `database/migrations/2025_11_25_000002_create_stock_outs_table.php`, `app/Http/Controllers/ReportController.php`, `resources/views/laporan/penjualan.blade.php`

---

## 🗂️ Struktur Database

### Tabel: `users`
```
id, name, email, password, created_at, updated_at
```

### Tabel: `suppliers`
```
id, nama_supplier, alamat, no_hp, created_at, updated_at
```

### Tabel: `barangs`
```
id, supplier_id, nama_barang, stok, stok_minimum, 
tanggal_kedaluwarsa, harga_jual, is_arsip, created_at, updated_at
```

### Tabel: `stock_ins`
```
id, barang_id, jumlah_masuk, keterangan, user_id, created_at, updated_at
```

### Tabel: `stock_outs` (PALING PENTING - SNAPSHOT DATA)
```
id, barang_id, jumlah_terjual,
product_name_snapshot,      ← Nama barang saat transaksi
supplier_name_snapshot,     ← Nama supplier saat transaksi
price_snapshot,             ← Harga total × qty saat transaksi
total_harga,
status_pembayaran,          ← 'lunas' atau 'belum_lunas'
user_id, created_at, updated_at
```

---

## 📁 File Structure Kunci

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── BarangController.php
│   ├── SupplierController.php
│   ├── StockInController.php
│   ├── StockOutController.php
│   ├── ReportController.php
│   └── ApiController.php (untuk AJAX)
│
├── Models/
│   ├── User.php
│   ├── Barang.php
│   ├── Supplier.php
│   ├── StockIn.php
│   └── StockOut.php
│
database/
├── migrations/
│   ├── 2025_11_25_000001_create_stock_ins_table.php
│   └── 2025_11_25_000002_create_stock_outs_table.php
│
resources/views/
├── dashboard.blade.php
├── barang/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── pemasok/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── stok-masuk/
│   ├── index.blade.php
│   └── create.blade.php
├── stok-keluar/
│   ├── index.blade.php
│   └── create.blade.php
└── laporan/
    ├── stok.blade.php
    ├── penjualan.blade.php
    └── detail-stok.blade.php

routes/
├── web.php
└── auth.php
```

---

## 🚀 Cara Menjalankan Project

### 1. **Setup Database**
```bash
cd c:\xampp\htdocs\laravel-project\agen-hendi

# Jalankan migrations
php artisan migrate

# (Optional) Seed dummy data
php artisan tinker
```

### 2. **Start Development Server**
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: (Optional) Vite untuk CSS/JS
npm run dev
```

### 3. **Akses Aplikasi**
- URL: `http://127.0.0.1:8000`
- Buat akun baru via Register
- Login sebagai Staff
- Mulai gunakan sistem

---

## 💡 Logika Penting yang Sudah Diimplementasi

### **Scope Methods untuk Barang**
```php
Barang::active()           // WHERE is_arsip = false
Barang::archived()         // WHERE is_arsip = true
Barang::lowStock()         // WHERE stok <= stok_minimum
Barang::expiringSoon()     // Tanggal exp < 30 hari
```

### **Snapshot Data di StockOut**
Contoh skenario:
- **Saat transaksi (23 Nov 2025)**:
  - Nama barang: "Indomie Goreng"
  - Harga jual: Rp 3.000
  - Supplier: "PT Distributor ABC"
  - Disimpan di `product_name_snapshot`, `price_snapshot`, `supplier_name_snapshot`

- **Kemudian (24 Nov 2025)**:
  - Admin ubah nama jadi "Indomie Jumbo"
  - Admin ubah harga jadi Rp 3.500
  - Admin ganti supplier

- **Saat buka Laporan Penjualan**:
  - Masih tampil "Indomie Goreng - Rp 3.000 - PT Distributor ABC"
  - Data original TIDAK BERUBAH! ✅

---

## 🔧 Fitur Lanjutan yang Bisa Ditambah

Ini adalah saran untuk feature ke depan:

1. **Export PDF/Excel Laporan**
   - Library: `barryvdh/laravel-dompdf` & `maatwebsite/excel`
   - Bisa membuat invoice, laporan penjualan format PDF

2. **Dashboard Widget**
   - Chart stok barang
   - Grafik penjualan trending
   - Forecast stok

3. **Notifikasi Email**
   - Alert stok rendah via email
   - Reminder payment belum lunas

4. **Multi-Warehouse**
   - Tracking stok per lokasi
   - Transfer antar warehouse

---

## 🎨 Styling & UX

- **Framework**: Tailwind CSS v2.2
- **Warna Scheme**: 
  - Primary: Blue (Dashboard, form)
  - Success: Green (Stok masuk, Lunas)
  - Warning: Yellow (Belum lunas, Stok rendah)
  - Danger: Red (Arsip, Delete)
- **Responsive**: Mobile-friendly dengan grid system

---

## 🔒 Keamanan

- ✅ CSRF Protection (via @csrf)
- ✅ Password hashing (Bcrypt)
- ✅ Query validation (type hints + rules)
- ✅ Route protection (middleware 'auth')
- ✅ Soft delete (arsip barang, bukan permanent delete)

---

## 📝 Catatan Penting

1. **Database MySQL** harus sudah running di XAMPP
2. **DB_DATABASE** di `.env` sudah set ke `stok_agen_hendi`
3. **Migrations** harus dijalankan PERTAMA kali setup
4. **Seeding** (dummy data) bisa ditambahkan di `database/seeders/`
5. **API endpoint** `/api/barang/{id}` untuk AJAX fetch stok realtime

---

## 📞 Support

Jika ada error atau perlu modifikasi, cek:
1. **Laravel logs**: `storage/logs/laravel.log`
2. **Database connection**: Test di `.env`
3. **Migration status**: `php artisan migrate:status`
4. **Artisan commands**: `php artisan tinker` untuk debugging

---

**Dibuat dengan ❤️ untuk Sistem Manajemen Stok Agen Hendi**
