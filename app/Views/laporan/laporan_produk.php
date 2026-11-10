<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow text-primary me-2"></i>4. Laporan Penjualan per Produk</h3>
<p class="text-secondary">LEFT JOIN <code>produk</code> dengan <code>detil_penjualan</code>, lalu <code>SUM(jumlah)</code> dan <code>SUM(subtotal)</code> per produk.</p>

<div class="card mb-4"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Kode</th><th>Nama Produk</th><th>Satuan</th><th class="text-end">Stok</th><th class="text-end">Total Kuantitas Terjual</th><th class="text-end">Total Nilai Penjualan</th></tr></thead>
        <tbody>
        <?php $q = 0; $total = 0; foreach ($daftar as $r) : $q += (int) $r['total_kuantitas_terjual']; $total += (float) $r['total_nilai_penjualan']; ?>
            <tr><td><?= esc($r['kode_produk']) ?></td><td class="fw-semibold"><?= esc($r['nama_produk']) ?></td><td><?= esc($r['satuan']) ?></td><td class="text-end"><?= (int) $r['stok'] ?></td>
                <td class="text-end"><?= (int) $r['total_kuantitas_terjual'] ?></td><td class="text-end"><?= rupiah($r['total_nilai_penjualan']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light"><tr><th colspan="4" class="text-end">Total</th><th class="text-end"><?= $q ?></th><th class="text-end"><?= rupiah($total) ?></th></tr></tfoot>
    </table>
</div></div>

<h6 class="fw-bold">SQL yang dihasilkan CodeIgniter</h6>
<div class="sql-box"><?= esc($sql) ?></div>
<?= $this->endSection() ?>
