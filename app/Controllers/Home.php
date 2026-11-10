<?php

namespace App\Controllers;

use Config\Database;
use Throwable;

class Home extends BaseController
{
    public function index(): string
    {
        $db      = Database::connect();
        $tabel   = ['produk', 'pelanggan', 'supplier', 'penjualan', 'detil_penjualan', 'pembelian', 'detil_pembelian'];
        $jumlah  = [];
        $koneksi = ['ok' => true, 'pesan' => ''];

        try {
            foreach ($tabel as $t) {
                $jumlah[$t] = $db->table($t)->countAllResults();
            }
            $koneksi['versi'] = $db->getVersion();
        } catch (Throwable $e) {
            $koneksi = ['ok' => false, 'pesan' => $e->getMessage()];
        }

        return view('home', [
            'title'   => 'Dashboard',
            'jumlah'  => $jumlah,
            'koneksi' => $koneksi,
            'db'      => $db->getDatabase(),
        ]);
    }
}
