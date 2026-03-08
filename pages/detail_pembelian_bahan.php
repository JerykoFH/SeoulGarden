<?php
include_once('../config/db.php');

$table = 'detail_pembelian_bahan';
$title = 'Detail Pembelian Bahan';

// Perbaikan nama kolom: harga_satuan (bukan harga_beli), dan hitung subtotal di query
$joinQuery = "
SELECT 
  dpb.pembelian_id,
  pb.tgl_pembelian,
  dpb.bahan_id,
  b.nama_bahan,
  dpb.jumlah,
  dpb.harga_satuan,
  (dpb.jumlah * dpb.harga_satuan) AS subtotal
FROM 
  detail_pembelian_bahan dpb
JOIN 
  pembelian_bahan pb ON dpb.pembelian_id = pb.pembelian_id
JOIN 
  bahan b ON dpb.bahan_id = b.bahan_id
";

// Dropdown data
$bahanList = $pdo->query("SELECT bahan_id, nama_bahan FROM bahan")->fetchAll();
$pembelianList = $pdo->query("SELECT pembelian_id, tgl_pembelian FROM pembelian_bahan")->fetchAll();

// Form field config
$formFields = [
  'pembelian_id' => [
    'label' => 'Tanggal Pembelian',
    'type' => 'select',
    'required' => true,
    'options' => $pembelianList,
    'option_label' => fn($row) => $row['tgl_pembelian']
  ],
  'bahan_id' => [
    'label' => 'Nama Bahan',
    'type' => 'select',
    'required' => true,
    'options' => $bahanList,
    'option_label' => fn($row) => $row['nama_bahan']
  ],
  'jumlah' => [
    'label' => 'Jumlah',
    'type' => 'number',
    'required' => true
  ],
  'harga_satuan' => [
    'label' => 'Harga Satuan',
    'type' => 'number',
    'required' => true
  ],
];

// Primary key gabungan
$primaryKeys = ['pembelian_id', 'bahan_id'];

include_once('../template/join_template.php');
