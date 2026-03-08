<?php
include_once('../config/db.php');

$table = 'shift_karyawan';
$title = 'Jadwal Shift Karyawan';

// Ambil data karyawan untuk pilihan select
$karyawanList = $pdo->query("SELECT karyawan_id, nama FROM karyawan")->fetchAll();

// Query JOIN
$joinQuery = "
  SELECT sk.shift_karyawan_id, k.nama AS nama_karyawan, sk.tgl_shift, sk.status_shift_karyawan
  FROM shift_karyawan sk
  JOIN karyawan k ON sk.karyawan_id = k.karyawan_id
";

// Form field
$formFields = [
  'karyawan_id' => [
    'label' => 'Nama Karyawan',
    'type' => 'select',
    'required' => true,
    'options' => $karyawanList,
    'option_label' => fn($row) => $row['nama']
  ],
  'tgl_shift' => ['label' => 'Tanggal Shift', 'type' => 'date', 'required' => true],
  'status_shift_karyawan' => [
    'label' => 'Status Shift',
    'type' => 'select',
    'required' => true,
    'options' => [
      ['status' => 'Terjadwal'],
      ['status' => 'Selesai'],
      ['status' => 'Absen'],
    ],
    'option_label' => fn($row) => $row['status']
  ]
];

$primaryKeys = ['shift_karyawan_id'];

include_once('../template/join_template.php');
