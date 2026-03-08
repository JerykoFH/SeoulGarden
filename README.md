# Seoul Garden CRUD Application

Aplikasi **Seoul Garden Database Management** adalah aplikasi web sederhana berbasis **PHP dan MySQL** yang digunakan untuk mengelola data tabel menggunakan operasi **CRUD (Create, Read, Update, Delete)**.  
Aplikasi ini menyediakan tampilan tabel yang memungkinkan pengguna untuk melihat, menambah, mengedit, dan menghapus data melalui antarmuka web.

---

## Contributors

Project ini dikerjakan oleh:

- Yosua Juswandiputra – 2472027  
- Jeryko Farelin Heliandra – 2472032  

---

## Tech Stack

Teknologi yang digunakan dalam project ini:

- PHP  
- MySQL  
- HTML  
- CSS  
- Bootstrap  
- XAMPP (Local Server)

---

## Fitur Utama

Fitur yang tersedia dalam aplikasi ini antara lain:

- Menampilkan data dari database dalam bentuk tabel
- Menambahkan data baru
- Mengedit data yang sudah ada
- Menghapus data dari database
- Notifikasi hasil operasi CRUD
- Tampilan antarmuka berbasis Bootstrap
- Template sistem untuk semua halaman tabel

---

## Cara Menjalankan Program

Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal.

### 1. Jalankan Server

Aktifkan **Apache** dan **MySQL** pada XAMPP.

---

### 2. Setup Database

1. Buka **phpMyAdmin**
2. Buat database baru dengan nama 'seoulGarden'
3. Buat tabel yang diperlukan
4. Masukkan data awal ke dalam tabel

---

### 3. Extract Project

Jika project masih dalam bentuk **ZIP**:

1. Extract folder **seoul-garden**
2. Pindahkan folder tersebut ke direktori:

```

xampp/htdocs/

```

---

### 4. Jalankan Aplikasi

Buka browser lalu akses alamat berikut:

```

[http://localhost/seoul-garden](http://localhost/seoul-garden)

```

---

## Struktur Project

Berikut adalah penjelasan file utama dalam project.

### config/db.php

Berfungsi untuk menghubungkan aplikasi dengan database.

Fungsi:
- Membuat koneksi ke database MySQL
- Menyimpan konfigurasi database

---

### template/table_template.php

Template dasar yang digunakan oleh semua halaman tabel.

Fungsi:
- Mengambil nama tabel dari nama file
- Menampilkan data dari database
- Menangani operasi DELETE
- Menyediakan modal untuk menambah dan mengedit data
- Menampilkan notifikasi hasil operasi CRUD

---

### pages/crud.php

File ini menangani proses operasi database.

Fungsi:
- Create (menambah data)
- Update (mengubah data)
- Delete (menghapus data)

---

### public/style.css

File stylesheet untuk tampilan antarmuka aplikasi.

Fungsi:
- Mengatur tampilan keseluruhan aplikasi

---

### index.php

Halaman utama atau dashboard aplikasi.

Fungsi:
- Menampilkan navigasi ke semua tabel
- Mengelompokkan tabel berdasarkan kategori

---

### Folder pages/

Folder ini berisi halaman untuk setiap tabel database.

Contoh:
- bahan.php
- member.php

Catatan:
- Nama file harus sama dengan nama tabel di database
- Setiap file digunakan untuk menampilkan data dari tabel terkait

---

## Catatan

Aplikasi ini dibuat sebagai sistem sederhana untuk mengelola data database menggunakan antarmuka web dengan operasi CRUD.


