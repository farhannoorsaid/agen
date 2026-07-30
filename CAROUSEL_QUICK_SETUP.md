# Quick Reference: Cara Menambah Gambar Carousel

## Setup Awal (Sekali)

### 1. Buat Folder
Folder sudah dibuat di: `public/images/carousel/`

### 2. Update Controller (Sudah Selesai)
File: `app/Http/Controllers/DashboardController.php`
- Sudah berisi array gambar default (banner1.jpg, banner2.jpg, banner3.jpg)

### 3. Update View (Sudah Selesai)
File: `resources/views/dashboard.blade.php`
- Sudah connect ke controller via `json_encode($carouselImages)`

---

## Cara Menambah Gambar (Setiap Kali)

### 1️⃣ Upload Gambar
Letakkan file gambar di: `public/images/carousel/`

**Contoh:**
```
public/images/carousel/
  ├── banner1.jpg
  ├── banner2.jpg
  ├── banner3.jpg
  └── promo_new.jpg  ← gambar baru
```

### 2️⃣ Update Controller
Edit: `app/Http/Controllers/DashboardController.php`

```php
$carouselImages = [
    asset('images/carousel/banner1.jpg'),
    asset('images/carousel/banner2.jpg'),
    asset('images/carousel/banner3.jpg'),
    asset('images/carousel/promo_new.jpg'),  // ← tambahkan baris ini
];
```

### 3️⃣ Refresh Browser
Kunjungi: `http://127.0.0.1:8000`

Gambar baru akan muncul di carousel! ✅

---

## Tips

| Kebutuhan | Solusi |
|-----------|--------|
| Ganti durasi auto-rotate | Edit `setInterval(..., 5000)` di dashboard.blade.php (dalam ms) |
| Ganti ukuran carousel | Edit `h-64 md:h-96` di dashboard.blade.php |
| Tambah tombol/efek | Edit HTML carousel div di dashboard.blade.php |
| Hapus auto-rotate | Comment/hapus `setInterval(...)` |

---

## Troubleshooting

❌ **Gambar tidak tampil?**
- Cek console browser (F12)
- Pastikan nama file sama persis (case-sensitive)
- Refresh cache browser (Ctrl+F5)

❌ **Hanya placeholder muncul?**
- Cek apakah folder `public/images/carousel/` kosong
- Upload file gambar ke folder tersebut

---

Untuk tutorial lengkap, baca: **TUTORIAL_CAROUSEL.md**
