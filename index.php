<?php include('config/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Seoul Garden</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="public/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">
      <i class="bi bi-flower2 me-2"></i>Seoul Garden DB
    </a>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">
      <i class="bi bi-database me-2"></i>Database Tables
    </h2>
  </div>
  
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100 border-primary">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-people-fill me-2"></i>Customer Management
        </div>
        <div class="card-body">
          <a href="pages/pelanggan.php" class="btn btn-outline-primary w-100 mb-2">Pelanggan</a>
          <a href="pages/member.php" class="btn btn-outline-primary w-100 mb-2">Member</a>
          <a href="pages/reservasi.php" class="btn btn-outline-primary w-100 mb-2">Reservasi</a>
          <a href="pages/meja.php" class="btn btn-outline-primary w-100">Meja</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100 border-success">
        <div class="card-header bg-success text-white">
          <i class="bi bi-cup-hot-fill me-2"></i>Menu & Inventory
        </div>
        <div class="card-body">
          <a href="pages/menu.php" class="btn btn-outline-success w-100 mb-2">Menu</a>
          <a href="pages/bahan.php" class="btn btn-outline-success w-100 mb-2">Bahan</a>
          <a href="pages/menu_bahan.php" class="btn btn-outline-success w-100">Menu Bahan</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100 border-warning">
        <div class="card-header bg-warning text-dark">
          <i class="bi bi-cash-coin me-2"></i>Transactions
        </div>
        <div class="card-body">
          <a href="pages/transaksi.php" class="btn btn-outline-warning w-100 mb-2">Transaksi</a>
          <a href="pages/detail_transaksi_menu.php" class="btn btn-outline-warning w-100 mb-2">Detail Transaksi Menu</a>
          <a href="pages/detail_meja_transaksi.php" class="btn btn-outline-warning w-100">Detail Meja Transaksi</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100 border-info">
        <div class="card-header bg-info text-white">
          <i class="bi bi-people-fill me-2"></i>Staff Management
        </div>
        <div class="card-body">
          <a href="pages/karyawan.php" class="btn btn-outline-info w-100 mb-2">Karyawan</a>
          <a href="pages/shift_karyawan.php" class="btn btn-outline-info w-100">Shift Karyawan</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100 border-danger">
        <div class="card-header bg-danger text-white">
          <i class="bi bi-truck me-2"></i>Suppliers & Purchasing
        </div>
        <div class="card-body">
          <a href="pages/supplier.php" class="btn btn-outline-danger w-100 mb-2">Supplier</a>
          <a href="pages/pembelian_bahan.php" class="btn btn-outline-danger w-100 mb-2">Pembelian Bahan</a>
          <a href="pages/detail_pembelian_bahan.php" class="btn btn-outline-danger w-100">Detail Pembelian Bahan</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100 border-secondary">
        <div class="card-header bg-secondary text-white">
          <i class="bi bi-calendar-check me-2"></i>Reservations
        </div>
        <div class="card-body">
          <a href="pages/detail_meja_reservasi.php" class="btn btn-outline-secondary w-100">Detail Meja Reservasi</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>