-- Lembar latihan SQL laporan untuk kedua pendekatan
-- Jalankan pada PostgreSQL setelah schema_seed.sql.

-- 1. Daftar transaksi penjualan beserta nama pelanggan
SELECT p.no_penjualan, p.tgl_penjualan, c.nama_pelanggan, p.total
FROM penjualan p
JOIN pelanggan c ON c.kode_pelanggan = p.kode_pelanggan
ORDER BY p.tgl_penjualan, p.no_penjualan;

-- 2. Rincian transaksi penjualan
SELECT dp.no_penjualan, pr.nama_produk, dp.jumlah, dp.harga, dp.subtotal
FROM detil_penjualan dp
JOIN produk pr ON pr.kode_produk = dp.kode_produk
WHERE dp.no_penjualan = 'J20261001'
ORDER BY dp.no_urut;

-- 3. Produk terlaris berdasarkan total unit terjual
SELECT pr.kode_produk, pr.nama_produk, COALESCE(SUM(dp.jumlah), 0) AS total_unit_terjual
FROM produk pr
LEFT JOIN detil_penjualan dp ON dp.kode_produk = pr.kode_produk
GROUP BY pr.kode_produk, pr.nama_produk
ORDER BY total_unit_terjual DESC, pr.nama_produk;

-- 4. Total pembelian per supplier
SELECT s.kode_supplier, s.nama_supplier, COALESCE(SUM(pb.total), 0) AS total_pembelian
FROM supplier s
LEFT JOIN pembelian pb ON pb.kode_supplier = s.kode_supplier
GROUP BY s.kode_supplier, s.nama_supplier
ORDER BY total_pembelian DESC;

-- 5. Periksa konsistensi total header penjualan dengan detail
SELECT p.no_penjualan, p.total AS total_header,
       COALESCE(SUM(dp.subtotal), 0) AS total_detail
FROM penjualan p
LEFT JOIN detil_penjualan dp ON dp.no_penjualan = p.no_penjualan
GROUP BY p.no_penjualan, p.total
HAVING p.total <> COALESCE(SUM(dp.subtotal), 0);

-- 6. Periksa konsistensi total header pembelian dengan detail
SELECT pb.no_pembelian, pb.total AS total_header,
       COALESCE(SUM(dp.subtotal), 0) AS total_detail
FROM pembelian pb
LEFT JOIN detil_pembelian dp ON dp.no_pembelian = pb.no_pembelian
GROUP BY pb.no_pembelian, pb.total
HAVING pb.total <> COALESCE(SUM(dp.subtotal), 0);
