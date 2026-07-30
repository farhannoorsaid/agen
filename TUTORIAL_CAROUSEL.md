# Tutorial: Memasukkan Gambar ke Carousel Dashboard

## Langkah 1: Siapkan Folder untuk Gambar

1. Buka File Explorer / Windows Explorer
2. Navigasi ke folder: `c:\xampp\htdocs\laravel-project\agen-hendi\public`
3. Buat folder baru dengan nama: `images`
4. Di dalam folder `images`, buat folder baru lagi dengan nama: `carousel`

**Struktur akhirnya:**
```
public/
  ├── images/
  │   └── carousel/
  ├── index.php
  ├── robots.txt
  └── build/
```

## Langkah 2: Upload Gambar

1. Siapkan gambar-gambar Anda (format: JPG, PNG, GIF, WebP)
2. **Rekomendasi ukuran:** 1200x400 pixel (atau minimal 16:4 aspect ratio)
3. Copy gambar ke folder `public/images/carousel/`

**Contoh:**
- `banner1.jpg`
- `banner2.png`
- `banner3.jpg`
- `promo.jpg`

## Langkah 3: Update Dashboard Controller

Buka file: `app/Http/Controllers/DashboardController.php`

Cari method `index()` dan tambahkan array gambar:

```php
public function index()
{
    $barangLowStock = Barang::active()->lowStock()->get();
    $barangExpiringSoon = Barang::active()->expiringSoon()->get();
    
    $totalBarang = Barang::active()->count();
    $totalStok = Barang::active()->sum('stok');
    $totalPenjualan = StockOut::sum('harga_jual');

    // Tambahkan 2 baris ini:
    $carouselImages = [
        asset('images/carousel/banner1.jpg'),
        asset('images/carousel/banner2.png'),
        asset('images/carousel/banner3.jpg'),
    ];

    return view('dashboard', compact(
        'barangLowStock',
        'barangExpiringSoon',
        'totalBarang',
        'totalStok',
        'totalPenjualan',
        'carouselImages'  // Tambahkan ini
    ));
}
```

## Langkah 4: Update Dashboard View

Buka file: `resources/views/dashboard.blade.php`

Cari bagian `<script>` di akhir file, dan replace bagian array images:

**SEBELUM:**
```javascript
const carouselImages = [
    "https://via.placeholder.com/1200x400?text=Agen+Hendi+Banner+1",
    "https://via.placeholder.com/1200x400?text=Agen+Hendi+Banner+2",
    "https://via.placeholder.com/1200x400?text=Agen+Hendi+Banner+3"
];
```

**SESUDAH (jika gambar dari Laravel):**
```javascript
const carouselImages = {!! json_encode($carouselImages) !!};
```

**ATAU jika ingin hardcode URL gambar:**
```javascript
const carouselImages = [
    "{{ asset('images/carousel/banner1.jpg') }}",
    "{{ asset('images/carousel/banner2.png') }}",
    "{{ asset('images/carousel/banner3.jpg') }}",
    "{{ asset('images/carousel/promo.jpg') }}"
];
```

## Langkah 5: Test Dashboard

1. Refresh browser ke `http://127.0.0.1:8000`
2. Carousel akan menampilkan gambar pertama
3. Klik tombol **Next** untuk lihat gambar berikutnya
4. Gambar akan auto-rotate setiap 5 detik

## Tips & Troubleshooting

### ❓ Gambar tidak muncul?
- **Cek:** Apakah file gambar sudah ada di `public/images/carousel/`?
- **Cek:** Apakah nama file di PHP/View sama dengan nama file asli? (case-sensitive!)
- **Solusi:** Buka Inspector (F12) → Console → cek error message

### ❓ Ingin menambah gambar baru?
- Upload file gambar ke `public/images/carousel/`
- Tambahkan URL ke array `carouselImages` di dashboard.blade.php
- Refresh halaman

### ❓ Ingin mengubah durasi auto-rotate?
Cari baris ini di `dashboard.blade.php`:
```javascript
setInterval(() => {
    rotateCarousel(1);
}, 5000);  // 5000 = 5 detik
```

Ubah `5000` ke nilai lain (dalam milidetik):
- `3000` = 3 detik
- `7000` = 7 detik
- `10000` = 10 detik

### ❓ Ingin mengubah ukuran carousel?
Cari di `dashboard.blade.php`:
```html
<div class="relative h-64 md:h-96">
```

Ganti ukuran Tailwind:
- `h-64` = height 256px (mobile)
- `md:h-96` = height 384px (desktop)

Opsi: `h-48`, `h-56`, `h-80`, `h-screen`, dll

## Contoh Struktur Akhir

```
public/
  ├── images/
  │   └── carousel/
  │       ├── banner1.jpg       (1200x400)
  │       ├── banner2.png       (1200x400)
  │       ├── banner3.jpg       (1200x400)
  │       └── promo.jpg         (1200x400)
  ├── index.php
  ├── robots.txt
  └── build/
```

## Quick Setup (Jika Ingin Langsung Test)

Jika Anda sudah punya gambar, gunakan langsung di view tanpa ubah controller:

1. Upload 3 gambar ke `public/images/carousel/` (banner1.jpg, banner2.jpg, banner3.jpg)
2. Update `resources/views/dashboard.blade.php` script section:

```javascript
const carouselImages = [
    "{{ asset('images/carousel/banner1.jpg') }}",
    "{{ asset('images/carousel/banner2.jpg') }}",
    "{{ asset('images/carousel/banner3.jpg') }}"
];
```

3. Refresh browser - done! ✅

---

**Pertanyaan?** Hubungi admin atau cek console browser (F12) untuk error details.
