<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Fitur 1 - CRUD Produk
$routes->group('produk', static function ($routes) {
    $routes->get('/', 'Produk::index');
    $routes->get('tambah', 'Produk::tambah');
    $routes->post('simpan', 'Produk::simpan');
    $routes->get('edit/(:segment)', 'Produk::edit/$1');
    $routes->post('update/(:segment)', 'Produk::update/$1');
    $routes->post('hapus/(:segment)', 'Produk::hapus/$1');
});

// Fitur 2, 3, 4 - JOIN dan agregasi
$routes->get('transaksi-join', 'Laporan::transaksiJoin');
$routes->get('agregasi-pelanggan', 'Laporan::agregasiPelanggan');
$routes->get('laporan-produk', 'Laporan::laporanProduk');

// Fitur 5 - Manajemen transaksi database
$routes->get('transaksi', 'Transaksi::index');
$routes->post('transaksi/uji-sukses', 'Transaksi::ujiSukses');
$routes->post('transaksi/uji-gagal', 'Transaksi::ujiGagal');
$routes->post('transaksi/simpan', 'Transaksi::simpan');
