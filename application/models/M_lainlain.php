<?php
class M_lainlain extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_kateg_pemasukan()
  {
    return $this->db->query("SELECT * FROM tbl_kateg_trans WHERE kategori_id = '1' AND id in ('1','2','12','14')");
  }

  public function insert_pemasukan($data)
  {
    return $this->db->insert('tbl_pemasukan', $data);
  }

  function get_data_pemasukan()
  {
    return $this->db->query('SELECT tp.id, tp.kategori_id, tp.nominal, tp.date, tp.keterangan, tkt.name as kateg from tbl_pemasukan tp 
    left join tbl_kateg_trans tkt on tkt.id = tp.kategori_id');
  }

  public function update_pemasukan($id, $data)
  {
    $this->db->where('id', $id);
    $update = $this->db->update('tbl_pemasukan', $data);

    if (!$update) {
      error_log("Gagal update ID: $id. Error: " . $this->db->error()['message']);
    }

    return $update;
  }

  function kategori_keluar() {
    return $this->db->query("SELECT * FROM tbl_kateg_trans tkt where kategori_id ='2'");
  }

  function sumber_dana() {
    return $this->db->query("SELECT
      tk.kategori_keuangan,
      tk.nominal,
      tk.periode,
      tkt.name,
      tk2.name as kategori
    from
      tbl_keuangan tk 
    left join tbl_kateg_trans tkt on tkt.id = tk.kategori_keuangan 
    left join tbl_kategori tk2 on tk2.id = tkt.kategori_id 
    where tk2.id = '1' and tk.periode = DATE_FORMAT(CURDATE(), '%m%y') and tk.kategori_keuangan not in ('3','12')");
  }

  function get_data_pengeluaran()  {
    return $this->db->query("SELECT
      tp.id,
      tp.kategori_id,
      tp.sumber_dana_id,
      tp.nominal,
      tp.date,
      tp.keterangan,
      tkt.name as kategori,
      tkt2.name as sumber_dana
    from
      tbl_pengeluaran tp
    left join tbl_kateg_trans tkt on tkt.id = tp.kategori_id 
    left join tbl_kateg_trans tkt2 on tkt2.id = tp.sumber_dana_id ");
  }
}
