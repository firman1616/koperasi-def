<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peserta extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2 && $this->session->userdata('level') != 3) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_anggota', 'anggota');
    }

    public function index()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Data Peserta',
            'subtitle' => 'List',
            'conten' => 'peserta/index',
            'footer_js' => array('assets/js/peserta.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tablePeserta()
    {
        $data['anggota'] = $this->anggota->get_data()->result();

        echo json_encode($this->load->view('peserta/peserta-table', $data, false));
    }

    function vtambah()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Tambah Data Peserta',
            'subtitle' => 'Form Tambah Data',
            'conten' => 'peserta/tambah-data',
            // 'footer_js' => array('assets/js/peserta.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tambah_data()
    {
        $table = 'tbl_anggota';
        $data = [
            'no_agt' => $this->input->post('no_anggota'),
            'name' => $this->input->post('nama_anggota'),
            'jk' => $this->input->post('jenis_kelamin'),
            'binbinti' => $this->input->post('binbinti'),
            'tmp_lahir' => $this->input->post('tempat_lahir'),
            'tgl_lahir' => $this->input->post('tgl_lahir'),
            'nik' => $this->input->post('nik'),
            'alamat' => $this->input->post('alamat'),
            'kongsi2' => $this->input->post('kongsi1'),
            'kongsi1' => $this->input->post('kongsi2'),
            'kongsi3' => $this->input->post('kongsi3'),
            'no_telp' => $this->input->post('phone')
        ];
        $this->m_data->simpan_data($table, $data);
        redirect('Peserta');
    }

    function vedit($id)
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Edit Data',
            'subtitle' => 'List',
            'conten' => 'peserta/edit-data',
            'edit' => $this->m_data->get_data_by_id('tbl_anggota', array('id' => $id))
            // 'footer_js' => array('assets/js/peserta.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function update_data($id)
    {
        $table = 'tbl_anggota';
        $data = [
            'no_agt' => $this->input->post('no_anggota'),
            'name' => $this->input->post('nama_anggota'),
            'jk' => $this->input->post('jenis_kelamin'),
            'binbinti' => $this->input->post('binbinti'),
            'tmp_lahir' => $this->input->post('tempat_lahir'),
            'tgl_lahir' => $this->input->post('tgl_lahir'),
            'nik' => $this->input->post('nik'),
            'alamat' => $this->input->post('alamat'),
            'kongsi2' => $this->input->post('kongsi1'),
            'kongsi1' => $this->input->post('kongsi2'),
            'kongsi3' => $this->input->post('kongsi3'),
            'no_telp' => $this->input->post('phone')
        ];
        $where = array('id' => $id);
        $this->m_data->update_data($table, $data, $where);
        redirect('Peserta');
    }

    function delete_data($id)
    {
        $table = 'tbl_anggota';
        $where = array('id' => $id);
        $this->m_data->hapus_data($table, $where);
        redirect('Peserta');
    }

    public function simpanIuran()
    {
        $anggota_id = $this->input->post('anggota_id');

        if (!$anggota_id) {
            echo json_encode(["message" => "ID Anggota tidak ditemukan"]);
            return;
        }

        $data_iuran = [
            'anggota_id' => $anggota_id,
            'nominal' => 200000,
            'date' => date('Y-m-d H:i:s')
        ];

        // Insert data ke tbl_iuran
        $this->db->insert('tbl_iuran', $data_iuran);

        // Update status iuran di tbl_anggota menjadi 2
        $this->db->where('id', $anggota_id);
        $this->db->update('tbl_anggota', ['status_iuran' => 2]);

        echo json_encode(["message" => "Iuran berhasil disimpan"]);
    }

    function iuran()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Simpanan Wajib',
            'subtitle' => 'List',
            'conten' => 'iuran/index',
            'footer_js' => array('assets/js/peserta.js')
        ];
        $this->load->view('template/conten', $data);
    }

    // public function get_iuran_data()
    // {
    //     $this->db->select("a.id, a.name, b.periode, b.status");
    //     $this->db->from("tbl_anggota a");
    //     $this->db->join("tbl_iuran b", "a.id = b.anggota_id", "left");
    //     $this->db->where("a.id !=", 117);
    //     $query = $this->db->get();
    //     $result = $query->result();

    //     // Ubah hasil query menjadi format yang bisa digunakan di View
    //     $iuran_data = [];
    //     foreach ($result as $row) {
    //         if (!isset($iuran_data[$row->id])) {
    //             $iuran_data[$row->id] = (object) [
    //                 'id' => $row->id,
    //                 'name' => $row->name,
    //                 'iuran_status' => [] // Simpan status pembayaran
    //             ];
    //         }
    //         if ($row->periode) {
    //             $iuran_data[$row->id]->iuran_status[$row->periode] = $row->status;
    //         }
    //     }

    //     return array_values($iuran_data);
    // }

    public function get_iuran_data()
    {
        $this->db->select("a.id, a.name, b.periode, b.status, d.status as deposit_status");
        $this->db->from("tbl_anggota a");
        $this->db->join("tbl_iuran b", "a.id = b.anggota_id", "left");
        $this->db->join("tbl_deposit d", "a.id = d.anggota_id", "left"); // Gabungkan dengan tbl_deposit
        $this->db->where("a.id !=", 117);
        $query = $this->db->get();
        $result = $query->result();

        // Ubah hasil query menjadi format yang bisa digunakan di View
        $iuran_data = [];
        foreach ($result as $row) {
            if (!isset($iuran_data[$row->id])) {
                $iuran_data[$row->id] = (object) [
                    'id' => $row->id,
                    'name' => $row->name,
                    'deposit_status' => $row->deposit_status, // Status deposit
                    'iuran_status' => [] // Simpan status pembayaran iuran
                ];
            }
            if ($row->periode) {
                $iuran_data[$row->id]->iuran_status[$row->periode] = $row->status;
            }
        }

        return array_values($iuran_data);
    }

    function tableIuran()
    {
        $data['iuran'] = $this->get_iuran_data();
        // $this->anggota->get_data()->result();

        echo json_encode($this->load->view('iuran/iuran-table', $data, false));
    }

    public function update_iuran()
    {
        $anggota_id = $this->input->post('anggota_id');
        $periode = $this->input->post('periode');
        $date = $this->input->post('date');

        if (empty($anggota_id) || empty($periode) || empty($date)) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap!"]);
            return;
        }

        $update_iuran = $this->anggota->update_iuran($anggota_id, $periode, $date);

        if ($update_iuran) {
            // 1️⃣ Ambil saldo sebelumnya dari tbl_keuangan
            $saldo_sebelumnya = $this->anggota->get_nominal_keuangan_iuran(3, $periode);

            // 2️⃣ Ambil total SUM dari tbl_iuran (status = 1) hanya untuk periode saat ini
            $total_nominal = $this->anggota->get_total_iuran_by_periode($periode);

            // 3️⃣ Hitung saldo baru dengan menambahkan saldo sebelumnya
            $saldo_baru = $saldo_sebelumnya + $total_nominal;

            // 4️⃣ Update saldo baru ke tbl_keuangan
            $update_data = ['nominal' => $saldo_baru];
            $this->anggota->update_keuangan(3, $periode, $update_data);

            // get_data simpanan pokok
            $saldo_keuangan_12 = $this->anggota->get_nominal_keuangan(12, $periode);
            $saldo_baru_13 = $saldo_keuangan_12 + $saldo_baru;

            $get_nominal_pengeluaran_lain_13 = $this->anggota->get_pengeluaran_lain($periode);
            $update_13_after_trans = $saldo_baru_13 - $get_nominal_pengeluaran_lain_13;

            $get_saldo_13 = $this->anggota->get_nominal_keuangan(13, $periode);
            $tambahan_saldo_13 = $saldo_baru_13 - $get_saldo_13;

            $this->anggota->update_keuangan(13, $periode, ['nominal' => $update_13_after_trans]);

            log_message('debug', "Update Keuangan: Periode: $periode | Saldo Sebelumnya: $saldo_sebelumnya | Tambahan: $total_nominal | Saldo Baru: $saldo_baru");
            log_message('debug', "Update Keuangan Kategori 13: Periode: $periode | Saldo Sebelumnya: $get_saldo_13 | Tambahan: $tambahan_saldo_13 | Saldo Baru: $saldo_baru_13");

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function deposit()
    {
        $anggota_id = $this->input->post('anggota_id');
        $nominal = $this->input->post('nominal');
        $date = date('Y-m-d');
        $periode = date('my'); // Format periode saat ini (misal: 0425 untuk April 2025)

        if (empty($anggota_id) || empty($nominal)) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap!"]);
            return;
        }

        // Simpan data deposit ke dalam database
        $data_deposit = [
            'anggota_id' => $anggota_id,
            'nominal' => $nominal,
            'date' => $date,
            'status' => 1
        ];

        $insert_deposit = $this->anggota->insert_deposit($data_deposit);

        if ($insert_deposit) {
            // Ambil total SUM dari nominal di tbl_deposit berdasarkan periode saat ini
            $total_nominal = $this->anggota->get_total_deposit_by_periode($periode);

            // Ambil nominal saat ini dari tbl_keuangan untuk kategori 12
            $current_nominal = $this->anggota->get_nominal_keuangan(12, $periode);

            if ($current_nominal === null) {
                $current_nominal = 0; // Jika tidak ada data, anggap nominal awal adalah 0
            }

            $new_nominal = $current_nominal + $nominal; // Tambah nominal baru

            // Debugging: Cek nilai sebelum update
            log_message('debug', "Update Keuangan: Periode: $periode, Nominal Lama: $current_nominal, Nominal Baru: $new_nominal");

            // Update tbl_keuangan
            $update_data = ['nominal' => $new_nominal];

            $update_result = $this->anggota->update_keuangan(12, $periode, $update_data);

            $sum_12_3 = $this->anggota->sum_12_3($periode);

            $get_nominal_pengeluaran_lain_13 = $this->anggota->get_pengeluaran_lain($periode);
            $update_13_after_trans = $sum_12_3 - $get_nominal_pengeluaran_lain_13;
            // die(json_encode($update_13_after_trans));

            $this->anggota->update_keuangan(13, $periode, ['nominal' => $update_13_after_trans]);

            

            if ($update_result) {
                echo json_encode(['status' => 'success', 'message' => 'Update keuangan berhasil']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update keuangan gagal']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Insert deposit gagal']);
        }
    }
}
