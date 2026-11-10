<?php
$edit = $produk !== null;
$v = static fn (string $k, $default = '') => old($k, $produk[$k] ?? $default);
?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0"><i class="bi bi-<?= $edit ? 'pencil-square text-warning' : 'plus-circle text-primary' ?> me-2"></i><?= $edit ? 'Edit Produk' : 'Tambah Produk' ?></h3>
    <a href="<?= site_url('produk') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card"><div class="card-body p-4">
    <form method="post" action="<?= site_url($edit ? 'produk/update/' . $produk['kode_produk'] : 'produk/simpan') ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Kode Produk</label>
                <input type="text" name="kode_produk" maxlength="10" class="form-control" value="<?= esc($v('kode_produk')) ?>" <?= $edit ? 'readonly' : 'required' ?>>
            </div>
            <div class="col-md-5">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama_produk" maxlength="100" class="form-control" value="<?= esc($v('nama_produk')) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" maxlength="20" class="form-control" value="<?= esc($v('satuan')) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga Jual</label>
                <input type="number" step="0.01" min="0" name="harga_jual" class="form-control" value="<?= esc($v('harga_jual', 0)) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga Beli</label>
                <input type="number" step="0.01" min="0" name="harga_beli" class="form-control" value="<?= esc($v('harga_beli', 0)) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" min="0" name="stok" class="form-control" value="<?= esc($v('stok', 0)) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="form-control"><?= esc($v('deskripsi')) ?></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            <a href="<?= site_url('produk') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
<?= $this->endSection() ?>
