<?php
include_once('../config/db.php');
$table = 'reservasi';
$title = 'Data Reservasi';

// Ambil data untuk select dropdown
$pelangganList = $pdo->query("SELECT pelanggan_id, nama FROM pelanggan")->fetchAll();
$memberList = $pdo->query("
  SELECT m.member_id, p.nama 
  FROM member m 
  JOIN pelanggan p ON m.pelanggan_id = p.pelanggan_id
")->fetchAll();

// Query untuk ditampilkan di tabel
$joinQuery = "
  SELECT 
    r.reservasi_id,
    p.nama AS nama_pelanggan,
    COALESCE(mp.nama, '-') AS nama_member,
    r.tgl_reservasi,
    r.jam_reservasi,
    r.jumlah_orang,
    r.catatan_khusus,
    r.status_reservasi,
    r.tgl_dibuat,
    r.tgl_diupdate
  FROM reservasi r
  JOIN pelanggan p ON r.pelanggan_id = p.pelanggan_id
  LEFT JOIN member m ON r.member_id = m.member_id
  LEFT JOIN pelanggan mp ON m.pelanggan_id = mp.pelanggan_id
";

// Konfigurasi form input
$formFields = [
  'pelanggan_id' => [
    'label' => 'Nama Pelanggan',
    'type' => 'select',
    'required' => true,
    'options' => $pelangganList,
    'option_label' => fn($row) => $row['nama']
  ],
  'member_id' => [
    'label' => 'Member (Opsional)',
    'type' => 'select',
    'required' => false,
    'options' => $memberList,
    'option_label' => fn($row) => $row['nama']
  ],
  'tgl_reservasi' => ['label' => 'Tanggal Reservasi', 'type' => 'date', 'required' => true],
  'jam_reservasi' => ['label' => 'Jam Reservasi', 'type' => 'time', 'required' => true],
  'jumlah_orang' => ['label' => 'Jumlah Orang', 'type' => 'number', 'required' => true],
  'catatan_khusus' => ['label' => 'Catatan Khusus', 'type' => 'textarea', 'required' => false],
  'status_reservasi' => [
    'label' => 'Status Reservasi',
    'type' => 'select',
    'required' => true,
    'options' => [
      ['status' => 'Dikonfirmasi'],
      ['status' => 'Selesai'],
      ['status' => 'Batal'],
      ['status' => 'No Show']
    ],
    'option_label' => fn($row) => $row['status']
  ]
];

$primaryKeys = ['reservasi_id'];

include_once('../template/join_template.php');
