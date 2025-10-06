<?php
class M_keuangan extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }
  function get_data()
  {
    return $this->db->query("SELECT a.id, a.name, a.kategori_id, b.kode FROM tbl_kateg_trans a
    LEFT JOIN tbl_kategori b on b.id = a.kategori_id");
  }
}
