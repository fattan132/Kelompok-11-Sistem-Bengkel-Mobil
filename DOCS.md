# Dokumentasi Aplikasi Servis Mobil

Dokumentasi singkat untuk aplikasi servis mobil berbasis Laravel.

## Model Utama

### User
- **Fields**: id, name, email, password, role (admin, kasir, manager, customer)
- **Relasi**: hasMany ServiceBooking, hasMany UserVoucher

### Service
- **Fields**: id, name, description, difficulty_level (easy, medium, hard), price
- **Relasi**: hasMany ServiceBookingItem

### ServiceBooking
- **Fields**: id, user_id, mechanic_notes, service_fee, status, created_at
- **Relasi**: belongsTo User, hasMany ServiceBookingItem

### ServiceBookingItem
- **Fields**: id, service_booking_id, service_id, quantity, price
- **Relasi**: belongsTo ServiceBooking, belongsTo Service

### Voucher
- **Fields**: id, code, discount_type (percentage/fixed), discount_value, expiry_date
- **Relasi**: hasMany UserVoucher

### UserVoucher
- **Fields**: id, user_id, voucher_id, used
- **Relasi**: belongsTo User, belongsTo Voucher

### ServiceFeeTemplate
- **Fields**: id, difficulty_level, fee_amount
- **Relasi**: -

## Endpoint Utama (Routes)

### Web Routes (routes/web.php)
- GET / : Halaman welcome
- Auth routes (login, register, dll.) via Laravel Breeze/UI
- /admin/* : Dashboard admin (services, bookings, dll.)
- /customer/* : Dashboard customer (booking, vouchers)
- /kasir/* : Dashboard kasir (manage bookings)
- /manager/* : Dashboard manager (reports, dll.)

### Controller Utama
- **ServiceController**: CRUD services
- **ServiceBookingController**: Manage bookings
- **VoucherController**: Manage vouchers
- **UserController**: Manage users

## Fitur Detail

### Booking Servis
1. Customer login dan pilih layanan.
2. Tambah item servis ke booking.
3. Terapkan voucher jika ada.
4. Hitung total dengan biaya servis berdasarkan difficulty.
5. Submit booking, status pending.

### Manajemen Voucher
- Admin buat voucher dengan kode, diskon, expiry.
- Customer dapat klaim voucher.
- Voucher digunakan saat booking.

### Role Access
- **Admin**: Full access, manage all.
- **Kasir**: Manage bookings, update status.
- **Manager**: View reports, manage services.
- **Customer**: Booking, view history.

## Database Migrations
- users_table
- services_table
- service_bookings_table
- service_booking_items_table
- vouchers_table
- service_fee_templates_table
- dll.

## Seeder
- DatabaseSeeder: Jalankan semua seeder
- ServiceDifficultySeeder: Seed difficulty levels
- VoucherSeeder: Seed sample vouchers

## Testing
- Gunakan PHPUnit untuk unit test.
- Test model, controller, dll.

Untuk dokumentasi lebih detail, lihat kode source atau gunakan tools seperti Swagger untuk API docs jika ada.