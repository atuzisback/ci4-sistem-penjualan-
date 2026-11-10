<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-1"><i class="bi bi-intersect text-primary me-2"></i>2. JOIN Transaksi Penjualan</h3>
<p class="text-secondary">JOIN tabel <code>penjualan</code> dan <code>pelanggan</code> memakai Query Builder: <code>->join('pelanggan c', 'c.kode_pelanggan = p.kode_pelanggan')</code>.</p>

<div class="card mb-4"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>No. Penjualan</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th><th class="text-end">Total</th><th></th></tr></thead>
        <tbody>
        <?php $grand = 0; foreach ($transaksi as $i => $t) : $grand += (float) $t['total']; ?>
            <tr>
                <td class="fw-semibold"><?= esc($t['no_penjualan']) ?></td>
                <td><?= tgl_indo($t['tgl_penjualan']) ?></td>
                <td><?= esc($t['nama_pelanggan']) ?> <span class="text-secondary small">(<?= esc($t['kode_pelanggan']) ?>)</span></td>
                <td><?= esc($t['keterangan']) ?></td>
                <td class="text-end"><?= rupiah($t['total']) ?></td>
                <td class="text-end"><button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#d<?= $i ?>">Rincian</button></td>
            </tr>
            <tr class="collapse" id="d<?= $i ?>"><td colspan="6" class="bg-light">
                <table class="table table-sm mb-0">
                    <thead><tr><th>#</th><th>Produk</th><th class="text-end">Jumlah</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($rincian[$t['no_penjualan']] ?? [] as $d) : ?>
                        <tr><td><?= (int) $d['no_urut'] ?></td><td><?= esc($d['nama_produk']) ?> (<?= esc($d['kode_produk']) ?>)</td>
                            <td class="text-end"><?= (int) $d['jumlah'] ?></td><td class="text-end"><?= rupiah($d['harga']) ?></td><td class="text-end"><?= rupiah($d['subtotal']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td></tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light"><tr><th colspan="4" class="text-end">Total Keseluruhan</th><th class="text-end"><?= rupiah($grand) ?></th><th></th></tr></tfoot>
    </table>
</div></div>

<h6 class="fw-bold">SQL yang dihasilkan CodeIgniter</h6>
<div class="sql-box"><?= esc($sql) ?></div>
<?= $this->endSection() ?>
