

-- ========================
-- TABLES
-- ========================

-- 1. Pelanggan
CREATE TABLE pelanggan (
  pelanggan_id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL
);

-- 2. Member
CREATE TABLE member (
  member_id INT AUTO_INCREMENT PRIMARY KEY,
  pelanggan_id INT UNIQUE,
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  alamat TEXT,
  tgl_bergabung DATE NOT NULL,
  tgl_kadaluarsa DATE NOT NULL,
  poin INT DEFAULT 0,
  status_aktif ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(pelanggan_id)
);

-- 3. Meja
CREATE TABLE meja (
  nomor_meja INT AUTO_INCREMENT PRIMARY KEY,
  kapasitas INT NOT NULL,
  lokasi ENUM('Indoor', 'Outdoor', 'Private Room') NOT NULL,
  status_meja ENUM('Tersedia', 'Dipesan', 'Terpakai', 'Perbaikan') DEFAULT 'Tersedia'
);

-- 4. Karyawan
CREATE TABLE karyawan (
  karyawan_id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  posisi ENUM('Koki', 'Pelayan', 'Kasir', 'Manajer', 'Staf Kebersihan') NOT NULL,
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  alamat TEXT,
  tgl_mulai_kerja DATE NOT NULL,
  gaji_pokok DECIMAL(10,2),
  status_karyawan ENUM('Tetap', 'Kontrak', 'Paruh Waktu')
);

-- 5. Menu
CREATE TABLE menu (
  menu_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu VARCHAR(100) NOT NULL,
  kategori ENUM('Makanan Utama', 'Side Dish', 'Minuman', 'Dessert', 'Set Menu') NOT NULL,
  harga DECIMAL(10,2) NOT NULL,
  deskripsi TEXT,
  tingkat_kepedasan INT CHECK (tingkat_kepedasan BETWEEN 0 AND 5),
  status_menu ENUM('Tersedia', 'Habis', 'Tidak Tersedia') DEFAULT 'Tersedia',
  is_rekomendasi BOOLEAN DEFAULT FALSE
);

