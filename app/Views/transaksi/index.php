<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="mb-3">
    <h3 class="fw-bold mb-1"><i class="bi bi-shield-check text-danger me-2"></i>5. Manajemen Transaksi Database</h3>
    <p class="text-secondary mb-0">Implementasi <code>transBegin()</code>, <code>transCommit()</code>, dan <code>transRollback()</code> di CodeIgniter 4.
        Database aktif: <span class="badge text-bg-dark"><?= esc($database) ?></span></p>
</div>

<?php if ($hasil) : ?>
    <div class="card border-start border-<?= esc($hasil['type']) ?> border-5 mb-4">
        <div class="card-header bg-white py-3"><h5 class="mb-0 fw-bold text-<?= esc($hasil['type']) ?>"><?= esc($hasil['title']) ?></h5></div>
        <div class="card-body">
            <div class="bg-dark text-white p-3 rounded font-monospace small mb-3">
                <div class="text-uppercase fw-bold text-secondary mb-2 border-bottom pb-1">// Log eksekusi transaksi CodeIgniter 4:</div>
                <?php foreach ($hasil['logs'] as $log) : ?><div class="py-1" style="white-space:pre-wrap"><?= esc($log) ?></div><?php endforeach; ?>
            </div>
            <?php if (! empty($hasil['bukti'])) : ?>
                <div class="alert alert-<?= esc($hasil['type']) ?> mb-0 fw-bold"><i class="bi bi-search me-2"></i><?= esc($hasil['bukti']) ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Panel Pengujian Transaksi</h5></div>
    <div class="card-body p-4"><div class="row g-4">
        <div class="col-md-6"><div class="p-3 border rounded h-100 bg-light">
            <h6 class="fw-bold text-success"><i class="bi bi-check-circle me-1"></i>Kondisi 1: Berhasil (COMMIT)</h6>
            <p class="small text-muted">1 header + 2 detail dengan kode produk valid (P001, P002). Semua berhasil, lalu <code>transCommit()</code>.</p>
            <form method="post" action="<?= site_url('transaksi/uji-sukses') ?>"><?= csrf_field() ?>
                <button class="btn btn-success w-100 fw-semibold"><i class="bi bi-play-circle-fill me-1"></i>Jalankan Uji Sukses</button></form>
        </div></div>
        <div class="col-md-6"><div class="p-3 border rounded h-100 bg-light">
            <h6 class="fw-bold text-danger"><i class="bi bi-x-circle me-1"></i>Kondisi 2: Gagal (ROLLBACK)</h6>
            <p class="small text-muted">Detail ke-2 memakai <code>P999_GAGAL</code> (tidak ada di tabel produk) sehingga melanggar foreign key. Header dan detail 1 dibatalkan lewat <code>transRollback()</code>.</p>
            <form method="post" action="<?= site_url('transaksi/uji-gagal') ?>"><?= csrf_field() ?>
                <button class="btn btn-danger w-100 fw-semibold"><i class="bi bi-play-circle-fill me-1"></i>Jalankan Uji Gagal</button></form>
        </div></div>
    </div></div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Input Transaksi Penjualan Manual</h5></div>
    <div class="card-body p-4">
        <form method="post" action="<?= site_url('transaksi/simpan') ?>"><?= csrf_field() ?>
            <div class="row g-3 mb-3">
                <div class="col-md-3"><label class="form-label">No. Penjualan</label>
                    <input type="text" name="no_penjualan" maxlength="15" class="form-control" value="J<?= date('mdHis') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_penjualan" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-3"><label class="form-label">Pelanggan</label>
                    <select name="kode_pelanggan" class="form-select" required>
                        <?php foreach ($pelanggan as $c) : ?><option value="<?= esc($c['kode_pelanggan']) ?>"><?= esc($c['nama_pelanggan']) ?></option><?php endforeach; ?>
                    </select></div>
                <div class="col-md-3"><label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"></div>
            </div>
            <table class="table table-sm align-middle">
                <thead><tr><th>#</th><th>Produk</th><th style="width:120px">Qty</th></tr></thead>
                <tbody>
                <?php for ($i = 0; $i < 4; $i++) : ?>
                    <tr><td><?= $i + 1 ?></td>
                        <td><select name="prod[]" class="form-select" <?= $i < 2 ? 'required' : '' ?>>
                            <option value="">-- pilih produk --</option>
                            <?php foreach ($produk as $p) : ?><option value="<?= esc($p['kode_produk']) ?>"><?= esc($p['nama_produk']) ?> - <?= rupiah($p['harga_jual']) ?></option><?php endforeach; ?>
                        </select></td>
                        <td><input type="number" min="1" name="qty[]" class="form-control" value="<?= $i < 2 ? 1 : '' ?>"></td></tr>
                <?php endfor; ?>
                </tbody>
            </table>
            <button class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Transaksi</button>
            <span class="text-secondary small ms-2">Minimal 2 baris detail.</span>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
