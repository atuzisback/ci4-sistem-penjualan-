<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Penjualan') ?> - Sistem Penjualan (CodeIgniter 4)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; }
        .main-content { flex: 1; }
        .badge-ci { background: linear-gradient(135deg, #dd4814, #f0814f); color: #fff; }
        .card { border-radius: .75rem; border: 1px solid rgba(0,0,0,.08); box-shadow: 0 2px 6px rgba(0,0,0,.03); }
        .sql-box { background: #1e293b; color: #f1f5f9; padding: 1rem; border-radius: .5rem;
                   font-family: Consolas, "Courier New", monospace; font-size: .88rem; overflow-x: auto; white-space: pre-wrap; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <span class="badge badge-ci px-2 py-1"><i class="bi bi-fire me-1"></i>CodeIgniter 4</span>
            <span>Sistem Penjualan</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                $menu = [
                    ['/', 'Dashboard', 'bi-house-door', url_is('/')],
                    ['produk', '1. CRUD Produk', 'bi-box-seam', url_is('produk*')],
                    ['transaksi-join', '2. JOIN Penjualan', 'bi-intersect', url_is('transaksi-join')],
                    ['agregasi-pelanggan', '3. Agregasi Pelanggan', 'bi-people', url_is('agregasi-pelanggan')],
                    ['laporan-produk', '4. Laporan Produk', 'bi-graph-up-arrow', url_is('laporan-produk')],
                    ['transaksi', '5. Transaksi DB', 'bi-shield-check', url_is('transaksi')],
                ];
                foreach ($menu as [$url, $label, $icon, $aktif]) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $aktif ? 'active fw-bold' : '' ?>" href="<?= site_url($url) ?>">
                            <i class="bi <?= $icon ?> me-1"></i> <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="main-content py-4">
    <div class="container">
        <?php if (session()->getFlashdata('sukses')) : ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('sukses')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('galat')) : ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-x-circle me-1"></i><?= esc(session()->getFlashdata('galat')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger"><ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('errors') as $e) : ?><li><?= esc($e) ?></li><?php endforeach; ?>
            </ul></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="text-center text-secondary small py-3 border-top bg-white">
    Praktikum Perbandingan Akses Database &middot; CodeIgniter 4 + PostgreSQL
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
