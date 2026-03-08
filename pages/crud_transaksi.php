<?php
include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $transaksi_id = $_POST['transaksi_id'];
    $total_bayar = $_POST['total_bayar'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    $stmt = $pdo->prepare("UPDATE transaksi SET total_bayar = ?, metode_pembayaran = ? WHERE transaksi_id = ?");
    $stmt->execute([$total_bayar, $metode_pembayaran, $transaksi_id]);

    $_SESSION['message'] = "Transaksi berhasil diperbarui!";
    $_SESSION['msg_type'] = "success";
    header("Location: transaksi.php");
    exit;
}

if (isset($_GET['delete'])) {
    $transaksi_id = $_GET['delete'];

    // Hapus dari detail_transaksi_menu dulu (jika ada relasi)
    $pdo->prepare("DELETE FROM detail_transaksi_menu WHERE transaksi_id = ?")->execute([$transaksi_id]);

    // Hapus dari transaksi
    $pdo->prepare("DELETE FROM transaksi WHERE transaksi_id = ?")->execute([$transaksi_id]);

    $_SESSION['message'] = "Transaksi berhasil dihapus!";
    $_SESSION['msg_type'] = "danger";
    header("Location: transaksi.php");
    exit;
}
?>