-- 6. Supplier
CREATE TABLE supplier (
  supplier_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_supplier VARCHAR(100) NOT NULL,
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  alamat TEXT,
  kontak_person VARCHAR(100),
  status_supplier ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

-- 7. Reservasi
CREATE TABLE reservasi (
  reservasi_id INT AUTO_INCREMENT PRIMARY KEY,
  pelanggan_id INT,
  member_id INT NULL,
  tgl_reservasi DATE NOT NULL,
  jam_reservasi TIME NOT NULL,
  jumlah_orang INT NOT NULL,
  catatan_khusus TEXT,
  status_reservasi ENUM('Dikonfirmasi', 'Selesai', 'Batal', 'No Show') DEFAULT 'Dikonfirmasi',
  tgl_dibuat DATETIME DEFAULT CURRENT_TIMESTAMP,
  tgl_diupdate DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(pelanggan_id),
  FOREIGN KEY (member_id) REFERENCES member(member_id)
);

-- 8. Shift Karyawan
CREATE TABLE shift_karyawan (
  shift_karyawan_id INT AUTO_INCREMENT PRIMARY KEY,
  karyawan_id INT,
  tgl_shift DATE NOT NULL,
  status_shift_karyawan ENUM('Terjadwal', 'Selesai', 'Absen') DEFAULT 'Terjadwal',
  FOREIGN KEY (karyawan_id) REFERENCES karyawan(karyawan_id)
);

-- 9. Pembelian Bahan
CREATE TABLE pembelian_bahan (
  pembelian_id INT AUTO_INCREMENT PRIMARY KEY,
  supplier_id INT,
  karyawan_id INT,
  tgl_pembelian DATE NOT NULL,
  tgl_dibutuhkan DATE NOT NULL,
  total_harga DECIMAL(10,2),
  status_pembelian_bahan ENUM('Draft', 'Diproses', 'Dikirim', 'Diterima', 'Ditolak') DEFAULT 'Draft',
  catatan TEXT,
  FOREIGN KEY (supplier_id) REFERENCES supplier(supplier_id),
  FOREIGN KEY (karyawan_id) REFERENCES karyawan(karyawan_id)
);

-- 10. Transaksi
CREATE TABLE transaksi (
  transaksi_id INT AUTO_INCREMENT PRIMARY KEY,
  pelanggan_id INT,
  member_id INT NULL,
  karyawan_id INT,
  tgl_transaksi DATE NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME,
  jumlah_orang INT NOT NULL,
  total_harga DECIMAL(10,2) NOT NULL,
  diskon_member DECIMAL(5,2) DEFAULT 0,
  total_bayar DECIMAL(10,2),
  metode_pembayaran ENUM('Tunai', 'Kartu Debit', 'Kartu Kredit', 'E-Wallet', 'Transfer Bank'),
  poin_didapat INT DEFAULT 0,
  poin_dipakai INT DEFAULT 0,
  status_transaksi ENUM('Proses', 'Lunas', 'Batal') DEFAULT 'Proses',
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(pelanggan_id),
  FOREIGN KEY (member_id) REFERENCES member(member_id),
  FOREIGN KEY (karyawan_id) REFERENCES karyawan(karyawan_id)
);

-- 11. Bahan
CREATE TABLE bahan (
  bahan_id INT AUTO_INCREMENT PRIMARY KEY,
  nama_bahan VARCHAR(100) NOT NULL,
  satuan VARCHAR(50) NOT NULL,
  stok DECIMAL(10,2) DEFAULT 0,
  status_bahan ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif'
);

-- ========================
-- RELASI N:M
-- ========================

-- Meja - Reservasi
CREATE TABLE detail_meja_reservasi (
  reservasi_id INT,
  nomor_meja INT,
  PRIMARY KEY (reservasi_id, nomor_meja),
  FOREIGN KEY (reservasi_id) REFERENCES reservasi(reservasi_id),
  FOREIGN KEY (nomor_meja) REFERENCES meja(nomor_meja)
);

-- Meja - Transaksi
CREATE TABLE detail_meja_transaksi (
  transaksi_id INT,
  nomor_meja INT,
  PRIMARY KEY (transaksi_id, nomor_meja),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(transaksi_id),
  FOREIGN KEY (nomor_meja) REFERENCES meja(nomor_meja)
);

-- Menu - Transaksi
CREATE TABLE detail_transaksi_menu (
  transaksi_id INT,
  menu_id INT,
  jumlah INT DEFAULT 1,
  catatan TEXT,
  PRIMARY KEY (transaksi_id, menu_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(transaksi_id),
  FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- Menu - Bahan (N:M)
CREATE TABLE menu_bahan (
  menu_id INT,
  bahan_id INT,
  jumlah DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (menu_id, bahan_id),
  FOREIGN KEY (menu_id) REFERENCES menu(menu_id),
  FOREIGN KEY (bahan_id) REFERENCES bahan(bahan_id)
);

-- Pembelian Bahan - Bahan (N:M)
-- Relasi N:M antara bahan dan pembelian_bahan
CREATE TABLE detail_pembelian_bahan (
  pembelian_id INT,
  bahan_id INT,
  jumlah DECIMAL(10,2) NOT NULL,
  harga_satuan DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (pembelian_id, bahan_id),
  FOREIGN KEY (pembelian_id) REFERENCES pembelian_bahan(pembelian_id),
  FOREIGN KEY (bahan_id) REFERENCES bahan(bahan_id)
);


INSERT INTO pelanggan (nama) VALUES
('Ahmad Fauzi'),
('Siti Aisyah'),
('Rizky Maulana'),
('Dewi Kartika'),
('Budi Prasetyo'),
('Indah Lestari'),
('Agus Santoso'),
('Rina Amelia'),
('Fajar Nugroho'),
('Wulan Ayu');


INSERT INTO member (pelanggan_id, no_telepon, email, alamat, tgl_bergabung, tgl_kadaluarsa, poin, status_aktif) VALUES
(1, '081234560001', 'ahmad.fauzi@seoulgarden.co.id', 'Jl. Merdeka No.1, Jakarta', '2024-01-10', '2025-01-10', 120, 'Aktif'),
(2, '081234560002', 'siti.aisyah@seoulgarden.co.id', 'Jl. Melati No.2, Bandung', '2024-02-15', '2025-02-15', 90, 'Aktif'),
(3, '081234560003', 'rizky.maulana@seoulgarden.co.id', 'Jl. Kenanga No.3, Surabaya', '2024-03-05', '2025-03-05', 70, 'Aktif'),
(4, '081234560004', 'dewi.kartika@seoulgarden.co.id', 'Jl. Anggrek No.4, Semarang', '2024-04-01', '2025-04-01', 110, 'Aktif'),
(5, '081234560005', 'budi.prasetyo@seoulgarden.co.id', 'Jl. Mawar No.5, Medan', '2024-05-20', '2025-05-20', 60, 'Non-Aktif');


INSERT INTO meja (kapasitas, lokasi, status_meja) VALUES
(2, 'Indoor', 'Tersedia'),
(4, 'Outdoor', 'Terpakai'),
(6, 'Indoor', 'Dipesan'),
(8, 'Private Room', 'Tersedia'),
(2, 'Outdoor', 'Perbaikan');


INSERT INTO karyawan (nama, posisi, no_telepon, email, alamat, tgl_mulai_kerja, gaji_pokok, status_karyawan) VALUES
('Agus Santoso', 'Koki', '0811122233', 'agus@resto.com', 'Jl. Kenanga No.5', '2023-01-10', 5000000.00, 'Tetap'),
('Budi Hartono', 'Pelayan', '0811133344', 'budi@resto.com', 'Jl. Flamboyan No.6', '2023-03-15', 3000000.00, 'Kontrak'),
('Citra Lestari', 'Kasir', '0811144455', 'citra@resto.com', 'Jl. Cempaka No.7', '2022-11-20', 4000000.00, 'Tetap'),
('Dewi Anggraini', 'Manajer', '0811155566', 'dewi@resto.com', 'Jl. Dahlia No.8', '2022-05-01', 7000000.00, 'Tetap'),
('Eko Prasetyo', 'Staf Kebersihan', '0811166677', 'eko@resto.com', 'Jl. Teratai No.9', '2024-01-05', 2500000.00, 'Paruh Waktu');


INSERT INTO menu (nama_menu, kategori, harga, deskripsi, tingkat_kepedasan, status_menu, is_rekomendasi) VALUES
('Kimchi Jjigae', 'Makanan Utama', 48000.00, 'Sup pedas khas Korea dengan kimchi dan tahu', 4, 'Tersedia', TRUE),
('Bibimbap', 'Makanan Utama', 55000.00, 'Nasi campur Korea dengan sayur, daging, dan telur', 2, 'Tersedia', TRUE),
('Tteokbokki', 'Side Dish', 42000.00, 'Kue beras pedas dengan saus gochujang', 5, 'Tersedia', TRUE),
('Japchae', 'Makanan Utama', 50000.00, 'Soun goreng khas Korea dengan sayur dan daging', 1, 'Tersedia', FALSE),
('Kimbap', 'Side Dish', 35000.00, 'Nasi gulung Korea isi sayur dan daging', 0, 'Tersedia', FALSE),
('Samgyeopsal Set', 'Set Menu', 85000.00, 'Set BBQ daging perut babi lengkap dengan pelengkap', 2, 'Tersedia', TRUE),
('Sundubu Jjigae', 'Makanan Utama', 47000.00, 'Sup tahu pedas Korea dengan seafood', 4, 'Tersedia', FALSE),
('Yangnyeom Chicken', 'Makanan Utama', 60000.00, 'Ayam goreng tepung saus manis pedas khas Korea', 3, 'Tersedia', TRUE),
('Bungeoppang', 'Dessert', 20000.00, 'Kue ikan isi kacang merah khas Korea', 0, 'Tersedia', FALSE),
('Citron Tea', 'Minuman', 18000.00, 'Teh manis dari jeruk yuzu Korea', 0, 'Tersedia', FALSE);




INSERT INTO supplier (nama_supplier, no_telepon, email, alamat, kontak_person, status_supplier) VALUES
('PT Sumber Pangan', '0211234567', 'sumber@pangan.co.id', 'Jl. Raya Bogor KM 10', 'Budi Santoso', 'Aktif'),
('CV Berkah Jaya', '0212345678', 'berkah@jaya.co.id', 'Jl. Margonda Raya', 'Siti Aminah', 'Aktif'),
('PT Segar Abadi', '0213456789', 'segar@abadi.co.id', 'Jl. Kalimalang', 'Andi Wijaya', 'Non-Aktif'),
('CV Freshindo', '0214567890', 'fresh@indo.co.id', 'Jl. Cibubur Indah', 'Lina Marlina', 'Aktif'),
('PT Dapur Nusantara', '0215678901', 'dapur@nusantara.co.id', 'Jl. Lenteng Agung', 'Rudi Hartono', 'Aktif');


INSERT INTO reservasi (pelanggan_id, member_id, tgl_reservasi, jam_reservasi, jumlah_orang, catatan_khusus, status_reservasi) VALUES
(1, 1, '2025-05-25', '18:00:00', 4, 'Ulang tahun', 'Dikonfirmasi'),
(2, 2, '2025-05-26', '19:00:00', 2, '', 'Selesai'),
(3, 3, '2025-05-27', '17:30:00', 3, 'Kursi dekat jendela', 'Dikonfirmasi'),
(4, NULL, '2025-05-28', '20:00:00', 5, '', 'Batal'),
(5, NULL, '2025-05-29', '18:30:00', 6, 'Bawa bayi', 'No Show');


INSERT INTO shift_karyawan (karyawan_id, tgl_shift, status_shift_karyawan) VALUES
(1, '2025-05-20', 'Selesai'),
(2, '2025-05-21', 'Terjadwal'),
(3, '2025-05-22', 'Absen'),
(4, '2025-05-22', 'Selesai'),
(5, '2025-05-22', 'Terjadwal');


INSERT INTO pembelian_bahan (supplier_id, karyawan_id, tgl_pembelian, tgl_dibutuhkan, total_harga, status_pembelian_bahan, catatan) VALUES
(1, 1, '2025-05-20', '2025-05-25', 150000.00, 'Dikirim', 'Urgent'),
(2, 2, '2025-05-21', '2025-05-26', 200000.00, 'Diproses', ''),
(3, 3, '2025-05-22', '2025-05-27', 175000.00, 'Ditolak', 'Stok kosong'),
(4, 4, '2025-05-23', '2025-05-28', 220000.00, 'Diterima', ''),
(5, 5, '2025-05-24', '2025-05-29', 130000.00, 'Draft', '');


INSERT INTO transaksi (pelanggan_id, member_id, karyawan_id, tgl_transaksi, jam_mulai, jam_selesai, jumlah_orang, total_harga, diskon_member, total_bayar, metode_pembayaran, poin_didapat, poin_dipakai, status_transaksi) VALUES
(1, 1, 3, '2025-05-20', '18:00:00', '19:00:00', 4, 100000.00, 10.00, 90000.00, 'Kartu Debit', 10, 0, 'Lunas'),
(2, 2, 3, '2025-05-21', '19:00:00', '20:00:00', 2, 60000.00, 5.00, 57000.00, 'Tunai', 5, 0, 'Lunas'),
(3, NULL, 2, '2025-05-22', '18:30:00', NULL, 3, 75000.00, 0.00, 75000.00, 'E-Wallet', 7, 0, 'Proses'),
(4, NULL, 4, '2025-05-22', '20:00:00', '21:00:00', 5, 125000.00, 0.00, 125000.00, 'Transfer Bank', 0, 0, 'Lunas'),
(5, 3, 1, '2025-05-23', '17:00:00', '18:00:00', 2, 50000.00, 5.00, 47500.00, 'Kartu Kredit', 5, 0, 'Lunas');


INSERT INTO bahan (nama_bahan, satuan, stok, status_bahan) VALUES
('Kimchi', 'gram', 5000.00, 'Aktif'),
('Tteok (Rice Cake)', 'gram', 3000.00, 'Aktif'),
('Gochujang (Sambal Korea)', 'gram', 2500.00, 'Aktif'),
('Daging Sapi Iris', 'gram', 4000.00, 'Aktif'),
('Rumput Laut Kering', 'lembar', 200.00, 'Aktif'),
('Tahu Sutra', 'buah', 300.00, 'Aktif'),
('Soun Korea (Dangmyeon)', 'gram', 3500.00, 'Aktif'),
('Ayam Fillet', 'gram', 4500.00, 'Aktif'),
('Telur Ayam', 'butir', 500.00, 'Aktif'),
('Jeruk Yuzu (Citron)', 'ml', 2000.00, 'Aktif');




INSERT INTO detail_meja_reservasi (reservasi_id, nomor_meja) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);


INSERT INTO detail_meja_transaksi (transaksi_id, nomor_meja) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);


INSERT INTO detail_transaksi_menu (transaksi_id, menu_id, jumlah, catatan) VALUES
(1, 1, 2, 'Tanpa sambal'),
(1, 3, 2, ''),
(2, 2, 1, 'Pedas maksimal'),
(3, 4, 1, 'Tambahan susu'),
(4, 5, 2, '');


INSERT INTO menu_bahan (menu_id, bahan_id, jumlah) VALUES
(1, 1, 0.15), -- Nasi
(1, 2, 0.1),  -- Ayam
(2, 2, 0.2),
(3, 4, 1),
(4, 5, 50);


INSERT INTO detail_pembelian_bahan (pembelian_id, bahan_id, jumlah, harga_satuan) VALUES
(1, 1, 20.00, 10000.00),
(1, 2, 10.00, 20000.00),
(2, 3, 15.00, 8000.00),
(3, 4, 5.00, 5000.00),
(4, 5, 1000.00, 100.00);
