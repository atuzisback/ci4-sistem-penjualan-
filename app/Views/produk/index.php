<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h3 class="fw-bold mb-1"><i class="bi bi-box-seam text-primary me-2"></i>1. CRUD Produk</h3>
        <p class="text-secondary mb-0">Memakai <code>ProdukModel</code> (Model CodeIgniter 4) &mdash; tanpa menulis SQL manual.</p>
    </div>
    <a href="<?= site_url('produk/tambah') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
</div>

<form method="get" action="<?= site_url('produk') ?>" class="input-group mb-3">
    <input type="text" name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Cari produk berdasarkan nama atau kode ...">
    <button class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Cari</button>
    <?php if ($q !== '') : ?><a href="<?= site_url('produk') ?>" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
</form>

<div class="card mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Kode</th><th>Nama Produk</th><th>Satuan</th><th class="text-end">Harga Jual</th><th class="text-end">Harga Beli</th><th class="text-end">Stok</th><th>Deskripsi</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
            <?php if (empty($daftar)) : ?>
                <tr><td colspan="8" class="text-center text-secondary py-4">Data produk tidak ditemukan.</td></tr>
            <?php endif; ?>
            <?php foreach ($daftar as $p) : ?>
                <tr>
                    <td><span class="badge text-bg-secondary"><?= esc($p['kode_produk']) ?></span></td>
                    <td class="fw-semibold"><?= esc($p['nama_produk']) ?></td>
                    <td><?= esc($p['satuan']) ?></td>
                    <td class="text-end"><?= rupiah($p['harga_jual']) ?></td>
                    <td class="text-end"><?= rupiah($p['harga_beli']) ?></td>
                    <td class="text-end"><?= (int) $p['stok'] ?></td>
                    <td><?= esc($p['deskripsi']) ?></td>
                    <td class="text-center text-nowrap">
                        <a href="<?= site_url('produk/edit/' . $p['kode_produk']) ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form action="<?= site_url('produk/hapus/' . $p['kode_produk']) ?>" method="post" class="d-inline"
                              onsubmit="return confirm('Hapus produk <?= esc($p['nama_produk'], 'js') ?>?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <h6 class="fw-bold">Kode CodeIgniter (Model)</h6>
        <div class="sql-box">$produk = new ProdukModel();
$produk->findAll();                          // SELECT
$produk->insert($data, false);               // INSERT
$produk->update($kode, $data);               // UPDATE
$produk->delete($kode);                      // DELETE
$produk->groupStart()
       ->like('nama_produk', $q, 'both', null, true)
       ->orLike('kode_produk', $q, 'both', null, true)
       ->groupEnd()->findAll();              // pencarian</div>
    </div>
    <div class="col-lg-6">
        <h6 class="fw-bold">SQL yang dihasilkan CodeIgniter</h6>
        <div class="sql-box"><?= esc($sql) ?></div>
    </div>
</div>
<?= $this->endSection() ?>
