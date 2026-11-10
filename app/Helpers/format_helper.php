<?php

if (! function_exists('rupiah')) {
    function rupiah($angka): string
    {
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('tgl_indo')) {
    function tgl_indo(?string $tanggal): string
    {
        return $tanggal ? date('d/m/Y', strtotime($tanggal)) : '-';
    }
}
