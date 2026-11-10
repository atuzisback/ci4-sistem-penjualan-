<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel detil_penjualan (PK komposit: no_penjualan + no_urut).
 */
class DetilPenjualanModel extends Model
{
    protected $table            = 'detil_penjualan';
    protected $primaryKey       = 'no_penjualan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_penjualan', 'no_urut', 'kode_produk', 'jumlah', 'harga', 'subtotal',
    ];

    /**
     * JOIN detil_penjualan dengan produk untuk menampilkan rincian transaksi.
     */
    public function rincianSemua(): array
    {
        return $this->db->table('detil_penjualan dp')
            ->select('dp.no_penjualan, dp.no_urut, pr.kode_produk, pr.nama_produk, dp.jumlah, dp.harga, dp.subtotal')
            ->join('produk pr', 'pr.kode_produk = dp.kode_produk')
            ->orderBy('dp.no_penjualan')
            ->orderBy('dp.no_urut')
            ->get()
            ->getResultArray();
    }

    /**
     * Laporan penjualan per produk (LEFT JOIN + SUM + GROUP BY).
     */
    public function laporanPerProduk(): array
    {
        return $this->db->table('produk pr')
            ->select('pr.kode_produk, pr.nama_produk, pr.satuan, pr.stok')
            ->select('COALESCE(SUM(dp.jumlah), 0) AS total_kuantitas_terjual', false)
            ->select('COALESCE(SUM(dp.subtotal), 0) AS total_nilai_penjualan', false)
            ->join('detil_penjualan dp', 'dp.kode_produk = pr.kode_produk', 'left')
            ->groupBy('pr.kode_produk, pr.nama_produk, pr.satuan, pr.stok')
            ->orderBy('total_kuantitas_terjual', 'DESC')
            ->orderBy('pr.nama_produk', 'ASC')
            ->get()
            ->getResultArray();
    }
}
