<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

/**
 * Model untuk tabel penjualan: JOIN, agregasi, dan penyimpanan header+detail dalam satu transaksi.
 */
class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'no_penjualan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_penjualan', 'tgl_penjualan', 'kode_pelanggan', 'total', 'keterangan',
    ];

    protected $validationRules = [
        'no_penjualan'   => 'required|max_length[15]',
        'tgl_penjualan'  => 'required|valid_date[Y-m-d]',
        'kode_pelanggan' => 'required|max_length[10]',
        'total'          => 'required|numeric|greater_than_equal_to[0]',
    ];

    /**
     * Fitur 2 - JOIN penjualan dengan pelanggan.
     */
    public function daftarDenganPelanggan(): array
    {
        return $this->db->table('penjualan p')
            ->select('p.no_penjualan, p.tgl_penjualan, c.kode_pelanggan, c.nama_pelanggan, c.no_telepon, p.total, p.keterangan')
            ->join('pelanggan c', 'c.kode_pelanggan = p.kode_pelanggan')
            ->orderBy('p.tgl_penjualan', 'DESC')
            ->orderBy('p.no_penjualan', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Fitur 3 - Agregasi penjualan per pelanggan (COUNT, SUM, GROUP BY, LEFT JOIN).
     */
    public function agregasiPerPelanggan(): array
    {
        return $this->db->table('pelanggan c')
            ->select('c.kode_pelanggan, c.nama_pelanggan, c.no_telepon, c.alamat')
            ->select('COUNT(p.no_penjualan) AS jumlah_transaksi', false)
            ->select('COALESCE(SUM(p.total), 0) AS total_nilai_penjualan', false)
            ->join('penjualan p', 'c.kode_pelanggan = p.kode_pelanggan', 'left')
            ->groupBy('c.kode_pelanggan, c.nama_pelanggan, c.no_telepon, c.alamat')
            ->orderBy('total_nilai_penjualan', 'DESC')
            ->orderBy('c.kode_pelanggan', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Fitur 5 - Simpan 1 header + banyak detail dalam satu transaksi database.
     *
     * Memakai transaksi MANUAL CodeIgniter 4: transBegin() -> transCommit() / transRollback().
     *
     * @return array{berhasil: bool, logs: list<string>}
     */
    public function simpanTransaksi(array $header, array $detail): array
    {
        $logs        = [];
        $detilModel  = new DetilPenjualanModel($this->db);

        // Agar query yang gagal di dalam transaksi melempar exception (membawa pesan error PostgreSQL),
        // aktifkan transException(true). Tanpa ini CI4 hanya mengembalikan false dan menandai transStatus = false.
        $this->db->transException(true);

        $logs[] = '[1] Memulai transaksi database: $this->db->transBegin()';
        $this->db->transBegin();

        try {
            $logs[] = "[2] Menyimpan header penjualan (No: {$header['no_penjualan']}, Total: " . rupiah($header['total']) . ') ...';
            if ($this->insert($header, false) === false) {
                throw new \RuntimeException($this->pesanGagal($this, 'Penyimpanan header gagal'));
            }
            $logs[] = '    -> SUKSES: header penjualan tersimpan (masih di dalam transaksi).';

            foreach ($detail as $i => $d) {
                $logs[] = '[3.' . ($i + 1) . "] Menyimpan detail #{$d['no_urut']}: produk [{$d['kode_produk']}] x {$d['jumlah']} ...";
                if ($detilModel->insert($d, false) === false) {
                    throw new \RuntimeException($this->pesanGagal($detilModel, "Penyimpanan detail #{$d['no_urut']} gagal"));
                }
                $logs[] = "    -> SUKSES: detail baris {$d['no_urut']} tersimpan.";
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Status transaksi bernilai false.');
            }

            $logs[] = '[4] Semua proses berhasil. Menjalankan: $this->db->transCommit()';
            $this->db->transCommit();
            $logs[] = '    -> COMMIT selesai: data permanen di database PostgreSQL.';

            return ['berhasil' => true, 'logs' => $logs];
        } catch (Throwable $e) {
            $logs[] = '    -> GAGAL / EXCEPTION: ' . html_entity_decode($e->getMessage(), ENT_QUOTES);
            $logs[] = '[4] Menjalankan: $this->db->transRollback()';
            // Pada mode DBDebug=true CI4 sudah membatalkan transaksi saat query gagal;
            // transRollback() tetap aman dipanggil (tidak berefek jika transaksi sudah tidak aktif).
            $this->db->transRollback();
            $logs[] = '    -> ROLLBACK selesai: semua INSERT yang sempat berjalan dibatalkan.';

            return ['berhasil' => false, 'logs' => $logs];
        } finally {
            $this->db->transException(false);
        }
    }

    /** Pesan kegagalan: error validasi Model, atau error dari database. */
    private function pesanGagal(Model $model, string $konteks): string
    {
        $errors = $model->errors();
        if ($errors !== []) {
            return $konteks . ': ' . implode('; ', $errors);
        }

        $dbError = $this->db->error();

        return $konteks . ': ' . ($dbError['message'] !== '' ? $dbError['message'] : 'query ditolak database');
    }

    /**
     * Jumlah baris header & detail untuk satu nomor penjualan (bukti hasil pengujian).
     *
     * @return array{header: int, detail: int}
     */
    public function hitungBaris(string $noPenjualan): array
    {
        return [
            'header' => $this->db->table('penjualan')->where('no_penjualan', $noPenjualan)->countAllResults(),
            'detail' => $this->db->table('detil_penjualan')->where('no_penjualan', $noPenjualan)->countAllResults(),
        ];
    }
}
