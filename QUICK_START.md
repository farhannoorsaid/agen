# 🚀 QUICK START - Sistem Manajemen Stok Agen Hendi

## ⚡ Setup dalam 5 Menit

### 1️⃣ Persiapan
```bash
# Pastikan sudah di folder project
cd c:\xampp\htdocs\laravel-project\agen-hendi

# Pastikan MySQL XAMPP sudah running
# - Buka XAMPP Control Panel
# - Klik START pada MySQL
```

### 2️⃣ Install Dependencies
```bash
# Composer packages
composer install

# NPM packages (optional, untuk Tailwind)
npm install
npm run dev
```

### 3️⃣ Setup Database
```bash
# Jalankan migrations (CREATE TABLES)
php artisan migrate

# Jika ada error, reset database dulu
php artisan migrate:reset
php artisan migrate
```

### 4️⃣ Start Server
```bash
# Terminal 1
php artisan serve

# Terminal 2 (optional - untuk CSS/JS watch)
npm run dev
```

### 5️⃣ Akses Aplikasi
```
URL: http://127.0.0.1:8000
Register → Login → Mulai Gunakan!
```

---

## 🎯 Default Logins (Jika Ada Seeding)

Belum ada seeding, jadi:
1. Klik **"Register"** untuk buat akun baru
2. Gunakan email: `staff@agen-hendi.com`
3. Password: sesuatu yang kuat
4. Login dan mulai!

---

## 📊 Testing Data Flow

### A. Input Barang
1. Dashboard → sidebar Data Barang → ✚ Tambah Barang
2. Isi: Nama, Supplier, Stok, Harga, Tanggal Exp (optional)
3. Klik Simpan

### B. Input Supplier Dulu
1. Dashboard → sidebar Data Supplier → ✚ Tambah Supplier
2. Isi: Nama, Alamat, No HP
3. Klik Simpan
4. **Lalu input Barang baru**

### C. Stok Masuk (Pembelian)
1. Dashboard → sidebar Stok Masuk → ✚ Input Stok Masuk
2. Pilih barang dari dropdown
3. Masukkan jumlah masuk
4. Klik Simpan
5. **Stok barang otomatis bertambah!**

### D. Stok Keluar (Penjualan)
1. Dashboard → sidebar Stok Keluar → ✚ Input Penjualan
2. Pilih barang → harga otomatis muncul
3. Masukkan jumlah terjual
4. Pilih status pembayaran (Lunas/Belum Lunas)
5. Klik Simpan
6. **Stok barang otomatis berkurang!**
7. **Snapshot data disimpan!** ✅

### E. Lihat Laporan
1. Dashboard → sidebar Laporan Stok
   - Lihat: Stok terkini, total masuk/keluar per barang
   - Click "Detail" untuk breakdown

2. Dashboard → sidebar Laporan Penjualan
   - Filter berdasarkan status pembayaran
   - Filter berdasarkan tanggal
   - **Data snapshot tidak berubah!** ✅

---

## ✅ Checklist Setelah Setup

- [ ] MySQL berjalan
- [ ] Migrations selesai (8 tables)
- [ ] Server Laravel running port 8000
- [ ] Bisa register akun baru
- [ ] Bisa login
- [ ] Bisa input supplier
- [ ] Bisa input barang
- [ ] Bisa input stok masuk
- [ ] Bisa input penjualan
- [ ] Stok otomatis bertambah/berkurang
- [ ] Laporan menampilkan data snapshot

---

## 🆘 Troubleshooting

### Error: SQLSTATE[HY000]: General error: 1030
**Solusi**: Database terlalu kecil, atau MySQL tidak running
```bash
# Restart MySQL di XAMPP
# Atau jalankan migrate fresh
php artisan migrate:fresh
```

### Error: Class not found
**Solusi**: Clear autoload
```bash
composer dump-autoload
```

### Error: Port 8000 sudah dipakai
**Solusi**: Gunakan port lain
```bash
php artisan serve --port=8001
```

### Error: npm command not found
**Solusi**: Skip npm, styling sudah di CDN Tailwind

---

## 🎨 Custom Branding

Edit ini sesuai kebutuhan:

### App Name
File: `.env`
```
APP_NAME="Agen Hendi"  ← Ubah di sini
```

### Logo/Brand
File: `resources/views/layouts/navigation.blade.php`
Cari: `Agen Hendi` dan `Application Logo`

### Warna Theme
File: `tailwind.config.js`
Atau edit class Tailwind di `.blade.php` files

---

## 📚 Struktur Menu Utama

```
Dashboard
├── 📦 Data Barang
│   ├── List barang (dengan stok)
│   ├── Tambah barang
│   ├── Edit barang
│   └── Arsip barang
├── 🏪 Data Supplier
│   ├── List supplier
│   ├── Tambah supplier
│   └── Edit supplier
├── 📥 Stok Masuk
│   ├── List riwayat
│   └── Input stok masuk
├── 📤 Stok Keluar
│   ├── List riwayat penjualan
│   ├── Input penjualan
│   └── Update status pembayaran
└── 📊 Laporan
    ├── Laporan Stok (real-time)
    ├── Laporan Penjualan (dengan snapshot)
    └── Detail breakdown per barang
```

---

## 🔐 User Management

Untuk admin features di masa depan (belum ada):
- User baru → Register sendiri (Staff)
- Tidak ada role/permission (semua punya akses sama)
- Setiap transaksi tercatat user siapa yang input

---

## 📞 Need Help?

Check these files:
- **Setup issues**: `DOKUMENTASI.md`
- **API endpoints**: `routes/web.php`
- **Database schema**: `database/migrations/`
- **Logic**: Controller files di `app/Http/Controllers/`

---

**Happy Inventory Management! 🎉**
