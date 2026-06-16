<?php

define('HOSPITAL_NAME',     'RS Medicaria');
define('HOSPITAL_ADDRESS',  'Jl. Merpati Raya No. 14, Jakarta');
define('HOSPITAL_PHONE',    '(021) 5550-1234');
define('HOSPITAL_EMAIL',    'halo@medicaria.id');

define('ANTRIAN_PERORANG',  3);

define('BASE_URL', '/santri-belajar/public');

function format_tanggal_id($date)
{
    if (!$date) return '-';

    $hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    $ts = strtotime($date);
    $namaHari  = $hari[date('w', $ts)];
    $tanggal   = date('j', $ts);
    $namaBulan = $bulan[date('n', $ts) - 1];
    $tahun     = date('Y', $ts);

    return $namaHari . ', ' . $tanggal . ' ' . $namaBulan . ' ' . $tahun;
}
