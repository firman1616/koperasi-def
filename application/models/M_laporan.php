<?php
class M_laporan extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  public function lap_trans($date_start, $date_end)
  {
    $query = $this->db->query("SELECT
        tt.id,
        tt.no_transaksi,
        tt.grand_total,
        tt.pelanggan_id,
        tt.tgl_transaksi,
        tt.lainnya,
        ta.name AS cust,
        tt.metode_bayar
    FROM
        tbl_transaksi tt
    LEFT JOIN 
        tbl_anggota ta ON ta.id = tt.pelanggan_id
    left join tbl_dtl_trans tdt on tdt.head_trans = tt.id
    left join tbl_barang tb on tb.kode_barang  = tdt.kode_barang 
    WHERE
        DATE(tt.tgl_transaksi) >= '$date_start'
        AND DATE(tt.tgl_transaksi) <= '$date_end'
        and tt.metode_bayar = '1'
    group by
        tt.id,
        tt.no_transaksi,
        tt.grand_total,
        tt.tgl_transaksi,
        tt.lainnya,
        ta.name
    order by date(tt.tgl_transaksi) desc");
    return $query;
  }

  function export_excel_penjualan($date_start, $date_end)
  {
    $query = $this->db->query("SELECT
        tt.id,
        tt.no_transaksi,
        tt.pelanggan_id,
        tt.grand_total,
        tt.tgl_transaksi,
        tt.lainnya,
        ta.name AS cust
    FROM
        tbl_transaksi tt
    LEFT JOIN 
        tbl_anggota ta ON ta.id = tt.pelanggan_id
    WHERE
        DATE(tt.tgl_transaksi) >= '$date_start'
        AND DATE(tt.tgl_transaksi) <= '$date_end'
        and tt.metode_bayar = '1'");
    return $query;
  }

  function export_detail_penjualan($date_start, $date_end)
  {
    return $this->db->query("SELECT
      tt.no_transaksi,
      tt.tgl_transaksi,
      tdt.kode_barang,
      tdt.qty,
      tdt.total_harga as harga_barang,
      tb.nama_barang
    from
      tbl_transaksi tt
    left join tbl_dtl_trans tdt on
      tdt.head_trans = tt.id
    left join tbl_barang tb on
      tb.kode_barang = tdt.kode_barang
    where
      tt.metode_bayar = '1'
      and DATE(tt.tgl_transaksi) >= '$date_start'
      and DATE(tt.tgl_transaksi) <= '$date_end'");
  }

  // function export_excel_penjualan($date_start, $date_end)
  // {
  //   $query = $this->db->query("SELECT
  //       tt.id,
  //       tt.no_transaksi,
  //       tt.grand_total,
  //       tt.pelanggan_id,
  //       tt.tgl_transaksi,
  //       tt.lainnya,
  //       ta.name AS cust,
  //       tb.kode_barang,
  //       tb.nama_barang,
  //       tdt.qty 
  //   FROM
  //       tbl_transaksi tt
  //   LEFT JOIN 
  //       tbl_anggota ta ON ta.id = tt.pelanggan_id
  //   left join tbl_dtl_trans tdt on tdt.head_trans = tt.id
  //   left join tbl_barang tb on tb.kode_barang  = tdt.kode_barang
  //   WHERE
  //       DATE(tt.tgl_transaksi) >= '$date_start'
  //       AND DATE(tt.tgl_transaksi) <= '$date_end'
  //       and tt.metode_bayar = '1'");
  //   return $query;
  // }

  public function lap_det_trans($id)
  {
    $query = $this->db->query("SELECT
        tdt.head_trans,
        tdt.kode_barang,
        tb.nama_barang, 
        tdt.qty
    FROM tbl_dtl_trans tdt
    LEFT JOIN tbl_barang tb ON tb.kode_barang = tdt.kode_barang 
    WHERE tdt.head_trans = ?", [$id]);
    return $query;
  }

  function lap_iuran($bulan, $tahun)
  {
    return $this->db->query("SELECT 
        ti.anggota_id, 
        ti.date, 
        ti.periode, 
        ta.name AS nama_anggota, 
        ti.status 
    FROM tbl_iuran ti
    LEFT JOIN tbl_anggota ta ON ta.id = ti.anggota_id
    WHERE SUBSTRING(ti.periode, 1, 2) = '$bulan' 
    AND SUBSTRING(ti.periode, 3, 2) = '$tahun'");
  }

  public function getSimpananWajib($bulan, $tahun)
  {
    $this->db->select('ti.anggota_id, ti.date, ti.periode, ta.name AS nama_anggota, ti.nominal');
    $this->db->from('tbl_iuran ti');
    $this->db->join('tbl_anggota ta', 'ta.id = ti.anggota_id', 'left');
    $this->db->where('ti.status', '1');
    $this->db->where('SUBSTRING(ti.periode, 1, 2) =', $bulan);
    $this->db->where('SUBSTRING(ti.periode, 3, 2) =', $tahun);
    return $this->db->get()->result();
  }

  public function getSimpananPokok()
  {
    $this->db->select('td.anggota_id, td.date, td.nominal, ta.name');
    $this->db->from('tbl_deposit td');
    $this->db->join('tbl_anggota ta', 'ta.id = td.anggota_id');
    return $this->db->get()->result();
  }


  // function total_iuran()  {
  //   return $this->db->query("SELECT sum(nominal) as total from tbl_iuran where status = '1' ")->row();
  // }

  public function total_iuran($bulan = null, $tahun = null)
  {
    // Jika tidak ada parameter, ambil bulan dan tahun sekarang
    if (is_null($bulan)) {
      $bulan = date('m'); // contoh: '04'
    } else {
      $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT); // pastikan dua digit
    }

    if (is_null($tahun)) {
      $tahun_2digit = date('y'); // contoh: '25'
    } else {
      $tahun_2digit = substr($tahun, -2); // contoh: '2025' jadi '25'
    }

    // Jalankan query
    $query = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tbl_iuran ti
        WHERE status = '1' 
          AND SUBSTRING(ti.periode, 1, 2) = ?
          AND SUBSTRING(ti.periode, 3, 2) = ?;
    ", [$bulan, $tahun_2digit]);

    return $query->row();
  }



  function lap_barang()
  {
    return $this->db->query("SELECT tb.id, tb.kode_barang,tb.harga_beli, tb.harga_jual, tb.nama_barang, tb.qty  from tbl_barang tb");
  }

  function history_barang($id, $date_start, $date_end)
  {
    return $this->db->query("SELECT
      tb.id,
      tb.kode_barang,
      tb.nama_barang,
      tb.qty,
      thb.barang_id,
      thb.qty,
      thb.history_date
    from
      tbl_barang tb
    left join tbl_history_barang thb on
      thb.barang_id = tb.id
    where
      tb.id = '$id' and date(thb.history_date) >= '$date_start' and date(thb.history_date) <= '$date_end'");
  }

  function export_barang($date_start, $date_end)
  {
    return $this->db->query("SELECT
      tb.id,
      tb.kode_barang,
      tb.nama_barang,
      tb.harga_jual,
      tb.harga_beli,
      SUM(thb.qty) AS qty_history,
      DATE_FORMAT(MAX(thb.history_date), '%Y-%m-%d') AS history_date
    FROM
      tbl_barang tb
    LEFT JOIN tbl_history_barang thb ON
      thb.barang_id = tb.id
    WHERE
      DATE(thb.history_date) >= '$date_start' 
      AND DATE(thb.history_date) <= '$date_end'
    GROUP BY 
      tb.id,
      tb.kode_barang,
      tb.nama_barang,
      tb.harga_jual,
      tb.harga_beli,
      MONTH(thb.history_date)");
  }


  function lap_keuangan($periode)
  {
    return $this->db->query("SELECT
      tk.id,
      tk.kategori_keuangan,
      tk.nominal,
      tk.periode,
      tkt.name as kateg_trans,
      tk2.name as kategori,
      tk2.kode 
    from
      tbl_keuangan tk
    left join tbl_kateg_trans tkt on
      tkt.id = tk.kategori_keuangan
    left join tbl_kategori tk2 on
      tk2.id = tkt.kategori_id 
    WHERE tk2.kode = 'IN' and periode = '$periode' and kategori_keuangan not in('3','12')");
  }

  // function lap_keuangan($periode)  {
  //   return $this->db->query("SELECT
  //     tk.id,
  //     tk.kategori_keuangan,
  //     tk.nominal,
  //     tk.periode,
  //     tkt.name as kateg_trans,
  //     tk2.name as kategori,
  //     tk2.kode 
  //   from
  //     tbl_keuangan tk
  //   left join tbl_kateg_trans tkt on
  //     tkt.id = tk.kategori_keuangan
  //   left join tbl_kategori tk2 on
  //     tk2.id = tkt.kategori_id
  //   WHERE tk.periode = '$periode'");
  // }

  function sum_nominal($periode)
  {
    return $this->db->query("SELECT 
        SUM(CASE WHEN tk2.name = 'Pemasukan' THEN tk.nominal ELSE 0 END) AS pemasukan,
        SUM(CASE WHEN tk2.name = 'Pengeluaran' THEN tk.nominal ELSE 0 END) AS pengeluaran
    FROM 
        tbl_keuangan tk
    LEFT JOIN tbl_kateg_trans tkt ON tkt.id = tk.kategori_keuangan
    LEFT JOIN tbl_kategori tk2 ON tk2.id = tkt.kategori_id
    WHERE  tk.periode = '$periode'
    AND tk.kategori_keuangan NOT IN ('3', '12')");
  }

  // function sum_nominal($periode) {
  //   return $this->db->query("SELECT 
  //       tk.periode,
  //       SUM(CASE WHEN tk2.name = 'Pemasukan' THEN tk.nominal ELSE 0 END) AS pemasukan,
  //       SUM(CASE WHEN tk2.name = 'Pengeluaran' THEN tk.nominal ELSE 0 END) AS pengeluaran
  //   FROM 
  //       tbl_keuangan tk
  //   LEFT JOIN tbl_kateg_trans tkt ON tkt.id = tk.kategori_keuangan
  //   LEFT JOIN tbl_kategori tk2 ON tk2.id = tkt.kategori_id
  //   WHERE 
  //       tk.periode = '$periode'
  //   GROUP BY 
  //       tk.periode");
  // }

  function in_keuangan($periode)
  {
    return $this->db->query("SELECT
      tk.id,
      tk.kategori_keuangan,
      tk.nominal,
      tk.periode,
      tkt.name as kateg_trans,
      tk2.name as kategori,
      tk2.kode 
    from
      tbl_keuangan tk
    left join tbl_kateg_trans tkt on
      tkt.id = tk.kategori_keuangan
    left join tbl_kategori tk2 on
      tk2.id = tkt.kategori_id
    WHERE tk.periode = '$periode'
    and tk2.kode = 'IN'");
  }

  function out_keuangan($periode)
  {
    return $this->db->query("SELECT
      tk.id,
      tk.kategori_keuangan,
      tk.nominal,
      tk.periode,
      tkt.name as kateg_trans,
      tk2.name as kategori,
      tk2.kode 
    from
      tbl_keuangan tk
    left join tbl_kateg_trans tkt on
      tkt.id = tk.kategori_keuangan
    left join tbl_kategori tk2 on
      tk2.id = tkt.kategori_id
    WHERE tk.periode = '$periode'
    and tk2.kode = 'OUT'");
  }

  function getLapPemasukan($date_start, $date_end)
  {
    return $this->db->query("SELECT
      tkt.name as kategori_trans,
      tp.nominal,
      tp.date,
      tp.keterangan
    from
      tbl_pemasukan tp
    left join tbl_kateg_trans tkt on tkt.id = tp.kategori_id 
    where
      DATE(tp.date) >= '$date_start'
      and DATE(tp.date) <= '$date_end'");
  }

  function getLapPengeluaran($date_start, $date_end)
  {
    return $this->db->query("SELECT
      tkt.name as nama_kategori,
      tkt2.name as nama_sumber_dana,
      tp.nominal,
      tp.date,
      tp.keterangan
    from
      tbl_pengeluaran tp
    left join tbl_kateg_trans tkt on tkt.id = tp.kategori_id 
    left join tbl_kateg_trans tkt2 on tkt2.id = tp.sumber_dana_id 
    where DATE(tp.date) >= '$date_start'
      and DATE(tp.date) <= '$date_end'");
  }

  function export_iuran_nominal($date_start, $date_end)
  {
    return $this->db->query("SELECT 
        ti.anggota_id,
        ta.name,
        SUM(ti.nominal) AS total,
        GROUP_CONCAT(DISTINCT ti.periode ORDER BY ti.periode SEPARATOR ', ') AS periode_tergabung
    FROM tbl_iuran ti
    LEFT JOIN tbl_anggota ta ON ta.id = ti.anggota_id
    WHERE ti.status = '1' and DATE(ti.date) >= '$date_start'
          and DATE(ti.date) <= '$date_end'
    GROUP BY ti.anggota_id, ta.name;");
  }

  function export_deposit_nominal()
  {
    return $this->db->query("SELECT
      td.anggota_id,
      td.nominal as total,
      td.date,
      td.status,
      ta.name
    from
      tbl_deposit td
    left join tbl_anggota ta on ta.id = td.anggota_id ");
  }
}
