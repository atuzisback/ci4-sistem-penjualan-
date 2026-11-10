<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-1"><i class="bi bi-people text-primary me-2"></i>3. Agregasi Penjualan per Pelanggan</h3>
<p class="text-secondary">Fungsi agregasi tetap SQL: <code>COUNT()</code> dan <code>SUM()</code> ditulis sebagai string di <code>->select()</code>, lalu <code>->groupBy()</code>.</p>

<div class="card mb-4"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Kode</th><th>Nama Pelanggan</th><th>Alamat</th><th class="text-end">Jumlah Transaksi</th><th class="text-end">Total Nilai Penjualan</th></tr></thead>
        <tbody>
        <?php $n = 0; $total = 0; foreach ($daftar as $r) : $n += (int) $r['jumlah_transaksi']; $total += (float) $r['total_nilai_penjualan']; ?>
            <tr><td><?= esc($r['kode_pelanggan']) ?></td><td class="fw-semibold"><?= esc($r['nama_pelanggan']) ?></td><td><?= esc($r['alamat']) ?></td>
                <td class="text-end"><?= (int) $r['jumlah_transaksi'] ?></td><td class="text-end"><?= rupiah($r['total_nilai_penjualan']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light"><tr><th colspan="3" class="text-end">Total</th><th class="text-end"><?= $n ?></th><th class="text-end"><?= rupiah($total) ?></th></tr></tfoot>
    </table>
</div></div>

<h6 class="fw-bold">SQL yang dihasilkan CodeIgniter</h6>
<div class="sql-box"><?= esc($sql) ?></div>
<?= $this->endSection() ?>
