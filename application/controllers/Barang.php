<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Pdf');
        $this->load->model('M_barang', 'barang');
    }
	
	public function index()
	{
		$data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Data Barang',
            'subtitle' => 'List',
			'conten' => 'barang/index',
            'footer_js' => array('assets/js/barang.js')
		];
		$this->load->view('template/conten',$data);
	}

    function tableBarang()
    {
        $data['barang'] = $this->barang->get_data()->result();

        echo json_encode($this->load->view('barang/barang-table',$data,false));
    }

    function vtambah() {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Tambah Data Barang',
            'subtitle' => 'Form Tambah Data',
			'conten' => 'barang/tambah-data',
            'uom' => $this->m_data->get_data('tbl_uom')->result(),
            'kd_barang' => $this->barang->kd_barang(),
            'footer_js' => array('assets/js/barang.js')
            
		];
		$this->load->view('template/conten',$data);
    }

    function tambah_data() {
        date_default_timezone_set('Asia/Jakarta');
        $table = 'tbl_barang';
        $data = [
            'kode_barang' => $this->input->post('kode_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
            'harga_beli' => $this->input->post('harga_beli'),
            'harga_jual' => $this->input->post('harga_jual'),
            'margin' => 0,
            'qty' => $this->input->post('qty'),
            'uom' => $this->input->post('uom'),
            'create_at' => date('Y-m-d H:i:s'),
            'tgl_update_stock' => date('Y-m-d H:i:s'),
            'jenis' => '1',
            'set_diskon' => $this->input->post('set_diskon'),
            'min_qty' => $this->input->post('min_qty'),
            'status_barang' => $this->input->post('jenis_barang')
        ];
        $this->m_data->simpan_data($table,$data);
        redirect('Barang');
    }

    function vedit($id)  {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
			'title' => 'Edit Data',
            'subtitle' => 'List',
			'conten' => 'barang/edit-data',
            'uom' => $this->m_data->get_data('tbl_uom')->result(),
            'edit' => $this->m_data->get_data_by_id('tbl_barang', array('id' => $id))
            // 'footer_js' => array('assets/js/peserta.js')
		];
		$this->load->view('template/conten',$data);
    }

    function update_data($id) {
        date_default_timezone_set('Asia/Jakarta');
        $table = 'tbl_barang';
        $beli = $this->input->post('harga_beli');
        $jual = $this->input->post('harga_jual');
        $margin = $jual - $beli;
        $data = [
           'kode_barang' => $this->input->post('kode_barang'),
            'nama_barang' => $this->input->post('nama_barang'),
            'harga_beli' => $this->input->post('harga_beli'),
            'harga_jual' => $this->input->post('harga_jual'),
            'margin' => 0,
            // 'qty' => $this->input->post('qty'),
            'uom' => $this->input->post('uom'),
            'tgl_update_stock' => date('Y-m-d H:i:s'),
            // 'jenis' => '1'
            'set_diskon' => $this->input->post('set_diskon'),
            'min_qty' => $this->input->post('min_qty'),
            'status_barang' => $this->input->post('jenis_barang')
        ];
        $where = array('id' => $id);
        $this->m_data->update_data($table,$data,$where);
        redirect('Barang');
    }

    function update_status($id)  {
        $table = 'tbl_barang';
        $data = array('status' => '0');
        $where = array('id' => $id);
        $this->m_data->update_data($table,$data,$where);
        redirect('Barang');
    }

    function delete_data($id)  {
        $table = 'tbl_anggota';
        $where = array('id' => $id);
        $this->m_data->hapus_data($table,$where);
        redirect('Peserta');
    }

    public function get_barang_by_id() {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(["error" => "ID tidak ditemukan"]);
            return;
        }

        $barang = $this->barang->get_barang_by_id($id);
    
        if ($barang) {
            echo json_encode($barang);
        } else {
            echo json_encode(["error" => "Barang tidak ditemukan"]);
        }
    }
    
    public function update_stok() {
        $id = $this->input->post('id');  // ID barang
        $qty = $this->input->post('qty'); // Qty yang diinput user
        $history_date = date('Y-m-d H:i:s'); // Waktu transaksi
    
        if (!$id || !$qty) {
            echo "Data tidak lengkap! (ID: $id, QTY: $qty)";
            return;
        }
    
        // Simpan ke tabel history
        $history_data = [
            'barang_id' => $id,
            'qty' => $qty,
            'history_date' => $history_date
        ];
        $insert_history = $this->barang->insert_history($history_data);
    
        if ($insert_history) {
            // Jika berhasil simpan history, update stok barang
            $update_stok = $this->barang->update_qty_barang($id, $qty);
            if ($update_stok) {
                echo "Stok berhasil diperbarui!";
            } else {
                echo "Gagal memperbarui stok.";
            }
        } else {
            echo "Gagal menyimpan ke history.";
        }
    }

    function export_data(){

        // Ambil data dari model berdasarkan tanggal input
        $data = $this->barang->data_export_barang()->result();

        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'QTY');
        $sheet->setCellValue('E1', 'Satuan');
        $sheet->setCellValue('F1', 'Harga');

        // Isi data
        $row = 2;
        $x = 1;
        foreach ($data as $d) {
            // $date = date('d-m-Y', strtotime($d->history_date));

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->kode_barang);
            $sheet->setCellValue('C' . $row, $d->nama_barang);
            $sheet->setCellValue('D' . $row, $d->qty);
            $sheet->setCellValue('E' . $row, $d->uom);
            $sheet->setCellValue('F' . $row, $d->harga_jual);
            $row++;
        }

        $sheet->getStyle('F2:F' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        foreach (range('A', 'F') as $col2) {
            $sheet->getColumnDimension($col2)->setAutoSize(true);
        }

        // Set nama file
        $filename = 'Master_Barang.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
}
