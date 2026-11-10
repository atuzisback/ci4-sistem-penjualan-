# Peta Kode untuk Tabel Analisis (Bagian E)

Daftar letak kode yang bisa dibandingkan sisi per sisi. **Analisis dan kesimpulannya tulis sendiri** (sesuai ketentuan tugas).

| Aspek (tabel E) | PHP Native (repo teman) | CodeIgniter 4 (proyek ini) |
|---|---|---|
| Koneksi database | `koneksi.php` (`new PDO(...)`) | `app/Config/Database.php` + `.env` |
| SELECT dan pencarian | `produk.php` | `ProdukModel::cari()`, `Controllers/Produk::index()` |
| INSERT | `produk_tambah.php` | `Controllers/Produk::simpan()` -> `ProdukModel::insert()` |
| UPDATE dan DELETE | `produk_edit.php`, `produk_hapus.php` | `Produk::update()`, `Produk::hapus()` |
| JOIN | `transaksi_join.php` | `PenjualanModel::daftarDenganPelanggan()` |
| Agregasi | `agregasi_pelanggan.php` | `PenjualanModel::agregasiPerPelanggan()` |
| Pembuatan laporan | `laporan_produk.php` | `DetilPenjualanModel::laporanPerProduk()`, `Controllers/Laporan.php` |
| Memulai transaksi | `transaksi_manajemen.php` -> `beginTransaction()` | `PenjualanModel::simpanTransaksi()` -> `transBegin()` |
| COMMIT dan ROLLBACK | `commit()` / `rollBack()` di blok try-catch | `transCommit()` / `transRollback()` |
| Penanganan kegagalan | `catch (PDOException)` | `transException(true)` + `catch (Throwable)` |
| Organisasi kode | satu file PHP per halaman (SQL + HTML bercampur) | Controller / Model / View terpisah |

Bukti pengujian: halaman `/transaksi` menampilkan log langkah-langkah dan jumlah baris di database sebelum/sesudah
(kondisi berhasil: header 1 + detail 2 tersimpan; kondisi gagal: tidak ada baris yang bertambah).
