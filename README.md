# Project Agen Hendi

Aplikasi manajemen stok dan data pemasok berbasis Laravel.

## Langkah-langkah Instalasi (Device Baru)

Ikuti panduan berikut jika Anda baru pertama kali menjalankan project ini di komputer/device baru:

1. **Clone / Download Project**
   Pastikan project sudah ada di komputer Anda dan buka terminal di dalam folder project tersebut.

2. **Install Dependensi PHP (Composer)**
   Jalankan perintah ini untuk menginstal semua paket Laravel:
   ```bash
   composer install
   ```

3. **Pengaturan Database (.env)**
   - Copy file konfigurasi environment:
     ```bash
     copy .env.example .env
     ```
     *(Gunakan `cp .env.example .env` jika di Mac/Linux)*
   - Buka file `.env` dan atur koneksi database Anda (biasanya untuk XAMPP):
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nama_database_anda
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - *Pastikan Anda sudah membuat database kosong di phpMyAdmin dengan nama yang sama dengan `DB_DATABASE`.*

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**
   Buat tabel-tabel yang dibutuhkan dan isi dengan data default (seperti akun user awal):
   ```bash
   php artisan migrate --seed
   ```

6. **Install Dependensi Frontend (Node.js)**
   Jalankan perintah ini untuk menginstal library CSS/JS (Tailwind & Vite):
   ```bash
   npm install
   ```

7. **Compile Asset Frontend**
   Untuk men-generate file CSS agar tampilan tidak berantakan:
   ```bash
   npm run dev
   ```
   *(Biarkan terminal ini tetap berjalan)*

8. **Jalankan Server Lokal**
   Buka terminal baru di folder yang sama, lalu jalankan:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser melalui URL: `http://localhost:8000`

---

## Troubleshooting (Solusi Jika Terjadi Error)

Jika Anda mengalami kendala saat menjalankan project, coba beberapa solusi berikut:

### 1. Perubahan Tidak Terbaca / Error Konfigurasi (Clear Cache)
Jika Anda sudah mengubah kode (terutama `.env` atau *config*) tapi tidak ada efeknya, jalankan perintah pembersih cache ini:
```bash
php artisan optimize:clear
```
Atau jika ingin spesifik:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 2. Gambar / File Upload Tidak Muncul
Jika gambar yang di-upload tidak bisa dibaca atau tidak muncul di halaman web, Anda perlu menghubungkan folder storage internal ke folder public agar bisa diakses browser. Jalankan perintah:
```bash
php artisan storage:link
```

### 3. Tampilan CSS Berantakan
Pastikan Anda menjalankan perintah `npm run dev` pada terminal terpisah dan membiarkannya tetap menyala saat Anda sedang men-develop atau membuka aplikasinya.

---

## Panduan Upload / Berbagi Project (.rar / .zip)

Jika Anda ingin mengirim atau meng-upload project ini (ke Google Drive, e-learning, dsb) dalam bentuk `.rar` atau `.zip`, pastikan Anda **menghapus folder-folder berukuran raksasa** berikut sebelum di-compress. Tujuannya agar ukuran file `.rar` Anda menjadi sangat kecil (biasanya hanya beberapa Megabyte saja).

### ❌ Folder yang WAJIB DIHAPUS (Jangan dimasukkan ke .rar):
- Folder `vendor/` *(Otomatis dibuat ulang saat menjalankan `composer install`)*
- Folder `node_modules/` *(Otomatis dibuat ulang saat menjalankan `npm install`)*

### ✅ File & Folder yang WAJIB ADA di dalam .rar:
- Semua folder inti Laravel (`app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `tests/`)
- `.env.example` *(dan `.env` jika Anda ingin menyertakan konfigurasi lokal Anda)*
- `composer.json` & `composer.lock`
- `package.json` & `package-lock.json`
- `vite.config.js` & `tailwind.config.js`
- `artisan`
- `README.md`
- (Opsional) File ekspor database seperti `.sql` jika Anda ingin menyertakan backup database asli.
