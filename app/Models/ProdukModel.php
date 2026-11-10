<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel produk (CRUD memakai fasilitas Model CodeIgniter 4).
 */
class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'kode_produk';
    protected $useAutoIncrement = false;          // PK berupa kode teks (P001, dst.)
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kode_produk', 'nama_produk', 'satuan',
        'harga_jual', 'harga_beli', 'stok', 'deskripsi',
    ];

    protected $validationRules = [
        'kode_produk' => 'required|max_length[10]|is_unique[produk.kode_produk]',
        'nama_produk' => 'required|max_length[100]',
        'satuan'      => 'required|max_length[20]',
        'harga_jual'  => 'required|numeric|greater_than_equal_to[0]',
        'harga_beli'  => 'required|numeric|greater_than_equal_to[0]',
        'stok'        => 'required|integer|greater_than_equal_to[0]',
    ];

    /**
     * SELECT semua produk, atau pencarian berdasarkan nama/kode (tidak peka huruf besar-kecil).
     */
    public function cari(?string $kataKunci = null): array
    {
        $this->orderBy('kode_produk', 'ASC');

        if ($kataKunci !== null && $kataKunci !== '') {
            $this->groupStart()
                    ->like('nama_produk', $kataKunci, 'both', null, true)
                    ->orLike('kode_produk', $kataKunci, 'both', null, true)
                 ->groupEnd();
        }

        return $this->findAll();
    }
}
