<?php
include_once('../config/db.php'); // koneksi database
$table = 'member';
$title = 'Data Member';

$currentPelangganId = null;

// Jika sedang edit, ambil pelanggan_id dari member yang sedang diedit
if (isset($_GET['edit']) && isset($_GET['member_id'])) {
    $stmt = $pdo->prepare("SELECT pelanggan_id FROM member WHERE member_id = ?");
    $stmt->execute([$_GET['member_id']]);
    $currentPelangganId = $stmt->fetchColumn();
}

// Ambil daftar pelanggan yang belum menjadi member, atau pelanggan yang sedang dipakai (saat edit)
$pelangganQuery = $pdo->prepare("
    SELECT p.pelanggan_id, p.nama 
    FROM pelanggan p
    LEFT JOIN member m ON p.pelanggan_id = m.pelanggan_id
    WHERE m.pelanggan_id IS NULL
       OR p.pelanggan_id = ?
");
$pelangganQuery->execute([$currentPelangganId]);
$pelangganList = $pelangganQuery->fetchAll();

// Query JOIN untuk menampilkan tabel
$joinQuery = "
    SELECT m.member_id, p.nama AS nama_pelanggan, m.no_telepon, m.email, m.alamat,
           m.tgl_bergabung, m.tgl_kadaluarsa, m.poin, m.status_aktif
    FROM member m
    JOIN pelanggan p ON m.pelanggan_id = p.pelanggan_id
";

// Form field konfigurasi
$formFields = [
    'pelanggan_id' => [
        'label' => 'Nama Pelanggan',
        'type' => 'select',
        'required' => true,
        'options' => $pelangganList,
        'option_label' => fn($row) => $row['nama']
    ],
    'no_telepon' => ['label' => 'No Telepon', 'type' => 'text', 'required' => true],
    'email' => ['label' => 'Email', 'type' => 'email', 'required' => true],
    'alamat' => ['label' => 'Alamat', 'type' => 'textarea', 'required' => true],
    'tgl_bergabung' => ['label' => 'Tanggal Bergabung', 'type' => 'date', 'required' => true],
    'tgl_kadaluarsa' => ['label' => 'Tanggal Kadaluarsa', 'type' => 'date', 'required' => true],
    'poin' => ['label' => 'Poin', 'type' => 'number', 'required' => false],
    'status_aktif' => [
        'label' => 'Status',
        'type' => 'select',
        'required' => true,
        'options' => [
            ['status' => 'Aktif'],
            ['status' => 'Non-Aktif']
        ],
        'option_label' => fn($row) => $row['status']
    ],
];

$primaryKeys = ['member_id'];

include_once('../template/join_template.php');
