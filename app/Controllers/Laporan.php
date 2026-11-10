<?php

namespace App\Controllers;

use App\Models\DetilPenjualanModel;
use App\Models\PenjualanModel;
use Config\Database;

/**
 * Fitur 2, 3, dan 4 - JOIN, agregasi, dan laporan (Query Builder).
 */
class Laporan extends BaseController
{
    private function sqlTerakhir(): string
    {
        return (string) Database::connect()->getLastQuery();
    }

    /** Fitur 2 - JOIN penjualan + pelanggan (dan rincian detail per transaksi). */
    public function transaksiJoin(): string
    {
        $transaksi = (new PenjualanModel())->daftarDenganPelanggan();
        $sql       = $this->sqlTerakhir();

        $rincian = [];
        foreach ((new DetilPenjualanModel())->rincianSemua() as $baris) {
            $rincian[$baris['no_penjualan']][] = $baris;
        }

        return view('laporan/transaksi_join', [
            'title'     => 'JOIN Penjualan',
            'transaksi' => $transaksi,
            'rincian'   => $rincian,
            'sql'       => $sql,
        ]);
    }

    /** Fitur 3 - COUNT, SUM, GROUP BY, JOIN per pelanggan. */
    public function agregasiPelanggan(): string
    {
        $daftar = (new PenjualanModel())->agregasiPerPelanggan();

        return view('laporan/agregasi_pelanggan', [
            'title'  => 'Agregasi Pelanggan',
            'daftar' => $daftar,
            'sql'    => $this->sqlTerakhir(),
        ]);
    }

    /** Fitur 4 - Laporan penjualan per produk. */
    public function laporanProduk(): string
    {
        $daftar = (new DetilPenjualanModel())->laporanPerProduk();

        return view('laporan/laporan_produk', [
            'title'  => 'Laporan Produk',
            'daftar' => $daftar,
            'sql'    => $this->sqlTerakhir(),
        ]);
    }
}
