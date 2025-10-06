<?php
class M_barang extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_data()
  {
    return $this->db->query("SELECT * FROM tbl_barang WHERE status = '1'");
  }

  public function kd_barang()
  {
    $this->db->select('RIGHT(tbl_barang.id,5) as kode_barang', FALSE);
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('tbl_barang');
    if ($query->num_rows() <> 0) {
      $data = $query->row();
      $kode = intval($data->kode_barang) + 1;
    } else {
      $kode = 1;
    }
    $date = date('ym');
    $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
    $kodetampil = "BRG-" . $date . "-" . $batas;
    return $kodetampil;
  }

  public function get_barang_by_id($id)
  {
    return $this->db->where('id', $id)->get('tbl_barang')->row_array();
  }

  public function insert_history($data)
  {
    return $this->db->insert('tbl_history_barang', $data);
  }

  // Tambahkan qty ke tbl_barang berdasarkan barang_id
  public function update_qty_barang($id, $qty)
  {
    $this->db->set('qty', 'qty + ' . (int)$qty, FALSE); // Menambahkan qty
    $this->db->where('id', $id);
    return $this->db->update('tbl_barang');
  }

  function data_export_barang()  {
      return $this->db->query("SELECT
        tb.kode_barang,
        tb.nama_barang,
        tb.harga_jual,
        tb.qty,
        tu.uom
      from
        tbl_barang tb
      join tbl_uom tu on tu.kode = tb.uom where tb.status = '1'");
  }
}
