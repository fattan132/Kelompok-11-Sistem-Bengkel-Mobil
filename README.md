# Aplikasi Servis Mobil

Aplikasi web untuk manajemen servis mobil berbasis Laravel. Aplikasi ini memungkinkan pengguna untuk melakukan booking servis, mengelola voucher, dan admin untuk mengatur layanan servis.

## Fitur Utama

- **Manajemen Layanan Servis**: Tambah, edit, dan hapus layanan servis dengan tingkat kesulitan.
- **Booking Servis**: Pelanggan dapat memesan servis dengan item-item tertentu.
- **Sistem Voucher**: Kelola voucher diskon untuk pengguna.
- **Role-based Access**: Peran seperti Admin, Kasir, Manager, dan Customer.
- **Dashboard**: Interface untuk masing-masing role.

## Teknologi yang Digunakan

- **Backend**: Laravel (PHP Framework)
- **Frontend**: Blade Templates, Vite untuk asset bundling
- **Database**: MySQL (atau sesuai konfigurasi)
- **Testing**: PHPUnit

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & npm (untuk frontend assets)
- MySQL atau database lainnya

## Instalasi

1. **Clone Repository**:
   ```
   git clone <url-repo-anda>
   cd servismobilpaw
   ```

2. **Install Dependencies**:
   ```
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   - Salin file `.env.example` ke `.env`
   - Atur konfigurasi database di `.env`

4. **Generate Key**:
   ```
   php artisan key:generate
   ```

5. **Migrate Database**:
   ```
   php artisan migrate
   ```

6. **Seed Database** (opsional, untuk data dummy):
   ```
   php artisan db:seed
   ```

7. **Build Assets**:
   ```
   npm run build
   ```

## Menjalankan Aplikasi

- **Development Server**:
  ```
  php artisan serve
  ```

- **Frontend Development** (dengan hot reload):
  ```
  npm run dev
  ```

Aplikasi akan berjalan di `http://localhost:8000`

## Struktur Proyek

- `app/Models/`: Model Eloquent (User, Service, ServiceBooking, dll.)
- `app/Http/Controllers/`: Controller untuk logika aplikasi
- `database/migrations/`: Migrasi database
- `database/seeders/`: Seeder untuk data awal
- `resources/views/`: Template Blade untuk UI
- `routes/web.php`: Definisi rute web

## Testing

Jalankan test dengan:
```
php artisan test
```

## Troubleshooting

- **Error Database Connection**: Pastikan konfigurasi `.env` benar dan database sudah dibuat.
- **Assets Tidak Load**: Jalankan `npm run build` atau `npm run dev`.
- **Permission Issues**: Pastikan storage folder writable (`chmod -R 755 storage`).

## Kontribusi

1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Proyek ini menggunakan lisensi MIT.
