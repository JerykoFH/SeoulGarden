<?php
include_once('../config/db.php');
$table = 'pembelian_bahan';
$title = 'Data Pembelian Bahan';

// Ambil data supplier dan karyawan untuk pilihan select
$suppliers = $pdo->query("SELECT supplier_id, nama_supplier FROM supplier")->fetchAll();
$karyawans = $pdo->query("SELECT karyawan_id, nama FROM karyawan")->fetchAll();

// Query JOIN untuk menampilkan data pembelian bahan
$joinQuery = "
  SELECT pb.pembelian_id, s.nama_supplier, k.nama AS nama_karyawan,
         pb.tgl_pembelian, pb.tgl_dibutuhkan, pb.total_harga,
         pb.status_pembelian_bahan, pb.catatan
  FROM pembelian_bahan pb
  JOIN supplier s ON pb.supplier_id = s.supplier_id
  JOIN karyawan k ON pb.karyawan_id = k.karyawan_id
";

// Form fields untuk tambah/edit pembelian bahan
$formFields = [
  'supplier_id' => [
    'label' => 'Nama Supplier',
    'type' => 'select',
    'required' => true,
    'options' => $suppliers,
    'option_label' => fn($row) => $row['nama_supplier']
  ],
  'karyawan_id' => [
    'label' => 'Nama Karyawan',
    'type' => 'select',
    'required' => true,
    'options' => $karyawans,
    'option_label' => fn($row) => $row['nama']
  ],
  'tgl_pembelian' => ['label' => 'Tanggal Pembelian', 'type' => 'date', 'required' => true],
  'tgl_dibutuhkan' => ['label' => 'Tanggal Dibutuhkan', 'type' => 'date', 'required' => true],
  'total_harga' => ['label' => 'Total Harga', 'type' => 'number', 'required' => false],
  'status_pembelian_bahan' => [
    'label' => 'Status Pembelian',
    'type' => 'select',
    'required' => true,
    'options' => [
      ['status' => 'Draft'],
      ['status' => 'Diproses'],
      ['status' => 'Dikirim'],
      ['status' => 'Diterima'],
      ['status' => 'Ditolak']
    ],
    'option_label' => fn($row) => $row['status']
  ],
  'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'required' => false],
];

$primaryKeys = ['pembelian_id'];

include_once('../template/join_template.php');
