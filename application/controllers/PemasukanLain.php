<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PemasukanLain extends CI_Controller
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
            'title' => 'Pemasukan Keuangan',
            'subtitle' => 'List',
            'conten' => 'pemasukanlain/index',
            'kateg' => $this->lain->get_kateg_pemasukan(),
            'footer_js' => array('assets/js/pemasukanlain.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tablePemasukanLain()
    {
        $data['pemasukan'] = $this->lain->get_data_pemasukan()->result();
        $data['kateg'] = $this->lain->get_kateg_pemasukan();
        echo json_encode($this->load->view('pemasukanlain/pemasukan-lain-table', $data, false));
    }

    public function store()
    {
        // Ambil tanggal sekarang dalam format Y-m-d
        $tanggal_pemasukan = date('Y-m-d');
        $nominal = str_replace('.', '', $this->input->post('nominal'));

        $data = [
            'kategori_id' => $this->input->post('kategori'),
            'nominal' => $nominal,
            'keterangan' => $this->input->post('keterangan'),
            'date' => $tanggal_pemasukan, // Simpan dengan format Y-m-d
        ];

        // Simpan ke tbl_pemasukan
        $this->db->insert('tbl_pemasukan', $data);

        // Ambil kategori_keuangan berdasarkan kategori_id
        $kategori_id = $data['kategori_id'];
        $nominal_baru = $data['nominal'];

        // Format periode untuk tbl_keuangan (my -> bulan & tahun tanpa tanda)
        $periode = date('my', strtotime($tanggal_pemasukan));

        // Periksa apakah kategori sudah ada di tbl_keuangan dengan periode yang sama
        $this->db->where('kategori_keuangan', $kategori_id);
        $this->db->where('periode', $periode);
        $query = $this->db->get('tbl_keuangan');

        if ($query->num_rows() > 0) {
            // Jika sudah ada, update nominal
            $row = $query->row();
            $nominal_lama = $row->nominal;
            $total_nominal = $nominal_lama + $nominal_baru;

            $this->db->where('id', $row->id);
            $this->db->update('tbl_keuangan', ['nominal' => $total_nominal]);
        } else {
            // Jika belum ada, insert data baru
            $keuangan_data = [
                'kategori_keuangan' => $kategori_id,
                'nominal' => $nominal_baru,
                'periode' => $periode, // Format my (contoh: 0325 untuk Maret 2025)
            ];
            $this->db->insert('tbl_keuangan', $keuangan_data);
        }

        echo json_encode(['status' => 'success']);
    }

    function vedit($id)
    {
        $table = 'tbl_pemasukan';
        $where = array('id' => $id);
        $data = $this->m_data->get_data_by_id($table, $where)->row();
        echo json_encode($data);
    }

    public function delete_data($id)
    {
        // Ambil data yang akan dihapus dari tbl_pemasukan
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_pemasukan');

        if ($query->num_rows() > 0) {
            $data = $query->row();
            $kategori_id = $data->kategori_id;
            $nominal_hapus = $data->nominal;
            $periode = date('my', strtotime($data->date)); // Format my dari date di tbl_pemasukan

            // Hapus data dari tbl_pemasukan
            $this->db->where('id', $id);
            $this->db->delete('tbl_pemasukan');

            // Periksa apakah kategori ini masih ada di tbl_keuangan untuk periode tersebut
            $this->db->where('kategori_keuangan', $kategori_id);
            $this->db->where('periode', $periode);
            $query_keuangan = $this->db->get('tbl_keuangan');

            if ($query_keuangan->num_rows() > 0) {
                $row_keuangan = $query_keuangan->row();
                $nominal_lama = $row_keuangan->nominal;
                $total_nominal = $nominal_lama - $nominal_hapus;

                // Update nominal di tbl_keuangan (biarkan 0 jika hasilnya 0)
                $this->db->where('id', $row_keuangan->id);
                $this->db->update('tbl_keuangan', ['nominal' => max(0, $total_nominal)]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }
}
