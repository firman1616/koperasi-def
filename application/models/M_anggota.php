<?php
class M_anggota extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_data()
  {
    return $this->db->query("SELECT * FROM `tbl_anggota` WHERE name != 'lainnya'");
  }

  public function update_iuran($anggota_id, $periode, $date)
  {
    $this->db->where('anggota_id', (int)$anggota_id);
    $this->db->where('periode', $periode);
    $query = $this->db->get('tbl_iuran');

    if ($query->num_rows() > 0) {
      // Jika sudah ada, lakukan update
      return $this->db->update('tbl_iuran', [
        'date' => $date,
        'status' => 1,
        'nominal' => 200000
      ], ['anggota_id' => (int)$anggota_id, 'periode' => $periode]);
    } else {
      // Jika belum ada, insert data baru
      return $this->db->insert('tbl_iuran', [
        'anggota_id' => (int)$anggota_id,
        'periode' => $periode,
        'date' => $date,
        'status' => 1,
        'nominal' => 200000
      ]);
    }
  }

  public function insert_deposit($data)
  {
    return $this->db->insert('tbl_deposit', $data);
  }

  public function get_total_deposit_by_periode($periode)
  {
    $this->db->select('SUM(nominal) as total');
    $this->db->from('tbl_deposit');
    $this->db->where("DATE_FORMAT(date, '%m%y') =", $periode);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $total = (int) $query->row()->total;
      log_message('debug', "Total Deposit Periode $periode: $total"); // Debugging
      return $total;
    }

    log_message('debug', "Total Deposit Periode $periode: 0 (tidak ditemukan)"); // Debugging
    return 0;
  }


  public function get_total_iuran_by_periode($periode)
  {
    $this->db->select('SUM(nominal) as total');
    $this->db->from('tbl_iuran');
    $this->db->where("DATE_FORMAT(date, '%m%y') =", $periode);
    $this->db->where("status", 1); // Hanya ambil status aktif
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return (int) $query->row()->total;
    }
    return 0;
  }


  public function get_nominal_keuangan($kategori_id, $periode)
  {
    $this->db->select('nominal');
    $this->db->from('tbl_keuangan');
    $this->db->where('kategori_keuangan', $kategori_id);
    $this->db->where('periode', $periode);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $nominal = (int) $query->row()->nominal;
      log_message('debug', "Nominal Keuangan (Kategori $kategori_id, Periode $periode): $nominal"); // Debugging
      return $nominal;
    }

    log_message('debug', "Nominal Keuangan (Kategori $kategori_id, Periode $periode): Tidak ditemukan"); // Debugging
    return 0; // Jika tidak ada data, anggap nominal awal adalah 0
  }

  public function get_nominal_keuangan_iuran($kategori_id, $periode)
  {
    // Hitung periode sebelumnya
    $bulan = (int) substr($periode, 0, 2); // Ambil 2 digit pertama (bulan)
    $tahun = (int) substr($periode, 2, 2); // Ambil 2 digit terakhir (tahun)

    if ($bulan == 1) { // Jika Januari, mundur ke Desember tahun sebelumnya
      $bulan = 12;
      $tahun -= 1;
    } else {
      $bulan -= 1;
    }

    // Format periode sebelumnya (misal: '0325' → '0225')
    $periode_sebelumnya = sprintf('%02d%02d', $bulan, $tahun);

    $this->db->select('nominal');
    $this->db->from('tbl_keuangan');
    $this->db->where('kategori_keuangan', $kategori_id);
    $this->db->where('periode', $periode_sebelumnya);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $nominal = (int) $query->row()->nominal;
      log_message('debug', "Nominal Keuangan (Kategori $kategori_id, Periode $periode_sebelumnya): $nominal"); // Debugging
      return $nominal;
    }

    log_message('debug', "Nominal Keuangan (Kategori $kategori_id, Periode $periode_sebelumnya): Tidak ditemukan"); // Debugging
    return 0; // Jika tidak ada data, anggap nominal awal adalah 0
  }

  public function update_keuangan($kategori_id, $periode, $data)
  {
    $this->db->where('kategori_keuangan', $kategori_id);
    $this->db->where('periode', $periode);
    $query = $this->db->get('tbl_keuangan');

    if ($query->num_rows() > 0) {
      // Jika data ditemukan, lakukan update
      $this->db->where('kategori_keuangan', $kategori_id);
      $this->db->where('periode', $periode);
      $update_result = $this->db->update('tbl_keuangan', $data);

      log_message('debug', "Update Keuangan Berhasil: " . json_encode($data));
      return $update_result;
    } else {
      // Jika data tidak ditemukan, lakukan insert baru
      $data['kategori_keuangan'] = $kategori_id;
      $data['periode'] = $periode;
      $insert_result = $this->db->insert('tbl_keuangan', $data);

      log_message('debug', "Insert Keuangan Baru: " . json_encode($data));
      return $insert_result;
    }
  }

  function get_pengeluaran_lain($periode){
    $this->db->select('SUM(nominal) as total');
    $this->db->from('tbl_pengeluaran');
    $this->db->where("DATE_FORMAT(date, '%m%y') =", $periode);
    $this->db->where("sumber_dana_id", 13); // Hanya ambil status aktif
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return (int) $query->row()->total;
    }
    return 0;
  }

  function sum_12_3($periode) {
    $this->db->select('SUM(nominal) as total');
    $this->db->from('tbl_keuangan');
    $this->db->where("periode =", $periode);
    $this->db->where_in("kategori_keuangan", [12, 3]); // Hanya ambil status aktif
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return (int) $query->row()->total;
    }
    return 0;
  }
}
