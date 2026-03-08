<?php
$custom_query = "
  SELECT rj.id_riwayat, k.nama AS nama_karyawan, rj.jabatan, rj.tanggal_mulai, rj.tanggal_selesai
  FROM riwayat_jabatan rj
  JOIN karyawan k ON rj.nip_karyawan = k.nip_karyawan
";
include('../template/table_template.php');
?>
