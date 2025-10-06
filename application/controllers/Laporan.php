<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('status') == FALSE || $this->session->userdata('level') != 1 && $this->session->userdata('level') != 2 && $this->session->userdata('level') != 3) {
            redirect(base_url("Login"));
        }
        // $this->load->library('Excel');
        $this->load->model('M_laporan', 'lap');
    }

    public function index()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Laporan Penjualan',
            'subtitle' => 'Report',
            'conten' => 'laporan/penjualan/index',
            'footer_js' => array('assets/js/lap_jual.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tableLapTrans()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $data['lap_trans'] = $this->lap->lap_trans($date_start, $date_end)->result();

        echo json_encode($this->load->view('laporan/penjualan/lap-trans-table', $data, false));
    }

    public function export_excel()
    {
        // Ambil tanggal dari input form
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');

        // Gunakan default jika kosong
        if (!$date_start) $date_start = date('Y-m-d', strtotime('-7 days'));
        if (!$date_end) $date_end = date('Y-m-d');

        // Ambil data dari model
        $headerData = $this->lap->export_excel_penjualan($date_start, $date_end)->result();
        $detailData = $this->lap->export_detail_penjualan($date_start, $date_end)->result();

        // Buat spreadsheet
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        // ============================
        // SHEET 1: HEADER TRANSAKSI
        // ============================
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Header');

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Transaksi');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Nominal');
        $sheet->setCellValue('E1', 'Tanggal Transaksi');

        // Isi data
        $row = 2;
        $x = 1;
        foreach ($headerData as $d) {
            $customer = ($d->pelanggan_id == '117') ? $d->lainnya : $d->cust;
            $date = date('d-m-Y', strtotime($d->tgl_transaksi));

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->no_transaksi);
            $sheet->setCellValue('C' . $row, $customer);
            $sheet->setCellValueExplicit('D' . $row, $d->grand_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValue('E' . $row, $date);

            $row++;
        }

        // Format kolom D sebagai Rupiah
        $sheet->getStyle('D2:D' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        // Auto size kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ============================
        // SHEET 2: DETAIL TRANSAKSI
        // ============================
        $detailSheet = $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $detailSheet = $spreadsheet->getActiveSheet();
        $detailSheet->setTitle('Detail');

        // Header kolom
        $detailSheet->setCellValue('A1', 'No');
        $detailSheet->setCellValue('B1', 'Kode Transaksi');
        $detailSheet->setCellValue('C1', 'Tanggal Transaksi');
        $detailSheet->setCellValue('D1', 'Kode Barang');
        $detailSheet->setCellValue('E1', 'Nama Barang');
        $detailSheet->setCellValue('F1', 'Qty');
        $detailSheet->setCellValue('G1', 'Harga Barang');

        // Isi data
        $row = 2;
        $x = 1;
        foreach ($detailData as $d) {
            $date = date('d-m-Y', strtotime($d->tgl_transaksi));

            $detailSheet->setCellValue('A' . $row, $x++);
            $detailSheet->setCellValue('B' . $row, $d->no_transaksi);
            $detailSheet->setCellValue('C' . $row, $date);
            $detailSheet->setCellValue('D' . $row, $d->kode_barang);
            $detailSheet->setCellValue('E' . $row, $d->nama_barang);
            $detailSheet->setCellValue('F' . $row, $d->qty);
            $detailSheet->setCellValueExplicit('G' . $row, $d->harga_barang, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

            $row++;
        }

        // Format kolom G sebagai Rupiah
        $detailSheet->getStyle('G2:G' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        // Auto size kolom
        foreach (range('A', 'G') as $col) {
            $detailSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Kembalikan ke sheet pertama sebagai default tampilan
        $spreadsheet->setActiveSheetIndex(0);

        // Set nama file
        $filename = 'Laporan_Penjualan_' . date('Ymd') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }




    public function getDetailTransaksi()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(["error" => "ID tidak ditemukan"]);
            return;
        }
        $data = $this->lap->lap_det_trans($id)->result();
        // Debugging: Pastikan data dari query benar
        if (!$data) {
            echo json_encode(["error" => "Data tidak ditemukan"]);
            return;
        }
        // Pastikan output JSON valid
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // iuran
    function lap_iuran()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Laporan Iuran Anggota',
            'subtitle' => 'Report',
            'conten' => 'laporan/iuran/index',
            'footer_js' => array('assets/js/lap_iuran.js'),
            'total' => $this->lap->total_iuran(),
        ];
        $this->load->view('template/conten', $data);
    }

    public function get_total_iuran()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $data = $this->lap->total_iuran($bulan, $tahun); // ganti Model_nama_model dengan nama model kamu
        echo json_encode($data);
    }

    function tableLapIuran()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $data['lap_iuran'] = $this->lap->lap_iuran($bulan, $tahun)->result();

        echo json_encode($this->load->view('laporan/iuran/lap-iuran-table', $data, false));
    }

    public function export_excel_iuran()
    {

        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('y'); // 2 digit tahun

        // Panggil data dari model
        $data_simpanan_wajib = $this->lap->getSimpananWajib($bulan, $tahun);
        $data_simpanan_pokok = $this->lap->getSimpananPokok();

        $spreadsheet = new Spreadsheet();

        // ================= Sheet 1 =================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Dana Simpanan Wajib');

        $sheet1->setCellValue('A1', 'Nama');
        $sheet1->setCellValue('B1', 'Nominal');

        $row1 = 2;
        foreach ($data_simpanan_wajib as $item) {
            $sheet1->setCellValue('A' . $row1, $item->nama_anggota);
            $sheet1->setCellValue('B' . $row1, $item->nominal);
            $row1++;
        }

        // Format kolom B sebagai format rupiah (B2 sampai baris terakhir)
        $sheet1->getStyle('B2:B' . ($row1 - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        foreach (range('A', 'B') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // ================= Sheet 2 =================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Dana Simpanan Pokok');

        $sheet2->setCellValue('A1', 'Nama');
        $sheet2->setCellValue('B1', 'Tanggal');
        $sheet2->setCellValue('C1', 'Nominal');

        $row2 = 2;
        foreach ($data_simpanan_pokok as $item) {
            $sheet2->setCellValue('A' . $row2, $item->name);
            $sheet2->setCellValue('B' . $row2, date('d-m-Y', strtotime($item->date)));
            $sheet2->setCellValue('C' . $row2, $item->nominal);
            $row2++;
        }

        // Format kolom C sebagai format rupiah
        $sheet2->getStyle('C2:C' . ($row2 - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        foreach (range('A', 'C') as $col2) {
            $sheet2->getColumnDimension($col2)->setAutoSize(true);
        }

        $filename = 'Laporan_Iuran_' . $bulan . '-' . $tahun . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function lap_barang()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Laporan barang',
            'subtitle' => 'Report',
            'conten' => 'laporan/barang/index',
            'footer_js' => array('assets/js/lap_barang.js')
        ];
        $this->load->view('template/conten', $data);
    }

    function tableLapBarang()
    {
        // $bulan = $this->input->post('bulan');
        // $tahun = $this->input->post('tahun');

        $data['barang'] = $this->lap->lap_barang()->result();

        echo json_encode($this->load->view('laporan/barang/lap-barang-table', $data, false));
    }

    public function getHistoryBarang()
    {
        $id = $this->input->post('id');
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        // Pastikan tanggal tidak kosong, jika kosong ambil 30 hari ke belakang
        if (empty($date_start)) {
            $date_start = date('Y-m-d', strtotime('-30 days'));
        }
        if (empty($date_end)) {
            $date_end = date('Y-m-d');
        }

        $history = $this->lap->history_barang($id, $date_start, $date_end)->result();

        // Ambil nama_barang jika ada data
        $nama_barang = !empty($history) ? $history[0]->nama_barang : "Unknown";

        foreach ($history as $row) {
            $row->history_date = !empty($row->history_date) ? date('Y-m-d', strtotime($row->history_date)) : null;
        }

        echo json_encode([
            "nama_barang" => $nama_barang,
            "history" => $history
        ]);
    }

    public function export_barang()
    {
        // Ambil tanggal dari input form
        $date_start = $this->input->get('date_start'); // Bisa pakai $this->input->post() jika pakai method POST
        $date_end = $this->input->get('date_end');

        // Gunakan default jika kosong
        if (!$date_start) $date_start = date('Y-m-d', strtotime('-7 days'));
        if (!$date_end) $date_end = date('Y-m-d');

        // Ambil data dari model berdasarkan tanggal input
        $data = $this->lap->export_barang($date_start, $date_end)->result();

        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Date History');
        $sheet->setCellValue('E1', 'QTY History');
        $sheet->setCellValue('F1', 'Harga Beli');
        $sheet->setCellValue('G1', 'Harga Jual');

        // Isi data
        $row = 2;
        $x = 1;
        foreach ($data as $d) {
            $date = date('d-m-Y', strtotime($d->history_date));

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->kode_barang);
            $sheet->setCellValue('C' . $row, $d->nama_barang);
            $sheet->setCellValue('D' . $row, $date);
            $sheet->setCellValue('E' . $row, $d->qty_history);
            $sheet->setCellValue('F' . $row, $d->harga_beli);
            $sheet->setCellValue('G' . $row, $d->harga_jual);
            $row++;
        }

        $sheet->getStyle('F2:F' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');
        
        $sheet->getStyle('G2:G' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        foreach (range('A', 'G') as $col2) {
            $sheet->getColumnDimension($col2)->setAutoSize(true);
        }

        // Set nama file
        $filename = 'Laporan_barang_' . date('Ymd') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


    function lap_keuangan()
    {
        $data = [
            'akses' => $this->session->userdata('level'),
            'name' => $this->session->userdata('nama'),
            'title' => 'Laporan Keuangan',
            'subtitle' => 'Report',
            'conten' => 'laporan/keuangan/index',
            'footer_js' => array('assets/js/lap_keuangan.js'),
            'kategori' => $this->m_data->get_data('tbl_kateg_trans'),
        ];
        $this->load->view('template/conten', $data);
    }

    function tableLapKeuangan()
    {
        // $date_param = $this->input->post('date_end');
        $periode = date('my');
        $data['keuangan'] = $this->lap->lap_keuangan($periode)->result();
        $data['sum_nominal'] = $this->lap->sum_nominal($periode)->result();

        echo json_encode($this->load->view('laporan/keuangan/lap-keuangan-table', $data, false));
    }

    // public function export_excel_keuangan()
    // {
    //     $date_end = $this->input->get('date_end');
    //     if (!$date_end) {
    //         $date_end = date('Y-m-d'); // Default jika tidak ada input
    //     }

    //     // Konversi date_end menjadi format 'my' (bulan 2 digit + tahun 2 digit)
    //     $periode = date('my', strtotime($date_end));

    //     // Ambil date_start (awal bulan dari date_end)
    //     $date_start = date('Y-m-01', strtotime($date_end));

    //     // Ambil data dari model berdasarkan kategori
    //     $pemasukan = $this->lap->in_keuangan($periode)->result();
    //     $pengeluaran = $this->lap->out_keuangan($periode)->result();

    //     // Ambil data Detail Pemasukan & Detail Pengeluaran
    //     $detail_pemasukan = $this->lap->getLapPemasukan($date_start, $date_end)->result();
    //     $detail_pengeluaran = $this->lap->getLapPengeluaran($date_start, $date_end)->result();

    //     // Buat Spreadsheet
    //     $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    //     // Buat Sheet Pemasukan
    //     $sheet1 = $spreadsheet->setActiveSheetIndex(0);
    //     $sheet1->setTitle('Pemasukan');
    //     $this->isiSheetKeuangan($sheet1, $pemasukan);

    //     // Buat Sheet Pengeluaran
    //     $sheet2 = $spreadsheet->createSheet();
    //     $sheet2->setTitle('Pengeluaran');
    //     $this->isiSheetKeuangan($sheet2, $pengeluaran);

    //     // Buat Sheet Detail Pemasukan
    //     $sheet3 = $spreadsheet->createSheet();
    //     $sheet3->setTitle('Detail Pemasukan');
    //     $this->isiSheetDetailPemasukan($sheet3, $detail_pemasukan);

    //     // Buat Sheet Detail Pengeluaran
    //     $sheet4 = $spreadsheet->createSheet();
    //     $sheet4->setTitle('Detail Pengeluaran');
    //     $this->isiSheetDetailPengeluaran($sheet4, $detail_pengeluaran);

    //     // Set nama file
    //     $filename = 'Laporan_Keuangan_' . $periode . '.xlsx';

    //     // Set header untuk download
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');

    //     ob_end_clean();
    //     $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //     $writer->save('php://output');
    //     exit;
    // }

    public function export_excel_keuangan()
    {
        $date_end = $this->input->get('date_end');
        if (!$date_end) {
            $date_end = date('Y-m-d'); // Default jika tidak ada input
        }

        // Konversi date_end menjadi format 'my' (bulan 2 digit + tahun 2 digit)
        $periode = date('my', strtotime($date_end));

        // Ambil date_start (awal bulan dari date_end)
        $date_start = date('Y-m-01', strtotime($date_end));

        // Ambil data dari model
        $keuangan = $this->lap->lap_keuangan()->result();
        $sum_nominal = $this->lap->sum_nominal()->row(); // Ambil total pemasukan & pengeluaran

        $total_pemasukan = $sum_nominal->pemasukan ?? 0;
        $total_pengeluaran = $sum_nominal->pengeluaran ?? 0;
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        // Ambil Detail Pemasukan & Pengeluaran dengan kedua parameter yang benar
        $detail_pemasukan = $this->lap->getLapPemasukan($date_start, $date_end)->result();
        $detail_pengeluaran = $this->lap->getLapPengeluaran($date_start, $date_end)->result();

        // Buat Spreadsheet
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        // **Buat Sheet Resume Keuangan**
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);
        $sheet1->setTitle('Resume Keuangan');
        $this->isiSheetResumeKeuangan($sheet1, $keuangan, $total_pemasukan, $total_pengeluaran, $saldo_akhir);

        // **Buat Sheet Detail Pemasukan**
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Detail Pemasukan');
        $this->isiSheetDetailPemasukan($sheet2, $detail_pemasukan);

        // **Buat Sheet Detail Pengeluaran**
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Detail Pengeluaran');
        $this->isiSheetDetailPengeluaran($sheet3, $detail_pengeluaran);

        // **Set nama file**
        $filename = 'Laporan_Keuangan_' . $periode . '.xlsx';

        // **Set header untuk download**
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function isiSheetKeuangan($sheet, $data)
    {
        // Header kolom
        $headers = ['A1' => 'No', 'B1' => 'Nama Kategori', 'C1' => 'Periode', 'D1' => 'Kategori', 'E1' => 'Nominal'];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true); // Buat Bold
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {
            $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $formatter->setPattern('MMMM yyyy'); // Format Indonesia

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->kateg_trans);
            $sheet->setCellValue('C' . $row, $formatter->format(new DateTime($d->periode)));
            $sheet->setCellValue('D' . $row, $d->kode);
            $sheet->setCellValue('E' . $row, $d->nominal);

            // Format Nominal menjadi angka dengan style keuangan & rata kanan
            $sheet->getStyle('E' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('E' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->nominal;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("E{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("E{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("E{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function isiSheetDetailPemasukan($sheet, $data)
    {
        // Header kolom
        $headers = [
            'A1' => 'No',
            'B1' => 'Kategori Transaksi',
            'C1' => 'Tanggal',
            'D1' => 'Keterangan',
            'E1' => 'Nominal'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->kategori_trans);
            $sheet->setCellValue('C' . $row, $d->date);
            $sheet->setCellValue('D' . $row, $d->keterangan);
            $sheet->setCellValue('E' . $row, $d->nominal);

            // Format Nominal
            $sheet->getStyle('E' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('E' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->nominal;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("E{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("E{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("E{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function isiSheetDetailPengeluaran($sheet, $data)
    {
        // Header kolom
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Kategori',
            'C1' => 'Sumber Dana',
            'D1' => 'Tanggal',
            'E1' => 'Keterangan',
            'F1' => 'Nominal'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->nama_kategori);
            $sheet->setCellValue('C' . $row, $d->nama_sumber_dana);
            $sheet->setCellValue('D' . $row, $d->date);
            $sheet->setCellValue('E' . $row, $d->keterangan);
            $sheet->setCellValue('F' . $row, $d->nominal);

            // Format Nominal
            $sheet->getStyle('F' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('F' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->nominal;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("F{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
        $sheet->getStyle("F{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("F{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function isiSheetResumeKeuangan($sheet, $keuangan, $total_pemasukan, $total_pengeluaran, $saldo_akhir)
    {
        // Header Kolom
        $headers = ['A1' => 'No', 'B1' => 'Kategori', 'C1' => 'Nominal'];
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi Data
        $row = 2;
        $x = 1;
        foreach ($keuangan as $data) {
            $sheet->setCellValue("A{$row}", $x++);
            $sheet->setCellValue("B{$row}", $data->kateg_trans);
            $sheet->setCellValue("C{$row}", $data->nominal);

            // Format angka
            $sheet->getStyle("C{$row}")
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle("C{$row}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $row++;
        }

        // Tambahkan Total Pemasukan dan Total Pengeluaran
        $row++;
        $sheet->setCellValue("B{$row}", "Total Pemasukan");
        $sheet->setCellValue("C{$row}", $total_pemasukan);

        $row++;
        $sheet->setCellValue("B{$row}", "Total Pengeluaran");
        $sheet->setCellValue("C{$row}", $total_pengeluaran);

        $row++;
        $sheet->setCellValue("B{$row}", "Saldo Akhir");
        $sheet->setCellValue("C{$row}", $saldo_akhir);

        // Buat bold dan format angka
        $boldCells = ["B" . ($row - 2), "C" . ($row - 2), "B" . ($row - 1), "C" . ($row - 1), "B{$row}", "C{$row}"];
        foreach ($boldCells as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        foreach (["C" . ($row - 2), "C" . ($row - 1), "C{$row}"] as $cell) {
            $sheet->getStyle($cell)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle($cell)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }

        // Auto-size kolom
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }





    public function getTotalTransaksiPOS()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $query = $this->db->query("
        SELECT SUM(grand_total) AS total 
        FROM tbl_transaksi 
        WHERE DATE(tgl_transaksi) >= ? 
        AND DATE(tgl_transaksi) <= ? 
        AND metode_bayar = '1'", [$date_start, $date_end]);

        $result = $query->row();

        if ($result && $result->total) {
            echo json_encode([
                "status" => true,
                "kategori" => "Transaksi POS",
                "total" => number_format($result->total, 0, ",", ".")
            ]);
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function getTotalDeposit()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $query = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tbl_deposit 
        WHERE DATE(date) >= ? 
        AND DATE(date) <= ?", [$date_start, $date_end]);

        $result = $query->row();

        if ($result && $result->total) {
            echo json_encode([
                "status" => true,
                "kategori" => "Deposit",
                "total" => number_format($result->total, 0, ",", ".")
            ]);
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function getTotalIuran()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $query = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tbl_iuran 
        WHERE DATE(date) >= ? 
        AND DATE(date) <= ? 
        AND status = '1'", [$date_start, $date_end]);

        $result = $query->row();

        if ($result && $result->total) {
            echo json_encode([
                "status" => true,
                "kategori" => "Iuran",
                "total" => number_format($result->total, 0, ",", ".")
            ]);
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function tableLapKeuanganMasuk()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        // Ambil data pemasukan dari model
        $data['keuangan_masuk'] = $this->lap->getLapPemasukan($date_start, $date_end)->result();

        // Kirim hasil query ke view tabel pemasukan
        echo json_encode($this->load->view('laporan/keuangan/lap-keuangan-in-table', $data, false));
    }

    public function getTotalIn()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $query = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tbl_pemasukan 
        WHERE DATE(date) >= ? 
        AND DATE(date) <= ?", [$date_start, $date_end]);

        $result = $query->row();

        if ($result && $result->total) {
            echo json_encode([
                "status" => true,
                "kategori" => "Pemasukan",
                "total" => number_format($result->total, 0, ",", ".")
            ]);
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function tableLapKeuanganKeluar()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        // Ambil data pemasukan dari model
        $data['keuangan_keluar'] = $this->lap->getLapPengeluaran($date_start, $date_end)->result();

        // Kirim hasil query ke view tabel pemasukan
        echo json_encode($this->load->view('laporan/keuangan/lap-keuangan-out-table', $data, false));
    }

    public function getTotalOut()
    {
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $query = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tbl_pengeluaran 
        WHERE DATE(date) >= ? 
        AND DATE(date) <= ?", [$date_start, $date_end]);

        $result = $query->row();

        if ($result && $result->total) {
            echo json_encode([
                "status" => true,
                "kategori" => "Pengeluaran",
                "total" => number_format($result->total, 0, ",", ".")
            ]);
        } else {
            echo json_encode(["status" => false]);
        }
    }

    public function export_excel_all()
    {
        $date_end = $this->input->get('date_end');
        if (!$date_end) {
            $date_end = date('Y-m-d'); // Default jika tidak ada input
        }

        // Konversi date_end menjadi format 'my' (bulan 2 digit + tahun 2 digit)
        $periode = date('my', strtotime($date_end));

        // Ambil date_start (awal bulan dari date_end)
        $date_start = date('Y-m-01', strtotime($date_end));

        // Ambil data dari model
        $keuangan = $this->lap->lap_keuangan($periode)->result();
        $sum_nominal = $this->lap->sum_nominal($periode)->row(); // Ambil total pemasukan & pengeluaran

        $total_pemasukan = $sum_nominal->pemasukan ?? 0;
        $total_pengeluaran = $sum_nominal->pengeluaran ?? 0;
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        // Ambil Detail Pemasukan & Pengeluaran dengan kedua parameter yang benar
        $detail_pemasukan = $this->lap->getLapPemasukan($date_start, $date_end)->result();
        $detail_pengeluaran = $this->lap->getLapPengeluaran($date_start, $date_end)->result();
        $detail_transaksi = $this->lap->export_excel_penjualan($date_start, $date_end)->result();
        $detail_iuran = $this->lap->export_iuran_nominal($date_start, $date_end)->result();
        $detail_deposit  = $this->lap->export_deposit_nominal()->result();


        // Buat Spreadsheet
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        // **Buat Sheet Resume Keuangan**
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);
        $sheet1->setTitle('Resume Keuangan');
        $this->isiSheetResumeKeuangan($sheet1, $keuangan, $total_pemasukan, $total_pengeluaran, $saldo_akhir);

        // **Buat Sheet Detail Pemasukan**
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Detail Pemasukan');
        $this->isiSheetDetailPemasukan($sheet2, $detail_pemasukan);

        // **Buat Sheet Detail Pengeluaran**
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Detail Pengeluaran');
        $this->isiSheetDetailPengeluaran($sheet3, $detail_pengeluaran);

        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Detail Penjualan');
        $this->isiSheetTransaksi($sheet4, $detail_transaksi);

        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Detail Simpanan Wajib');
        $this->isiSheetIuran($sheet5, $detail_iuran);

        $sheet6 = $spreadsheet->createSheet();
        $sheet6->setTitle('Detail Simpanan Pokok');
        $this->isiSheetDeposit($sheet6, $detail_deposit);


        // **Set nama file**
        $filename = 'Laporan_Keuangan_All' . $periode . '.xlsx';

        // **Set header untuk download**
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function isiSheetTransaksi($sheet, $data)
    {
        // Header kolom
        $headers = [
            'A1' => 'No',
            'B1' => 'Kode Transaksi',
            'C1' => 'Pembeli',
            'D1' => 'Tanggal Transaksi',
            'E1' => 'Nominal'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {
            $customer = ($d->pelanggan_id == '117') ? $d->lainnya : $d->cust;
            $date = date('d-m-Y', strtotime($d->tgl_transaksi));

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->no_transaksi);
            $sheet->setCellValue('C' . $row, $customer);
            $sheet->setCellValue('D' . $row, $date);
            $sheet->setCellValue('E' . $row, $d->grand_total);

            // Format Nominal
            $sheet->getStyle('E' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('E' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->grand_total;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("E{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("E{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("E{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function isiSheetIuran($sheet, $data)
    {
        // Header kolom
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Anggota',
            'C1' => 'Periode Pembayaran',
            'D1' => 'Nominal'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->name);
            $sheet->setCellValue('C' . $row, $d->periode_tergabung);
            $sheet->setCellValue('D' . $row, $d->total);

            // Format Nominal
            $sheet->getStyle('D' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('D' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->total;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("D{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("D{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function isiSheetDeposit($sheet, $data)
    {
        // Header kolom
        $headers = [
            'A1' => 'No',
            'B1' => 'Nama Anggota',
            'C1' => 'Tanggal Pembayaran',
            'D1' => 'Nominal'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Isi data
        $row = 2;
        $x = 1;
        $total = 0;

        foreach ($data as $d) {

            $sheet->setCellValue('A' . $row, $x++);
            $sheet->setCellValue('B' . $row, $d->name);
            $sheet->setCellValue('C' . $row, date('d-m-Y', strtotime($d->date)));
            $sheet->setCellValue('D' . $row, $d->total);

            // Format Nominal
            $sheet->getStyle('D' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle('D' . $row)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $total += $d->total;
            $row++;
        }

        // Baris Total
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->setCellValue("A{$row}", "TOTAL");
        $sheet->setCellValue("D{$row}", $total);

        // Styling untuk total
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
        $sheet->getStyle("D{$row}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        $sheet->getStyle("D{$row}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
