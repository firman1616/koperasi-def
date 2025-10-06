<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 3) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_transaksi', 'trans');
    }

    public function index()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'id_user' => $this->session->userdata('id'),
            'title' => 'Data Transaksi',
            'subtitle' => 'List',
            'conten' => 'transaksi/index',
            'footer_js' => array('assets/js/transaksi.js'),
            'kd_trans' => $this->trans->kd_trans(),
            'id_akhir' => $this->trans->id_akhir(),
            'barang' => $this->m_data->get_data('tbl_barang')->result()
        ];
        $this->load->view('template/conten', $data);
    }

    function tableTransaksi()
    {
        // $data['uom'] = $this->m_data->get_data('tbl_uom')->result();

        echo json_encode($this->load->view('transaksi/transaksi-table', false));
    }

    public function generate_kode()
    {
        $kode_baru = $this->trans->kd_trans(); // panggil fungsi dari model
        echo json_encode(['kode' => $kode_baru]);
    }

    public function cari()
    {
        $kode = $_GET['kode'];
        $cari = $this->trans->cari($kode)->row_array();
        echo json_encode($cari);
    }

    public function get_anggota()
    {
        $anggota = $this->trans->get_all_anggota();
        echo json_encode($anggota);
    }

    public function get_barang()
    {
        $barcode = $this->input->get('barcode'); // Ambil barcode dari request
        $this->db->where('kode_barang', $barcode);
        $barang = $this->db->get('tbl_barang')->row_array();

        if ($barang) {
            echo json_encode([
                'kode_barang' => $barang['kode_barang'],
                'nama_barang' => $barang['nama_barang'],
                'harga_jual' => $barang['harga_jual'], // Pastikan harga ikut diambil
                'qty' => $barang['qty']
            ]);
        } else {
            echo json_encode([]);
        }
    }

    public function get_all_barang()
    {
        $this->db->select('id, kode_barang, nama_barang, qty');
        $this->db->from('tbl_barang');
        $this->db->where('status', 1); // Filter hanya barang dengan status = 1
        $this->db->where('status_barang', 2); // Tambah filter status_barang = 2
        $barang = $this->db->get()->result();

        echo json_encode($barang);
    }


    // public function proses_pembayaran()
    // {

    //     $tanggal = $this->input->post('tanggal');
    //     $diskon = $this->input->post('diskon');
    //     $total_bayar = $this->input->post('total_setelah_diskon');
    //     $uang_dibayarkan = $this->input->post('uang_dibayarkan');
    //     $uang_kembali = $uang_dibayarkan - $total_bayar;
    //     $anggota_id = $this->input->post('anggota_id');
    //     $extra_value = $this->input->post('extraField');
    //     $metode_bayar = $this->input->post('metode_bayar');
    //     $id_user = $this->input->post('id_user');

    //     // Data transaksi utama
    //     $data_transaksi = [
    //         'no_transaksi' => $this->input->post('kd_trans'), // Sementara NULL, nanti diperbarui dengan ID
    //         'diskon' => $diskon,
    //         'grand_total' => $total_bayar,
    //         'uang_bayar' => $uang_dibayarkan,
    //         'uang_kembali' => $uang_kembali,
    //         'tgl_transaksi' => $tanggal,
    //         'pelanggan_id' => $anggota_id,
    //         'lainnya' => ($anggota_id == 117) ? $extra_value : null,
    //         'metode_bayar' => $metode_bayar,
    //         'kasir_id' => $id_user,
    //     ];

    //     log_message('error', 'Data Transaksi: ' . print_r($data_transaksi, true));

    //     // Ambil data barang dari frontend
    //     $barang = $this->input->post('barang'); // Array barang yang dikirim dari frontend
    //     $data_detail = [];

    //     foreach ($barang as $item) {
    //         $data_detail[] = [
    //             'kode_barang' => $item['barcode'],
    //             'qty' => $item['jumlah'],
    //             'total_harga' => $item['harga']
    //         ];
    //     }

    //     // Simpan transaksi dan detailnya
    //     $result = $this->trans->insert_transaksi($data_transaksi, $data_detail);

    //     // Kurangi stok barang
    //     foreach ($barang as $item) {
    //         $this->trans->kurangi_stok($item['barcode'], $item['jumlah']);
    //     }

    //     if ($result) {
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error']);
    //     }
    // }

    public function proses_pembayaran()
    {
        $no_transaksi = $this->input->post('kd_trans');
        $tanggal = $this->input->post('tanggal');
        $diskon = $this->input->post('diskon');
        $total_bayar = $this->input->post('total_setelah_diskon');
        $uang_dibayarkan = $this->input->post('uang_dibayarkan');
        $uang_kembali = $uang_dibayarkan - $total_bayar;
        $anggota_id = $this->input->post('anggota_id');
        $extra_value = $this->input->post('extraField');
        $metode_bayar = $this->input->post('metode_bayar');
        $id_user = $this->input->post('id_user');

        // ✅ CEK DUPLIKAT NO TRANSAKSI
        $cek = $this->db->get_where('tbl_transaksi', ['no_transaksi' => $no_transaksi])->row();
        if ($cek) {
            echo json_encode(['status' => 'duplicate']);
            return; // stop eksekusi lebih lanjut
        }

        // Format periode berdasarkan tgl_transaksi (format MMYY)
        $periode = date('my', strtotime($tanggal));

        // Data transaksi utama
        $data_transaksi = [
            'no_transaksi' => $no_transaksi,
            'diskon' => $diskon,
            'grand_total' => $total_bayar,
            'uang_bayar' => $uang_dibayarkan,
            'uang_kembali' => $uang_kembali,
            'tgl_transaksi' => $tanggal,
            'pelanggan_id' => $anggota_id,
            'lainnya' => ($anggota_id == 117) ? $extra_value : null,
            'metode_bayar' => $metode_bayar,
            'kasir_id' => $id_user,
        ];

        // log_message('error', 'Data Transaksi: ' . print_r($data_transaksi, true));

        // Ambil data barang dari frontend
        $barang = $this->input->post('barang');
        $data_detail = [];

        foreach ($barang as $item) {
            $data_detail[] = [
                'kode_barang' => $item['barcode'],
                'qty' => $item['jumlah'],
                'total_harga' => $item['harga']
            ];
        }

        // Simpan transaksi dan detailnya
        $result = $this->trans->insert_transaksi($data_transaksi, $data_detail);

        // Jika metode pembayaran adalah "Cash", update tbl_keuangan
        // Jika metode pembayaran adalah "Cash", update tbl_keuangan
        if ($metode_bayar == "1") {
            // Hitung total transaksi dalam periode saat ini
            $periode = date('my'); // Format periode '0425' (April 2025)

            $this->db->select('SUM(grand_total) AS total_transaksi');
            $this->db->from('tbl_transaksi');
            $this->db->where("DATE_FORMAT(tgl_transaksi, '%m%y') =", $periode);
            $query = $this->db->get();

            $result = $query->row();
            $total_transaksi = $result ? $result->total_transaksi : 0;

            // Hitung periode sebelumnya
            $bulan_sekarang = (int)date('m');
            $tahun_sekarang = (int)date('y');

            if ($bulan_sekarang == 1) { // Jika bulan Januari, periode sebelumnya adalah Desember tahun lalu
                $bulan_sebelumnya = 12;
                $tahun_sebelumnya = $tahun_sekarang - 1;
            } else {
                $bulan_sebelumnya = $bulan_sekarang - 1;
                $tahun_sebelumnya = $tahun_sekarang;
            }

            $periode_sebelumnya = sprintf('%02d%02d', $bulan_sebelumnya, $tahun_sebelumnya); // Format MMYY

            // Ambil saldo awal dari tbl_keuangan untuk periode sebelumnya
            $this->db->select('nominal');
            $this->db->from('tbl_keuangan');
            $this->db->where('kategori_keuangan', '11');
            $this->db->where('periode', $periode_sebelumnya);
            $query_saldo = $this->db->get();

            $saldo_awal = $query_saldo->row() ? $query_saldo->row()->nominal : 0;

            // Update nominal dengan menambahkan total transaksi baru ke saldo awal
            $total_nominal = $saldo_awal + $total_transaksi;

            // Update hanya field nominal di tbl_keuangan berdasarkan periode saat ini
            $this->db->where('kategori_keuangan', '11');
            $this->db->where('periode', $periode);
            $this->db->update('tbl_keuangan', ['nominal' => $total_nominal]);
        }


        // Kurangi stok barang
        foreach ($barang as $item) {
            $this->trans->kurangi_stok($item['barcode'], $item['jumlah']);
        }

        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }


    function list_trans()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Daftar Transaksi',
            'subtitle' => 'Lits',
            'conten' => 'list-transaksi/index',
            'footer_js' => array('assets/js/list-trans.js'),
        ];
        $this->load->view('template/conten', $data);
    }

    function tableListTrans()
    {
        $data['list'] = $this->trans->list_trans()->result();
        echo json_encode($this->load->view('list-transaksi/list-trans-table', $data, false));
    }

    function cetak_struk($id)
    {
        $data = [
            'header' => $this->trans->head_trans($id)->result(),
            'detail' => $this->trans->detail_trans($id)->result()
        ];
        $this->load->view('transaksi/cetak_trans', $data);
    }

    // section tempo
    function trans_tempo()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Data Transaksi Tempo',
            'subtitle' => 'List',
            'conten' => 'tempo/index',
            'footer_js' => array('assets/js/transaksi.js'),
        ];
        $this->load->view('template/conten', $data);
    }

    function tableTempo()
    {
        $data['tempo'] = $this->trans->get_tempo()->result();
        echo json_encode($this->load->view('tempo/tempo-table', $data, false));
    }

    // public function updatePembayaran()
    // {
    //     date_default_timezone_set('Asia/Jakarta');

    //     $id_transaksi = $this->input->post('id_transaksi'); // Ambil ID transaksi dari form
    //     if (!$id_transaksi) {
    //         echo json_encode(['status' => 'error', 'message' => 'ID Transaksi tidak ditemukan!']);
    //         return;
    //     }

    //     $data = [
    //         'uang_bayar'     => $this->input->post('uang_bayar'),
    //         'uang_kembali'   => $this->input->post('uang_kembali'),
    //         'tgl_transaksi'  => date('Y-m-d H:i:s'), // Format waktu saat ini
    //         'metode_bayar'   => 1 // Set metode bayar ke 1
    //     ];

    //     $update = $this->trans->updateTransaksi($id_transaksi, $data); // Pastikan model dipanggil dengan benar

    //     if ($update) {
    //         echo json_encode(['status' => 'success', 'message' => 'Pembayaran Tempo Lunas!']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan pembayaran Tempo!']);
    //     }
    // }
    public function updatePembayaran()
    {
        date_default_timezone_set('Asia/Jakarta');

        $id_transaksi = $this->input->post('id_transaksi'); // Ambil ID transaksi dari form
        if (!$id_transaksi) {
            echo json_encode(['status' => 'error', 'message' => 'ID Transaksi tidak ditemukan!']);
            return;
        }

        // Ambil grand_total dan tgl_transaksi dari tbl_transaksi berdasarkan id_transaksi
        $this->db->select('grand_total, tgl_transaksi');
        $this->db->from('tbl_transaksi');
        $this->db->where('id', $id_transaksi);
        $query = $this->db->get();
        $transaksi = $query->row();

        if (!$transaksi) {
            echo json_encode(['status' => 'error', 'message' => 'Data transaksi tidak ditemukan!']);
            return;
        }

        // Ambil periode berdasarkan tgl_transaksi dengan format my (misal: 0325 untuk Maret 2025)
        $periode = date('my', strtotime($transaksi->tgl_transaksi));

        // Hitung periode sebelumnya
        $bulan_sekarang = (int)date('m', strtotime($transaksi->tgl_transaksi));
        $tahun_sekarang = (int)date('y', strtotime($transaksi->tgl_transaksi));

        if ($bulan_sekarang == 1) { // Jika bulan Januari, periode sebelumnya adalah Desember tahun lalu
            $bulan_sebelumnya = 12;
            $tahun_sebelumnya = $tahun_sekarang - 1;
        } else {
            $bulan_sebelumnya = $bulan_sekarang - 1;
            $tahun_sebelumnya = $tahun_sekarang;
        }

        $periode_sebelumnya = sprintf('%02d%02d', $bulan_sebelumnya, $tahun_sebelumnya); // Format MMYY

        // Ambil saldo awal dari tbl_keuangan untuk periode sebelumnya
        $this->db->select('nominal');
        $this->db->from('tbl_keuangan');
        $this->db->where('kategori_keuangan', '11');
        $this->db->where('periode', $periode_sebelumnya);
        $query_saldo = $this->db->get();

        $saldo_awal = $query_saldo->row() ? $query_saldo->row()->nominal : 0;

        // Hitung total transaksi untuk periode yang sama
        $this->db->select('SUM(grand_total) as total_transaksi');
        $this->db->from('tbl_transaksi');
        $this->db->where("DATE_FORMAT(tgl_transaksi, '%m%y') =", $periode);
        $query_total = $this->db->get();
        $total_transaksi = $query_total->row()->total_transaksi ?? 0; // Pastikan nilai tidak null

        // Total nominal baru (saldo awal + total transaksi pada periode ini)
        $total_nominal = $saldo_awal + $total_transaksi;

        // Data update ke tbl_transaksi
        $data_transaksi = [
            'uang_bayar'   => $this->input->post('uang_bayar'),
            'uang_kembali' => $this->input->post('uang_kembali'),
            'tgl_transaksi' => date('Y-m-d H:i:s'), // Format waktu saat ini
            'metode_bayar' => 1 // Set metode bayar ke 1
        ];

        $update_transaksi = $this->trans->updateTransaksi($id_transaksi, $data_transaksi); // Update transaksi

        if ($update_transaksi) {
            // Jika metode_bayar = 1, update tbl_keuangan dengan total nominal baru
            $this->db->set('nominal', $total_nominal);
            $this->db->where('kategori_keuangan', '11');
            $this->db->where('periode', $periode);
            $update_keuangan = $this->db->update('tbl_keuangan');

            if ($update_keuangan) {
                echo json_encode(['status' => 'success', 'message' => 'Pembayaran Tempo Lunas & Data Keuangan Diperbarui!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Pembayaran berhasil tetapi update keuangan gagal!']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal melakukan pembayaran Tempo!']);
        }
    }
}
