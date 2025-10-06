<?php
class M_transaksi extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function cari($id)
  {
    return $this->db->query("SELECT * FROM `tbl_barang` WHERE id = '$id'");
  }

  public function kd_trans()
  {
    $this->db->select('RIGHT(tbl_transaksi.id,5) as kode_transaksi', FALSE);
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('tbl_transaksi');
    if ($query->num_rows() <> 0) {
      $data = $query->row();
      $kode = intval($data->kode_transaksi) + 1;
    } else {
      $kode = 1;
    }
    $date = date('ym');
    $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
    $kodetampil = "TRX-" . $date . "-" . $batas;
    return $kodetampil;
  }

  public function id_akhir()
  {
    $this->db->select('id as id_akhir', FALSE);
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('tbl_transaksi');
    if ($query->num_rows() <> 0) {
      $data = $query->row();
      $kode = intval($data->id_akhir) + 1;
    } else {
      $kode = 1;
    }
    return $kode;
  }

  public function insert_transaksi($data_transaksi, $data_detail)
  {
    $this->db->trans_start(); // Memulai transaksi database

    // Insert ke tbl_transaksi
    $this->db->insert('tbl_transaksi', $data_transaksi);
    $id_transaksi = $this->db->insert_id(); // Ambil ID transaksi yang baru dibuat

    // Update nota dengan ID transaksi yang sama
    $this->db->where('id', $id_transaksi);
    $this->db->update('tbl_transaksi', ['id' => $id_transaksi]);

    // Insert ke tbl_dtl_trans
    foreach ($data_detail as &$detail) {
      $detail['head_trans'] = $id_transaksi; // Tambahkan ID transaksi ke setiap detail
    }
    $this->db->insert_batch('tbl_dtl_trans', $data_detail); // Insert banyak data sekaligus

    $this->db->trans_complete(); // Selesaikan transaksi

    return $this->db->trans_status(); // Mengembalikan status transaksi (true/false)
  }

  public function kurangi_stok($barcode, $jumlah)
  {
    $this->db->set('qty', 'qty - ' . (int) $jumlah, FALSE);
    $this->db->where('kode_barang', $barcode);
    $this->db->update('tbl_barang');
  }

  public function get_all_anggota()
  {
    return $this->db->select('id, name, no_agt')
      ->from('tbl_anggota')
      ->get()
      ->result();
  }

  function list_trans()
  {
    return $this->db->query("SELECT
      tt.id,
      tt.no_transaksi,
      tt.grand_total,
      tt.tgl_transaksi,
      tt.pelanggan_id,
      tt.lainnya,
      ta.name
    from
      tbl_transaksi tt
    left join tbl_anggota ta  on ta.id = tt.pelanggan_id");
  }

  function head_trans($id)
  {
    return $this->db->query("SELECT
      tt.id,
      tt.no_transaksi,
      tt.grand_total,
      tt.tgl_transaksi,
      tt.pelanggan_id,
      tt.lainnya,
      tt.kasir_id,
      tt.metode_bayar,
      ta.name,
      tt.uang_bayar,
      tt.uang_kembali,
      ts.nama_user as kasir
    from
      tbl_transaksi tt
    left join tbl_anggota ta  on ta.id = tt.pelanggan_id
    left join tbl_user ts on ts.id = tt.kasir_id
    where tt.id = '$id'");
  }

  function detail_trans($id)
  {
    return $this->db->query("SELECT
      tdt.head_trans,
      tdt.kode_barang,
      tdt.qty,
      tdt.total_harga,
      tb.nama_barang 
    from
      tbl_dtl_trans tdt
    left join tbl_barang tb on tb.kode_barang = tdt.kode_barang 
    where tdt.head_trans = '$id'");
  }

  function get_tempo()
  {
    return $this->db->query("SELECT
      tt.id,
      tt.no_transaksi,
      tt.grand_total,
      tt.uang_bayar,
      tt.uang_kembali,
      tt.tgl_transaksi,
      tt.pelanggan_id,
      tt.metode_bayar,
      ta.name,
      tt.lainnya 
    from
      tbl_transaksi tt
    left join tbl_anggota ta on ta.id = tt.pelanggan_id 
    where
      tt.metode_bayar = '2'");
  }

  public function updateTransaksi($id_transaksi, $data)
  {
    $this->db->where('id', $id_transaksi);
    return $this->db->update('tbl_transaksi', $data);
  }
}
