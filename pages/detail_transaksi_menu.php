<?php
include_once('../config/db.php');

$table = 'detail_transaksi_menu';
$title = 'Detail Transaksi Menu';
$primaryKeys = ['transaksi_id', 'menu_id'];

$joinQuery = "
  SELECT dtm.transaksi_id, t.tgl_transaksi, dtm.menu_id, m.nama_menu, dtm.jumlah, dtm.catatan
  FROM detail_transaksi_menu dtm
  JOIN transaksi t ON dtm.transaksi_id = t.transaksi_id
  JOIN menu m ON dtm.menu_id = m.menu_id
";

$formFields = [
  'transaksi_id' => [
    'label' => 'Transaksi',
    'type' => 'select',
    'required' => true,
    'options' => $pdo->query("SELECT transaksi_id, tgl_transaksi FROM transaksi")->fetchAll(),
    'option_label' => fn($opt) => $opt['transaksi_id'] . ' - ' . $opt['tgl_transaksi']
  ],
  'menu_id' => [
    'label' => 'Menu',
    'type' => 'select',
    'required' => true,
    'options' => $pdo->query("SELECT menu_id, nama_menu FROM menu")->fetchAll(),
    'option_label' => fn($opt) => $opt['menu_id'] . ' - ' . $opt['nama_menu']
  ],
  'jumlah' => ['label' => 'Jumlah', 'type' => 'number', 'required' => true],
  'catatan' => ['label' => 'Catatan', 'type' => 'textarea', 'required' => false],
];

include_once('../template/join_template.php');
