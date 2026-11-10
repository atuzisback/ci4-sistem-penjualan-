-- Studi Kasus: Sistem Penjualan dan Pembelian
-- PostgreSQL
-- Jalankan pada database kosong atau sesuaikan nama schema/database terlebih dahulu.

DROP TABLE IF EXISTS detil_penjualan CASCADE;
DROP TABLE IF EXISTS detil_pembelian CASCADE;
DROP TABLE IF EXISTS penjualan CASCADE;
DROP TABLE IF EXISTS pembelian CASCADE;
DROP TABLE IF EXISTS produk CASCADE;
DROP TABLE IF EXISTS pelanggan CASCADE;
DROP TABLE IF EXISTS supplier CASCADE;

CREATE TABLE produk (
    kode_produk   VARCHAR(10) PRIMARY KEY,
    nama_produk   VARCHAR(100) NOT NULL,
    satuan        VARCHAR(20) NOT NULL,
    harga_jual    NUMERIC(12,2) NOT NULL CHECK (harga_jual >= 0),
    harga_beli    NUMERIC(12,2) NOT NULL CHECK (harga_beli >= 0),
    stok          INTEGER NOT NULL DEFAULT 0 CHECK (stok >= 0),
    deskripsi     TEXT
);

CREATE TABLE pelanggan (
    kode_pelanggan VARCHAR(10) PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telepon     VARCHAR(25),
    alamat         TEXT
);

CREATE TABLE supplier (
    kode_supplier VARCHAR(10) PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    no_telepon    VARCHAR(25),
    alamat        TEXT
);

CREATE TABLE penjualan (
    no_penjualan   VARCHAR(15) PRIMARY KEY,
    tgl_penjualan  DATE NOT NULL,
    kode_pelanggan VARCHAR(10) NOT NULL REFERENCES pelanggan(kode_pelanggan),
    total          NUMERIC(12,2) NOT NULL DEFAULT 0 CHECK (total >= 0),
    keterangan     TEXT
);

CREATE TABLE detil_penjualan (
    no_penjualan VARCHAR(15) NOT NULL REFERENCES penjualan(no_penjualan) ON DELETE CASCADE,
    no_urut       INTEGER NOT NULL CHECK (no_urut > 0),
    kode_produk   VARCHAR(10) NOT NULL REFERENCES produk(kode_produk),
    jumlah        INTEGER NOT NULL CHECK (jumlah > 0),
    harga         NUMERIC(12,2) NOT NULL CHECK (harga >= 0),
    subtotal      NUMERIC(12,2) NOT NULL CHECK (subtotal >= 0),
    PRIMARY KEY (no_penjualan, no_urut)
);

CREATE TABLE pembelian (
    no_pembelian   VARCHAR(15) PRIMARY KEY,
    tgl_pembelian  DATE NOT NULL,
    kode_supplier  VARCHAR(10) NOT NULL REFERENCES supplier(kode_supplier),
    total          NUMERIC(12,2) NOT NULL DEFAULT 0 CHECK (total >= 0),
    keterangan     TEXT
);

CREATE TABLE detil_pembelian (
    no_pembelian VARCHAR(15) NOT NULL REFERENCES pembelian(no_pembelian) ON DELETE CASCADE,
    no_urut       INTEGER NOT NULL CHECK (no_urut > 0),
    kode_produk   VARCHAR(10) NOT NULL REFERENCES produk(kode_produk),
    jumlah        INTEGER NOT NULL CHECK (jumlah > 0),
    harga         NUMERIC(12,2) NOT NULL CHECK (harga >= 0),
    subtotal      NUMERIC(12,2) NOT NULL CHECK (subtotal >= 0),
    PRIMARY KEY (no_pembelian, no_urut)
);

CREATE INDEX idx_penjualan_tanggal ON penjualan(tgl_penjualan);
CREATE INDEX idx_penjualan_pelanggan ON penjualan(kode_pelanggan);
CREATE INDEX idx_pembelian_tanggal ON pembelian(tgl_pembelian);
CREATE INDEX idx_detil_penjualan_produk ON detil_penjualan(kode_produk);
CREATE INDEX idx_detil_pembelian_produk ON detil_pembelian(kode_produk);

INSERT INTO produk (kode_produk, nama_produk, satuan, harga_jual, harga_beli, stok, deskripsi) VALUES
('P001', 'Kertas A4', 'rim', 58000, 47000, 40, 'Kertas A4 80 gsm'),
('P002', 'Pulpen Hitam', 'buah', 3500, 2000, 150, 'Pulpen tinta hitam'),
('P003', 'Buku Tulis', 'buah', 6000, 3800, 100, 'Buku tulis 58 lembar'),
('P004', 'Tinta Printer', 'botol', 85000, 69000, 25, 'Tinta printer hitam'),
('P005', 'Map Plastik', 'buah', 2500, 1200, 200, 'Map dokumen');

INSERT INTO pelanggan (kode_pelanggan, nama_pelanggan, no_telepon, alamat) VALUES
('C001', 'Toko Maju', '081200000001', 'Surabaya'),
('C002', 'Koperasi Kampus', '081200000002', 'Sidoarjo'),
('C003', 'CV Sejahtera', '081200000003', 'Gresik');

INSERT INTO supplier (kode_supplier, nama_supplier, no_telepon, alamat) VALUES
('S001', 'PT Alat Tulis Nusantara', '0317000001', 'Surabaya'),
('S002', 'CV Kertas Jaya', '0317000002', 'Sidoarjo');

-- Data transaksi awal disertakan untuk kebutuhan laporan.
INSERT INTO penjualan (no_penjualan, tgl_penjualan, kode_pelanggan, total, keterangan) VALUES
('J20261001', '2026-10-01', 'C001', 119000, 'Transaksi contoh'),
('J20261002', '2026-10-02', 'C002', 18000, 'Transaksi contoh');

INSERT INTO detil_penjualan (no_penjualan, no_urut, kode_produk, jumlah, harga, subtotal) VALUES
('J20261001', 1, 'P001', 2, 58000, 116000),
('J20261001', 2, 'P002', 1, 3000, 3000),
('J20261002', 1, 'P003', 3, 6000, 18000);

INSERT INTO pembelian (no_pembelian, tgl_pembelian, kode_supplier, total, keterangan) VALUES
('B20261001', '2026-10-01', 'S001', 101000, 'Transaksi contoh'),
('B20261002', '2026-10-03', 'S002', 76000, 'Transaksi contoh');

INSERT INTO detil_pembelian (no_pembelian, no_urut, kode_produk, jumlah, harga, subtotal) VALUES
('B20261001', 1, 'P001', 2, 47000, 94000),
('B20261001', 2, 'P002', 3, 2000, 6000),
('B20261002', 1, 'P004', 1, 69000, 69000),
('B20261002', 2, 'P005', 5, 1400, 7000);
