<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengeluaranLain extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_lainlain', 'lain');
    }

    public function index()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Pengeluaran Keuangan',
            'subtitle' => 'List',
            'conten' => 'pengeluaranlain/index',
            'keluar' => $this->lain->kategori_keluar(),
            'sumber' => $this->lain->sumber_dana(),
            'footer_js' => array('assets/js/pengeluaranlain.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tablePengeluaranLain()
    {
        $data['pengeluaran'] = $this->lain->get_data_pengeluaran()->result();
        // $data['kateg'] = $this->lain->get_kateg_pemasukan();
        echo json_encode($this->load->view('pengeluaranlain/pengeluaran-lain-table', $data, false));
    }

    public function store()
    {
        $id = $this->input->post('id'); // ID pengeluaran (jika ada)
        $kategori_id = $this->input->post('kategori'); // ID kategori pengeluaran
        $sumber_dana_id = $this->input->post('sumberdana'); // ID sumber dana yang dipilih
        $nominal = str_replace('.', '', $this->input->post('nominal')); // Hapus titik dari format angka
        $date = $this->input->post('tgl_pengeluaran'); // Tanggal sekarang
        $keterangan = $this->input->post('keterangan');

        $data_pengeluaran = [
            'kategori_id' => $kategori_id,
            'sumber_dana_id' => $sumber_dana_id,
            'nominal' => $nominal,
            'date' => $date,
            'keterangan' => $keterangan
        ];

        if ($id) {
            // Jika ID ada, lakukan update data di tbl_pengeluaran
            $this->db->where('id', $id);
            $this->db->update('tbl_pengeluaran', $data_pengeluaran);
            $message = "Data Berhasil Diupdate!";
        } else {
            // Jika tidak ada ID, lakukan insert data ke tbl_pengeluaran
            $this->db->insert('tbl_pengeluaran', $data_pengeluaran);
            $id = $this->db->insert_id(); // Ambil ID terakhir yang diinsert
            $message = "Data Berhasil Ditambahkan!";
        }

        // **PERIODE FORMAT MMYY (contoh: "0325" untuk Maret 2025)**
        $periode = date('my');

        // ==============================
        // **1. UPDATE / INSERT KE tbl_keuangan BERDASARKAN KATEGORI**
        // ==============================
        $cek_kategori_keuangan = $this->db->get_where('tbl_keuangan', [
            'kategori_keuangan' => $kategori_id,
            'periode' => $periode
        ])->row();

        if ($cek_kategori_keuangan) {
            // Jika kategori sudah ada di tbl_keuangan, tambah nominal
            $new_nominal = $cek_kategori_keuangan->nominal + $nominal;
            $this->db->where('id', $cek_kategori_keuangan->id);
            $this->db->update('tbl_keuangan', ['nominal' => $new_nominal]);
        } else {
            // Jika belum ada, insert data baru ke tbl_keuangan
            $data_keuangan_kategori = [
                'kategori_keuangan' => $kategori_id,
                'nominal' => $nominal,
                'periode' => $periode
            ];
            $this->db->insert('tbl_keuangan', $data_keuangan_kategori);
        }

        // ==============================
        // **2. PENGURANGAN NOMINAL DI tbl_keuangan BERDASARKAN SUMBER DANA**
        // ==============================
        $cek_sumber_dana_keuangan = $this->db->get_where('tbl_keuangan', [
            'kategori_keuangan' => $sumber_dana_id, // Gunakan sumber dana sebagai kategori_keuangan
            'periode' => $periode
        ])->row();

        if ($cek_sumber_dana_keuangan) {
            // Jika sumber dana sudah ada di tbl_keuangan, kurangi nominal
            $new_nominal_sumber = $cek_sumber_dana_keuangan->nominal - $nominal;

            // Pastikan nominal tidak negatif
            if ($new_nominal_sumber < 0) {
                $new_nominal_sumber = 0;
            }

            $this->db->where('id', $cek_sumber_dana_keuangan->id);
            $this->db->update('tbl_keuangan', ['nominal' => $new_nominal_sumber]);
        } else {
            // Jika belum ada, insert data baru dengan nominal negatif
            $data_keuangan_sumber = [
                'kategori_keuangan' => $sumber_dana_id,
                'nominal' => max(0, -$nominal), // Jika belum ada, buat negatif
                'periode' => $periode
            ];
            $this->db->insert('tbl_keuangan', $data_keuangan_sumber);
        }

        echo json_encode(['status' => 'success', 'message' => $message]);
    }



    function vedit($id)
    {
        $table = 'tbl_pengeluaran';
        $where = array('id' => $id);
        $data = $this->m_data->get_data_by_id($table, $where)->row();
        echo json_encode($data);
    }

    public function delete_data($id)
    {
        $this->load->database();

        // Ambil data pengeluaran sebelum dihapus
        $pengeluaran = $this->db->select('kategori_id, sumber_dana_id, nominal')
            ->from('tbl_pengeluaran')
            ->where('id', $id)
            ->get()
            ->row();

        if ($pengeluaran) {
            $periode = date('my'); // Format MMYY, contoh: "0325" untuk Maret 2025

            // ==============================
            // **1. Tambahkan kembali saldo di tbl_keuangan berdasarkan SUMBER DANA**
            // ==============================
            $cek_sumber_dana_keuangan = $this->db->get_where('tbl_keuangan', [
                'kategori_keuangan' => $pengeluaran->sumber_dana_id,
                'periode' => $periode
            ])->row();

            if ($cek_sumber_dana_keuangan) {
                $new_nominal_sumber = $cek_sumber_dana_keuangan->nominal + $pengeluaran->nominal;
                $this->db->where('id', $cek_sumber_dana_keuangan->id);
                $this->db->update('tbl_keuangan', ['nominal' => $new_nominal_sumber]);
            }

            // ==============================
            // **2. Kurangi saldo di tbl_keuangan berdasarkan KATEGORI**
            // ==============================
            $cek_kategori_keuangan = $this->db->get_where('tbl_keuangan', [
                'kategori_keuangan' => $pengeluaran->kategori_id,
                'periode' => $periode
            ])->row();

            if ($cek_kategori_keuangan) {
                $new_nominal_kategori = $cek_kategori_keuangan->nominal - $pengeluaran->nominal;

                // Pastikan nominal tidak negatif
                if ($new_nominal_kategori < 0) {
                    $new_nominal_kategori = 0;
                }

                $this->db->where('id', $cek_kategori_keuangan->id);
                $this->db->update('tbl_keuangan', ['nominal' => $new_nominal_kategori]);
            }

            // ==============================
            // **3. Hapus data dari tbl_pengeluaran**
            // ==============================
            $table = 'tbl_pengeluaran';
            $where = array('id' => $id);
            $this->m_data->hapus_data($table, $where);
        }

        redirect('PengeluaranLain');
    }

    public function getSaldo()
    {
        $sumberdana = $this->input->post('sumberdana');

        // Ambil periode saat ini dalam format 'my' (bulan-tahun, misalnya: 042025)
        $periode = date('my'); // Format bulan-tahun (misalnya: 042025)

        // Ambil saldo berdasarkan sumber dana dan periode saat ini
        $saldo = $this->db->select('nominal')
            ->from('tbl_keuangan')
            ->where('kategori_keuangan', $sumberdana)
            ->where('periode', $periode)  // Menambahkan filter berdasarkan periode
            ->get()
            ->row();

        if ($saldo) {
            echo json_encode(['saldo' => $saldo->nominal]);
        } else {
            echo json_encode(['saldo' => 0]);
        }
    }
}
