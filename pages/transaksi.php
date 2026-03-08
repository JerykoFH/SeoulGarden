<?php
include_once('../config/db.php');

$table = 'transaksi';
$title = 'Data Transaksi';

// Ambil opsi dropdown dari tabel terkait
$pelangganList = $pdo->query("SELECT pelanggan_id, nama FROM pelanggan")->fetchAll();
$memberList = $pdo->query("SELECT member_id, email FROM member")->fetchAll();
$karyawanList = $pdo->query("SELECT karyawan_id, nama FROM karyawan")->fetchAll();

// Query JOIN untuk menampilkan nama terkait
$joinQuery = "
  SELECT t.transaksi_id, p.nama AS nama_pelanggan, m.email AS email_member, k.nama AS nama_karyawan,
         t.tgl_transaksi, t.jam_mulai, t.jam_selesai, t.jumlah_orang, t.total_harga,
         t.diskon_member, t.total_bayar, t.metode_pembayaran, t.poin_didapat, t.poin_dipakai, t.status_transaksi
  FROM transaksi t
  JOIN pelanggan p ON t.pelanggan_id = p.pelanggan_id
  LEFT JOIN member m ON t.member_id = m.member_id
  JOIN karyawan k ON t.karyawan_id = k.karyawan_id
";

// Kolom untuk form tambah/edit
$formFields = [
  'pelanggan_id' => [
    'label' => 'Pelanggan',
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
    'option_label' => fn($row) => $row['email'],
    'allow_empty' => true
  ],
  'karyawan_id' => [
    'label' => 'Karyawan',
    'type' => 'select',
    'required' => true,
    'options' => $karyawanList,
    'option_label' => fn($row) => $row['nama']
  ],
  'tgl_transaksi' => ['label' => 'Tanggal', 'type' => 'date', 'required' => true],
  'jam_mulai' => ['label' => 'Jam Mulai', 'type' => 'time', 'required' => true],
  'jam_selesai' => ['label' => 'Jam Selesai', 'type' => 'time', 'required' => false],
  'jumlah_orang' => ['label' => 'Jumlah Orang', 'type' => 'number', 'required' => true],
  'total_harga' => ['label' => 'Total Harga', 'type' => 'number', 'required' => true],
  'diskon_member' => ['label' => 'Diskon Member (%)', 'type' => 'number', 'required' => false],
  'total_bayar' => ['label' => 'Total Bayar', 'type' => 'number', 'required' => true],
  'metode_pembayaran' => [
    'label' => 'Metode Pembayaran',
    'type' => 'select',
    'required' => true,
    'options' => [
      ['metode' => 'Tunai'],
      ['metode' => 'Kartu Debit'],
      ['metode' => 'Kartu Kredit'],
      ['metode' => 'E-Wallet'],
      ['metode' => 'Transfer Bank'],
    ],
    'option_label' => fn($row) => $row['metode']
  ],
  'poin_didapat' => ['label' => 'Poin Didapat', 'type' => 'number', 'required' => false],
  'poin_dipakai' => ['label' => 'Poin Dipakai', 'type' => 'number', 'required' => false],
  'status_transaksi' => [
    'label' => 'Status',
    'type' => 'select',
    'required' => true,
    'options' => [
      ['status' => 'Proses'],
      ['status' => 'Lunas'],
      ['status' => 'Batal']
    ],
    'option_label' => fn($row) => $row['status']
  ],
];

// Primary key
$primaryKeys = ['transaksi_id'];

// Tampilkan dengan join_template
include_once('../template/join_template.php');
