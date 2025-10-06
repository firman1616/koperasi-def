<?php
class M_dashboard extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function trans_month()
  {
    return $this->db->query("SELECT
      SUM(grand_total) as total
    FROM
      tbl_transaksi
    WHERE
      MONTH(tgl_transaksi) = MONTH(CURRENT_DATE)
      AND YEAR(tgl_transaksi) = YEAR(CURRENT_DATE)
      and metode_bayar = '1'")->row();
      // AND uang_bayar <> '0'
  }

  function trans_day()
  {
    return $this->db->query("SELECT SUM(grand_total) AS total
    FROM tbl_transaksi
    WHERE DATE(tgl_transaksi) = CURDATE()
    AND uang_bayar <> 0; ")->row();
  }

  function count_anggota()
  {
    return $this->db->query("SELECT id from tbl_anggota")->num_rows();
  }

  function count_tempo()
  {
    return $this->db->query("SELECT * FROM `tbl_transaksi` WHERE metode_bayar ='2'")->num_rows();
  }

  function qty_0()  {
    return $this->db->query("SELECT tb.kode_barang, tb.nama_barang, tb.qty  from tbl_barang tb where qty = '0' and status_barang = '2'");
  }

  function qty_kurang()  {
    return $this->db->query("SELECT tb.kode_barang, tb.nama_barang, tb.qty  from tbl_barang tb where qty <= '4' and status_barang = '2' and qty != '0'");
  }
}
