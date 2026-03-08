<?php
// === CRUD_JOIN.PHP ===
ob_start();
include('../config/db.php');
session_start();

$table = $_GET['table'] ?? '';
$primaryKeys = $_POST['primary_keys'] ?? [];
$isEdit = isset($_GET['edit']);
$isDelete = isset($_GET['delete']);

// Validasi nama tabel
if (!$table) {
    $_SESSION['message'] = 'Nama tabel tidak valid.';
    $_SESSION['msg_type'] = 'danger';
    header("Location: ../index.php");
    exit;
}

// Pastikan file formFields ada
$pagePath = "../pages/$table.php";
if (!file_exists($pagePath)) {
    $_SESSION['message'] = "File konfigurasi halaman $table tidak ditemukan.";
    $_SESSION['msg_type'] = 'danger';
    header("Location: ../index.php");
    exit;
}
include_once($pagePath);

// Validasi $formFields
if (!isset($formFields)) {
    $_SESSION['message'] = 'Form konfigurasi tidak ditemukan.';
    $_SESSION['msg_type'] = 'danger';
    header("Location: ../index.php");
    exit;
}

// Ambil kolom valid
$validFields = array_keys($formFields);
$data = array_intersect_key($_POST, array_flip($validFields));

// Ubah nilai kosong string jadi NULL (untuk FOREIGN KEY opsional seperti member_id)
foreach ($data as $key => $val) {
    if ($val === '') {
        $data[$key] = null;
    }
}

// DELETE
if ($isDelete) {
    $conditions = [];
    $values = [];
    foreach ($_GET as $k => $v) {
        if (!in_array($k, ['table', 'delete'])) {
            $conditions[] = "$k = ?";
            $values[] = $v;
        }
    }

    $sql = "DELETE FROM $table WHERE " . implode(" AND ", $conditions);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    $_SESSION['message'] = 'Data berhasil dihapus.';
    $_SESSION['msg_type'] = 'success';
    header("Location: ../pages/$table.php");
    exit;
}

// INSERT
if (isset($_POST['save'])) {
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), '?');

    $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(array_values($data));
        $_SESSION['message'] = 'Data berhasil ditambahkan.';
        $_SESSION['msg_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Gagal menambahkan data: ' . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }

    header("Location: ../pages/$table.php");
    exit;
}

// UPDATE
if ($isEdit && isset($_POST['update'])) {
    $setParts = [];
    foreach ($data as $col => $val) {
        $setParts[] = "$col = ?";
    }

    $whereParts = [];
    $whereValues = [];
    foreach ($primaryKeys as $pk) {
        $whereParts[] = "$pk = ?";
        $whereValues[] = $_GET[$pk] ?? '';
    }

    $sql = "UPDATE $table SET " . implode(',', $setParts) . " WHERE " . implode(" AND ", $whereParts);
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(array_merge(array_values($data), $whereValues));
        $_SESSION['message'] = 'Data berhasil diperbarui.';
        $_SESSION['msg_type'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Gagal memperbarui data: ' . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }

    header("Location: ../pages/$table.php");
    exit;
}

// Tidak ada aksi
$_SESSION['message'] = 'Tidak ada aksi yang dilakukan.';
$_SESSION['msg_type'] = 'warning';
header("Location: ../pages/$table.php");
exit;
ob_end_flush();
