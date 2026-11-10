<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-3"><i class="bi bi-house-door me-2"></i>Dashboard - CodeIgniter 4</h3>

<?php if ($koneksi['ok']) : ?>
    <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i>
        Terhubung ke database <strong><?= esc($db) ?></strong> (PostgreSQL <?= esc($koneksi['versi']) ?>) melalui konfigurasi CodeIgniter.</div>
<?php else : ?>
    <div class="alert alert-danger"><strong>Koneksi gagal:</strong> <code><?= esc($koneksi['pesan']) ?></code><br>
        Periksa <code>.env</code> (password PostgreSQL) dan pastikan <code>schema_seed.sql</code> sudah diimpor.</div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($jumlah as $tabel => $n) : ?>
        <div class="col-6 col-md-3">
            <div class="card p-3 text-center">
                <div class="fs-2 fw-bold"><?= $n ?></div>
                <div class="text-secondary small"><?= esc($tabel) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
