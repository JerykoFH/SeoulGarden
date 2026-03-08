<?php
include_once('../config/db.php');

$table = 'detail_meja_reservasi';
$title = 'Detail Meja Reservasi';

$joinQuery = "
SELECT 
  dmr.reservasi_id,
  r.tgl_reservasi,
  dmr.nomor_meja,
  m.lokasi,
  m.kapasitas
FROM 
  detail_meja_reservasi dmr
JOIN reservasi r ON dmr.reservasi_id = r.reservasi_id
JOIN meja m ON dmr.nomor_meja = m.nomor_meja
";

// Dropdown options
$reservasiList = $pdo->query("SELECT reservasi_id, tgl_reservasi FROM reservasi")->fetchAll();
$mejaList = $pdo->query("SELECT nomor_meja, lokasi FROM meja")->fetchAll();

$formFields = [
  'reservasi_id' => [
    'label' => 'Tanggal Reservasi',
    'type' => 'select',
    'required' => true,
    'options' => $reservasiList,
    'option_label' => fn($row) => $row['tgl_reservasi']
  ],
  'nomor_meja' => [
    'label' => 'Nomor Meja',
    'type' => 'select',
    'required' => true,
    'options' => $mejaList,
    'option_label' => fn($row) => $row['nomor_meja'] . ' - ' . $row['lokasi']
  ],
];

// Composite key
$primaryKeys = ['reservasi_id', 'nomor_meja'];

include_once('../template/join_template.php');
