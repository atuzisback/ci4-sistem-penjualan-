<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;

/**
 * Fitur 5 - Manajemen transaksi database (COMMIT dan ROLLBACK).
 */
class Transaksi extends BaseController
{
    private PenjualanModel $penjualan;

    public function __construct()
    {
        $this->penjualan = new PenjualanModel();
    }

    public function index(): string
    {
        $db = Database::connect();

        return view('transaksi/index', [
            'title'     => 'Transaksi DB',
            'pelanggan' => $db->table('pelanggan')->orderBy('nama_pelanggan')->get()->getResultArray(),
            'produk'    => (new ProdukModel())->orderBy('nama_produk')->findAll(),
            'hasil'     => session()->getFlashdata('hasil'),
            'database'  => $db->getDatabase(),
        ]);
    }

    /** Pengujian 1: kondisi berhasil -> COMMIT. */
    public function ujiSukses(): RedirectResponse
    {
        $no     = 'OK-' . date('mdHis');           // maks. 15 karakter (VARCHAR(15))
        $header = [
            'no_penjualan'   => $no,
            'tgl_penjualan'  => date('Y-m-d'),
            'kode_pelanggan' => 'C001',
            'total'          => 126500,
            'keterangan'     => 'Pengujian Transaksi Berhasil (CodeIgniter 4)',
        ];
        $detail = [
            ['no_penjualan' => $no, 'no_urut' => 1, 'kode_produk' => 'P001', 'jumlah' => 2, 'harga' => 58000, 'subtotal' => 116000],
            ['no_penjualan' => $no, 'no_urut' => 2, 'kode_produk' => 'P002', 'jumlah' => 3, 'harga' => 3500,  'subtotal' => 10500],
        ];

        return $this->jalankan($header, $detail, 'PENGUJIAN KONDISI BERHASIL (COMMIT)');
    }

    /** Pengujian 2: detail kedua memakai kode_produk yang tidak ada -> ROLLBACK. */
    public function ujiGagal(): RedirectResponse
    {
        $no     = 'FAIL-' . date('mdHis');         // 15 karakter
        $header = [
            'no_penjualan'   => $no,
            'tgl_penjualan'  => date('Y-m-d'),
            'kode_pelanggan' => 'C001',
            'total'          => 98000,
            'keterangan'     => 'Pengujian Rollback (kode produk salah, CodeIgniter 4)',
        ];
        $detail = [
            ['no_penjualan' => $no, 'no_urut' => 1, 'kode_produk' => 'P001',       'jumlah' => 1, 'harga' => 58000, 'subtotal' => 58000],
            ['no_penjualan' => $no, 'no_urut' => 2, 'kode_produk' => 'P999_GAGAL', 'jumlah' => 2, 'harga' => 20000, 'subtotal' => 40000],
        ];

        return $this->jalankan($header, $detail, 'PENGUJIAN KONDISI GAGAL (ROLLBACK)');
    }

    /** Simpan transaksi dari form (minimal 2 baris detail). */
    public function simpan(): RedirectResponse
    {
        $no        = trim((string) $this->request->getPost('no_penjualan'));
        $tgl       = trim((string) $this->request->getPost('tgl_penjualan'));
        $pelanggan = trim((string) $this->request->getPost('kode_pelanggan'));
        $ket       = trim((string) $this->request->getPost('keterangan'));
        $produkArr = (array) $this->request->getPost('prod');
        $qtyArr    = (array) $this->request->getPost('qty');

        if ($no === '' || $tgl === '' || $pelanggan === '') {
            return $this->kembali('danger', 'Input Tidak Lengkap', ['Harap lengkapi nomor transaksi, tanggal, dan pelanggan.']);
        }

        $produkModel = new ProdukModel();
        $detail      = [];
        $total       = 0;
        $urut        = 1;

        foreach ($produkArr as $i => $kode) {
            $qty = (int) ($qtyArr[$i] ?? 0);
            if ($kode === '' || $qty <= 0) {
                continue;
            }
            $harga    = (float) ($produkModel->find($kode)['harga_jual'] ?? 0);
            $subtotal = $qty * $harga;
            $total   += $subtotal;
            $detail[] = [
                'no_penjualan' => $no, 'no_urut' => $urut++, 'kode_produk' => $kode,
                'jumlah' => $qty, 'harga' => $harga, 'subtotal' => $subtotal,
            ];
        }

        if (count($detail) < 2) {
            return $this->kembali('danger', 'Syarat Transaksi', ['Minimal harus ada 2 baris detail produk yang diisi.']);
        }

        $header = [
            'no_penjualan' => $no, 'tgl_penjualan' => $tgl, 'kode_pelanggan' => $pelanggan,
            'total' => $total, 'keterangan' => $ket !== '' ? $ket : 'Transaksi Penjualan Manual',
        ];

        return $this->jalankan($header, $detail, 'TRANSAKSI MANUAL');
    }

    /** Jalankan transaksi lalu simpan hasil + bukti (jumlah baris di DB) ke flashdata. */
    private function jalankan(array $header, array $detail, string $judul): RedirectResponse
    {
        $sebelum = $this->penjualan->hitungBaris($header['no_penjualan']);
        $hasil   = $this->penjualan->simpanTransaksi($header, $detail);
        $baris   = $this->penjualan->hitungBaris($header['no_penjualan']);

        $hasil['title'] = $judul . ($hasil['berhasil'] ? ' - COMMIT DITERAPKAN' : ' - ROLLBACK DITERAPKAN');
        $hasil['type']  = $hasil['berhasil'] ? 'success' : 'warning';
        $hasil['bukti'] = "Pengecekan database untuk {$header['no_penjualan']}: "
            . "sebelum (header {$sebelum['header']}, detail {$sebelum['detail']}) -> "
            . "sesudah (header {$baris['header']}, detail {$baris['detail']}). "
            . ($hasil['berhasil']
                ? 'Header dan seluruh detail tersimpan.'
                : ($sebelum === $baris
                    ? 'Tidak ada baris yang bertambah: header dan detail yang sempat dimasukkan dibatalkan (atomicity terbukti).'
                    : 'Terjadi perubahan tak terduga, periksa data.'));

        return redirect()->to('transaksi')->with('hasil', $hasil);
    }

    private function kembali(string $type, string $title, array $logs): RedirectResponse
    {
        return redirect()->to('transaksi')->with('hasil', ['type' => $type, 'title' => $title, 'logs' => $logs, 'bukti' => '']);
    }
}
