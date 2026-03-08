<?php
$custom_query = "
  SELECT 
    dmt.transaksi_id,
    t.tgl_transaksi,
    dmt.nomor_meja,
    mj.lokasi,
    mj.kapasitas
  FROM detail_meja_transaksi dmt
  JOIN transaksi t ON dmt.transaksi_id = t.transaksi_id
  JOIN meja mj ON dmt.nomor_meja = mj.nomor_meja
";
include('../template/table_template.php');
