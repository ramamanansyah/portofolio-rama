# Panduan Upload Gambar - Blog & Proyek

## 📏 Ukuran Gambar yang Direkomendasikan

### **1. Gambar Blog Card (Index & Blog Page)**
- **Ukuran Display**: 35% dari lebar container (aspect ratio)
- **Ukuran Rekomendasi Upload**: 800x500px atau 1200x750px
- **Aspect Ratio**: 16:10 atau 3:2
- **Format**: JPG, PNG, WebP
- **Ukuran File Max**: 2MB (untuk performa optimal)

### **2. Featured Image (Blog Detail Page)**
- **Ukuran Display**: 35% dari lebar container (aspect ratio)
- **Ukuran Rekomendasi Upload**: 1200x600px atau 1600x800px
- **Aspect Ratio**: 16:9 atau 2:1
- **Format**: JPG, PNG, WebP
- **Ukuran File Max**: 3MB

### **3. Project/Portfolio Images**
- **Ukuran Display**: 35% dari lebar container (sama dengan blog)
- **Ukuran Rekomendasi Upload**: 1000x500px atau 1200x600px
- **Aspect Ratio**: 5:2 atau 16:9
- **Format**: JPG, PNG, WebP
- **Ukuran File Max**: 2MB

### **4. Thumbnail Admin (Admin Panel)**
- **Ukuran Display**: 80x80px
- **Otomatis di-crop**: Ya (center-crop)

## 🎨 Cara Kerja Sistem

### **Object-Fit: Contain (Updated!)**
Semua gambar menggunakan `object-fit: contain` yang berarti:
- ✅ **SELURUH gambar akan ditampilkan** tanpa crop
- ✅ Aspect ratio gambar **100% terjaga** 
- ✅ **TIDAK ADA bagian yang terpotong**
- ✅ Gambar akan di-resize otomatis untuk fit dalam container
- ✅ Jika aspect ratio berbeda, akan ada background abu-abu di sisi kosong

### **Responsive Design**
- Container menggunakan **aspect ratio 5:3** (card) dan **2:1** (detail)
- Semua ukuran **otomatis menyesuaikan** lebar layar
- Gambar akan **fit di dalam container** tanpa crop

### **Keuntungan Sistem Baru:**
✅ **Tidak ada crop** - Semua bagian gambar terlihat
✅ **Fleksibel** - Bisa upload portrait, landscape, atau square
✅ **No distortion** - Aspect ratio selalu terjaga
✅ **Professional look** - Background abu-abu untuk konsistensi

## 📸 Tips Upload Gambar Terbaik

### **DO ✅**
1. **Gunakan gambar landscape (16:9 atau 5:3)** untuk fill penuh tanpa letterbox
2. **Gambar square atau portrait juga OK** - akan tampil lengkap dengan background
3. **Kompress gambar** menggunakan tools seperti TinyPNG
4. **Gunakan gambar berkualitas tinggi** (minimal 800px lebar)
5. **Upload aspect ratio apa saja** - sistem akan handle dengan baik

### **DON'T ❌**
1. ❌ Jangan upload gambar terlalu kecil (<600px) - akan blur
2. ❌ Jangan upload gambar > 5MB - akan lambat loading
3. ❌ Jangan gunakan gambar dengan watermark besar
4. ❌ Hindari gambar dengan resolusi rendah

## 🛠️ Tools Rekomendasi

### **Image Editors (Online)**
- **Photopea** - photopea.com (gratis, seperti Photoshop)
- **Canva** - canva.com (template siap pakai)
- **Pixlr** - pixlr.com (editor cepat)

### **Image Compression**
- **TinyPNG** - tinypng.com (kompresi JPG/PNG)
- **Squoosh** - squoosh.app (kompresi dengan preview)

### **Aspect Ratio Calculator**
- **aspect-ratio.com** - Hitung ukuran berdasarkan ratio

## 🎯 Contoh Ukuran Ideal

### Blog Card
```
Ukuran Upload: 1200 x 750 px (16:10)
Display Size: Auto x 250 px
File Size: ~200-500 KB
```

### Featured Image
```
Ukuran Upload: 1600 x 900 px (16:9)
Display Size: Auto x 450 px
File Size: ~300-800 KB
```

## 💡 FAQ

**Q: Apa yang terjadi jika saya upload gambar dengan ukuran berbeda?**
A: Sistem akan menampilkan **SELURUH gambar** tanpa crop dengan `object-fit: contain`. Gambar akan di-resize agar fit dalam container. Jika aspect ratio berbeda dari container, akan muncul background abu-abu di sisi kosong (letterbox).

**Q: Apakah gambar akan terdistorsi?**
A: TIDAK! Sistem menggunakan `object-fit: contain` yang menjaga aspect ratio 100%. Tidak ada distorsi sama sekali.

**Q: Bagian gambar saya terpotong, kenapa?**
A: Dengan sistem baru, **TIDAK ADA bagian yang terpotong**. Semua gambar akan tampil lengkap dari ujung ke ujung.

**Q: Kenapa ada ruang abu-abu di samping/atas bawah gambar?**
A: Ini normal untuk gambar dengan aspect ratio berbeda dari container. Ini disebut "letterboxing" dan memastikan seluruh gambar terlihat tanpa crop.

**Q: Ukuran file maksimal?**
A: Secara teknis tidak ada batas di kode, tapi untuk performa website disarankan max 2-3MB per gambar.

**Q: Format gambar apa yang didukung?**
A: JPG, PNG, GIF, WebP. Rekomendasi JPG untuk foto, PNG untuk gambar dengan transparansi.

**Q: Apakah gambar portrait/vertikal bisa di-upload?**
A: YA! Sistem baru mendukung semua orientasi gambar (portrait, landscape, square) tanpa crop.

## 🔄 Cara Update Gambar

1. Login sebagai admin
2. Masuk ke halaman Admin Blog
3. Klik Edit pada artikel yang ingin diubah gambarnya
4. Upload gambar baru
5. Simpan - gambar lama akan tertimpa

---

**Catatan Penting**: 
- ✅ Sistem menggunakan `object-fit: contain` - **TIDAK ADA CROP**
- ✅ **SEMUA gambar** (Blog & Proyek) akan ditampilkan lengkap tanpa potongan
- ✅ Mendukung **semua aspect ratio** (landscape, portrait, square)
- ✅ Background abu-abu otomatis untuk konsistensi visual
- ✅ **Ukuran card konsisten** - Blog, Proyek, dan Layanan menggunakan grid 300px
- ✅ Dioptimalkan untuk pengalaman visual terbaik di semua ukuran layar
