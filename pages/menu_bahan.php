<?php
include_once('../config/db.php');
$table = 'menu_bahan';
$title = 'Komposisi Menu';

// Ambil data untuk dropdown
$menuList = $pdo->query("SELECT menu_id, nama_menu FROM menu")->fetchAll();
$bahanList = $pdo->query("SELECT bahan_id, nama_bahan FROM bahan")->fetchAll();

// Query hasil JOIN
$joinQuery = "
  SELECT 
    mb.menu_id,
    m.nama_menu,
    mb.bahan_id,
    b.nama_bahan,
    mb.jumlah
  FROM menu_bahan mb
  JOIN menu m ON mb.menu_id = m.menu_id
  JOIN bahan b ON mb.bahan_id = b.bahan_id
";

// Konfigurasi form input
$formFields = [
  'menu_id' => [
    'label' => 'Menu',
    'type' => 'select',
    'required' => true,
    'options' => $menuList,
    'option_label' => fn($row) => $row['nama_menu']
  ],
  'bahan_id' => [
    'label' => 'Bahan',
    'type' => 'select',
    'required' => true,
    'options' => $bahanList,
    'option_label' => fn($row) => $row['nama_bahan']
  ],
  'jumlah' => [
    'label' => 'Jumlah (per porsi)',
    'type' => 'number',
    'required' => true
  ]
];

// Primary key gabungan
$primaryKeys = ['menu_id', 'bahan_id'];

include_once('../template/join_template.php');
