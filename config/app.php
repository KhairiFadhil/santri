<?php

define('HOSPITAL_NAME',     'RS Medicaria');
define('HOSPITAL_ADDRESS',  'Jl. Merpati Raya No. 14, Jakarta');
define('HOSPITAL_PHONE',    '(021) 5550-1234');
define('HOSPITAL_EMAIL',    'halo@medicaria.id');

define('QUEUE_ETA_PER_PERSON',  3);

define('BASE_URL', '/santri-belajar/public');

function format_tanggal_id(?string $date): string
{
    if (!$date) return '-';
    $ts = strtotime($date);
    if (!$ts) return $date;
    $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    return $hari[(int)date('w', $ts)] . ', ' . (int)date('j', $ts) . ' ' . $bulan[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
}
