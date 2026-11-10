<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Database;
use Throwable;

/**
 * Fitur 1 - CRUD Produk (Controller -> ProdukModel -> View).
 */
class Produk extends BaseController
{
    private ProdukModel $produk;

    public function __construct()
    {
        $this->produk = new ProdukModel();
    }

    private const KOLOM = ['kode_produk', 'nama_produk', 'satuan', 'harga_jual', 'harga_beli', 'stok', 'deskripsi'];

    /** READ + pencarian berdasarkan nama. */
    public function index(): string
    {
        $q    = trim((string) $this->request->getGet('q'));
        $data = $this->produk->cari($q);

        return view('produk/index', [
            'title' => 'CRUD Produk',
            'daftar' => $data,
            'q'      => $q,
            'sql'    => (string) Database::connect()->getLastQuery(),
        ]);
    }

    public function tambah(): string
    {
        return view('produk/form', ['title' => 'Tambah Produk', 'produk' => null]);
    }

    /** CREATE. */
    public function simpan(): RedirectResponse
    {
        $data = $this->request->getPost(self::KOLOM);
        $data['kode_produk'] = trim((string) $data['kode_produk']);

        try {
            if (! $this->produk->insert($data, false)) {
                return redirect()->back()->withInput()->with('errors', $this->produk->errors());
            }
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('galat', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return redirect()->to('produk')->with('sukses', 'Produk berhasil ditambahkan.');
    }

    public function edit(string $kode): string|RedirectResponse
    {
        $produk = $this->produk->find($kode);
        if ($produk === null) {
            return redirect()->to('produk')->with('galat', 'Produk tidak ditemukan.');
        }

        return view('produk/form', ['title' => 'Edit Produk', 'produk' => $produk]);
    }

    /** UPDATE. */
    public function update(string $kode): RedirectResponse
    {
        $data = $this->request->getPost(array_slice(self::KOLOM, 1)); // kode_produk tidak diubah

        try {
            if (! $this->produk->update($kode, $data)) {
                return redirect()->back()->withInput()->with('errors', $this->produk->errors());
            }
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('galat', 'Gagal mengubah: ' . $e->getMessage());
        }

        return redirect()->to('produk')->with('sukses', 'Produk berhasil diperbarui.');
    }

    /** DELETE. */
    public function hapus(string $kode): RedirectResponse
    {
        if ($this->produk->find($kode) === null) {
            return redirect()->to('produk')->with('galat', 'Produk tidak ditemukan atau sudah dihapus.');
        }

        try {
            $this->produk->delete($kode);
        } catch (Throwable $e) {
            return redirect()->to('produk')->with('galat', 'Produk tidak dapat dihapus karena sudah dipakai pada transaksi (' . $e->getMessage() . ').');
        }

        return redirect()->to('produk')->with('sukses', 'Produk berhasil dihapus.');
    }
}
