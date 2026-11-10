# Sistem Penjualan & Pembelian - CodeIgniter 4 (PostgreSQL)

Bagian **CodeIgniter 4** dari tugas kelompok *Perbandingan CRUD, JOIN, dan Agregasi: PHP Native (PDO) vs CodeIgniter 4*.
Aplikasi ini memakai **database dan struktur tabel yang sama** dengan versi PHP Native (`schema_seed.sql`).

## Kebutuhan
- PHP 8.2+ dengan ekstensi `pgsql`, `intl`, `mbstring` aktif (XAMPP: hilangkan `;` pada `extension=pgsql`, `extension=pdo_pgsql`, `extension=intl` di `php.ini`, lalu restart)
- Composer
- PostgreSQL

## Cara menjalankan
1. Pasang dependensi (folder `vendor/` belum disertakan):
   ```
   composer install
   ```
2. Buat database `sistem_penjualan` di PostgreSQL, lalu impor `database/schema_seed.sql`
   (pgAdmin: Query Tool -> buka file -> Run, atau `psql -U postgres -d sistem_penjualan -f database/schema_seed.sql`).
3. Buka `.env` dan sesuaikan password PostgreSQL:
   ```
   database.default.password = PASSWORD_ANDA
   ```
   (Pengaturan koneksi lengkap ada di `app/Config/Database.php`.)
4. Jalankan server pengembangan:
   ```
   php spark serve
   ```
   lalu buka <http://localhost:8080>.

> Jika memakai XAMPP dengan virtual host yang mengarah ke proyek lain, gunakan `php spark serve` (port 8080)
> supaya tidak bentrok dengan Apache.

## Pengujian transaksi di database terpisah
Tugas meminta data awal tidak terganggu. Buat database `sistem_penjualan_test`, impor `schema_seed.sql` ke sana,
lalu ubah sementara baris `database.default.database` di `.env` menjadi `sistem_penjualan_test`.
Nama database aktif tampil di halaman **5. Transaksi DB**. Kembalikan setelah pengujian selesai.

## Fitur
| Menu | Route | Controller -> Model | Teknik |
|---|---|---|---|
| 1. CRUD Produk | `/produk` | `Produk` -> `ProdukModel` | Model (`findAll`, `insert`, `update`, `delete`, `like`) |
| 2. JOIN Penjualan | `/transaksi-join` | `Laporan` -> `PenjualanModel`, `DetilPenjualanModel` | Query Builder `join()` |
| 3. Agregasi Pelanggan | `/agregasi-pelanggan` | `Laporan` -> `PenjualanModel` | Query Builder `select('COUNT/SUM')`, `groupBy()` |
| 4. Laporan Produk | `/laporan-produk` | `Laporan` -> `DetilPenjualanModel` | LEFT JOIN + `SUM()` + `groupBy()` |
| 5. Transaksi DB | `/transaksi` | `Transaksi` -> `PenjualanModel::simpanTransaksi()` | `transBegin()`, `transCommit()`, `transRollback()` |

Setiap halaman laporan menampilkan **SQL yang dihasilkan CodeIgniter** (`getLastQuery()`), berguna untuk bahan perbandingan.
Lihat `PETA_KODE.md` untuk letak kode yang dibandingkan.
