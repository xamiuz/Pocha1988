# 1988 Pocha Samarinda - Web Ordering & Admin System

Sistem pemesanan makanan & minuman online dan manajemen kasir/admin untuk 1988 Pocha Samarinda. Dibangun menggunakan Laravel, didukung database Supabase PostgreSQL, dan dioptimalkan untuk deployment ke Vercel.

## Fitur Utama
- **Katalog Menu & Pemesanan**: Pelanggan dapat memesan menu makanan/minuman dengan penyesuaian porsi dan level kepedasan.
- **Ongkos Kirim & Delivery Zone**: Perhitungan tarif ongkos kirim otomatis berdasarkan area Samarinda.
- **Promo Otomatis**: Diskon dan promo otomatis untuk pelanggan tanpa repot input kode promo.
- **Metode Pembayaran Fleksibel**: Mendukung QRIS, Transfer Bank, dan Bayar di Tempat (COD).
- **Admin Panel**:
  - Manajemen pesanan masuk secara real-time.
  - Live chat pesan antar kasir & pelanggan per pesanan.
  - Kelola menu & varian dengan popup interaktif responsif.
  - Pengaturan buka/tutup toko dan nomor rekening/QRIS.
  - Arsip & riwayat pesanan.

## Tech Stack
- **Framework**: Laravel 11 / PHP 8.4
- **Database**: Supabase PostgreSQL
- **Hosting / Serverless**: Vercel (`vercel-php`)
- **Frontend**: Blade + Tailwind CSS / Vanilla CSS & Alpine.js
